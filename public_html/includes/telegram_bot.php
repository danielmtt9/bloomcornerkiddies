<?php

declare(strict_types=1);

function telegram_bot_storage_dir(): string
{
    return sys_get_temp_dir() . '/bloomrocxx-telegram-bot';
}

function telegram_bot_session_path(string $chatId): string
{
    return telegram_bot_storage_dir() . '/chat-' . preg_replace('/[^0-9\-]/', '', $chatId) . '.json';
}

function telegram_bot_default_session(string $chatId): array
{
    return [
        'chat_id' => $chatId,
        'step' => 'awaiting_product',
        'data' => [
            'product' => '',
            'size' => '',
            'buyer_name' => '',
            'address' => '',
            'gift_flow' => '',
        ],
        'updated_at' => gmdate('c'),
    ];
}

function telegram_bot_load_session(string $chatId): array
{
    $path = telegram_bot_session_path($chatId);
    if (!is_file($path)) {
        return telegram_bot_default_session($chatId);
    }

    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') {
        return telegram_bot_default_session($chatId);
    }

    $decoded = json_decode($raw, true);
    if (!is_array($decoded) || !isset($decoded['step']) || !isset($decoded['data'])) {
        return telegram_bot_default_session($chatId);
    }

    return array_merge(telegram_bot_default_session($chatId), $decoded);
}

function telegram_bot_save_session(array $session): void
{
    $dir = telegram_bot_storage_dir();
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $session['updated_at'] = gmdate('c');
    file_put_contents(
        telegram_bot_session_path((string) $session['chat_id']),
        json_encode($session, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    );
}

function telegram_bot_summary(array $data): string
{
    return "Please confirm your order:\n"
        . "- Product: {$data['product']}\n"
        . "- Size: {$data['size']}\n"
        . "- Buyer: {$data['buyer_name']}\n"
        . "- Address: {$data['address']}\n"
        . "- Gift order: {$data['gift_flow']}\n\n"
        . "Reply CONFIRM to complete, or RESTART to start over.";
}

function telegram_bot_transition(array $session, string $input): array
{
    $text = trim($input);
    $normalized = strtolower($text);

    if ($text === '' && $session['step'] !== 'awaiting_product') {
        return [$session, 'I did not catch that. Please send a valid response.'];
    }

    if (in_array($normalized, ['/start', 'start', 'restart'], true)) {
        $reset = telegram_bot_default_session((string) $session['chat_id']);
        return [$reset, 'Welcome to Bloom Corner Kiddies bot. What product do you want to order?'];
    }

    switch ($session['step']) {
        case 'awaiting_product':
            if ($text === '') {
                return [$session, 'What product do you want to order?'];
            }
            $session['data']['product'] = $text;
            $session['step'] = 'awaiting_size';
            return [$session, 'Great. Which size do you need?'];

        case 'awaiting_size':
            $session['data']['size'] = $text;
            $session['step'] = 'awaiting_buyer_name';
            return [$session, 'Got it. What is the buyer full name?'];

        case 'awaiting_buyer_name':
            $session['data']['buyer_name'] = $text;
            $session['step'] = 'awaiting_address';
            return [$session, 'Thanks. Please send the delivery address.'];

        case 'awaiting_address':
            $session['data']['address'] = $text;
            $session['step'] = 'awaiting_gift_flow';
            return [$session, 'Is this a gift order? Reply YES or NO.'];

        case 'awaiting_gift_flow':
            if (!in_array($normalized, ['yes', 'no', 'y', 'n'], true)) {
                return [$session, 'Please reply YES or NO for gift order.'];
            }
            $session['data']['gift_flow'] = in_array($normalized, ['yes', 'y'], true) ? 'yes' : 'no';
            $session['step'] = 'awaiting_confirmation';
            return [$session, telegram_bot_summary($session['data'])];

        case 'awaiting_confirmation':
            if ($normalized === 'confirm') {
                $session['step'] = 'completed';
                return [$session, 'Confirmed. Your order flow is complete. A seller will follow up soon.'];
            }
            if (in_array($normalized, ['restart', 'start'], true)) {
                $reset = telegram_bot_default_session((string) $session['chat_id']);
                return [$reset, 'Restarted. What product do you want to order?'];
            }
            return [$session, 'Reply CONFIRM to complete, or RESTART to start over.'];

        case 'completed':
            return [$session, 'This order is already confirmed. Reply RESTART to create a new order.'];

        default:
            $fallback = telegram_bot_default_session((string) $session['chat_id']);
            return [$fallback, 'Session reset. What product do you want to order?'];
    }
}

function telegram_bot_send_message(string $chatId, string $text): bool
{
    $payload = [
        'chat_id' => $chatId,
        'text' => $text,
    ];

    $url = 'https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/sendMessage';
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'timeout' => 5,
        ],
    ]);

    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        return false;
    }

    $decoded = json_decode($response, true);
    return is_array($decoded) && ($decoded['ok'] ?? false) === true;
}

function telegram_bot_is_completion_transition(array $previousSession, array $nextSession): bool
{
    return ($previousSession['step'] ?? '') !== 'completed'
        && ($nextSession['step'] ?? '') === 'completed';
}

function telegram_bot_resolve_notification_destination(): array
{
    $mode = strtolower(trim(env_value('TELEGRAM_ORDER_DESTINATION_MODE', 'disabled')));

    if ($mode === 'dm') {
        $chatId = trim(env_value('TELEGRAM_SELLER_CHAT_ID'));
        if ($chatId === '') {
            return ['enabled' => false, 'mode' => 'dm', 'chat_id' => '', 'error' => 'missing TELEGRAM_SELLER_CHAT_ID'];
        }
        return ['enabled' => true, 'mode' => 'dm', 'chat_id' => $chatId, 'error' => ''];
    }

    if ($mode === 'channel') {
        $chatId = trim(env_value('TELEGRAM_SELLER_CHANNEL_ID'));
        if ($chatId === '') {
            return ['enabled' => false, 'mode' => 'channel', 'chat_id' => '', 'error' => 'missing TELEGRAM_SELLER_CHANNEL_ID'];
        }
        return ['enabled' => true, 'mode' => 'channel', 'chat_id' => $chatId, 'error' => ''];
    }

    return ['enabled' => false, 'mode' => 'disabled', 'chat_id' => '', 'error' => 'destination mode disabled'];
}

function telegram_bot_build_order_notification(array $data, string $sourceChatId): string
{
    return "New Telegram order confirmed:\n"
        . "- Source chat: {$sourceChatId}\n"
        . "- Product: {$data['product']}\n"
        . "- Size: {$data['size']}\n"
        . "- Buyer: {$data['buyer_name']}\n"
        . "- Address: {$data['address']}\n"
        . "- Gift order: {$data['gift_flow']}";
}

function telegram_bot_notify_seller(array $session, string $sourceChatId): array
{
    $destination = telegram_bot_resolve_notification_destination();
    if (($destination['enabled'] ?? false) !== true) {
        return [
            'attempted' => false,
            'sent' => false,
            'mode' => $destination['mode'] ?? 'disabled',
            'reason' => $destination['error'] ?? 'destination disabled',
        ];
    }

    $text = telegram_bot_build_order_notification($session['data'] ?? [], $sourceChatId);
    $sent = telegram_bot_send_message((string) $destination['chat_id'], $text);

    return [
        'attempted' => true,
        'sent' => $sent,
        'mode' => $destination['mode'],
        'reason' => $sent ? '' : 'sendMessage failed',
    ];
}
