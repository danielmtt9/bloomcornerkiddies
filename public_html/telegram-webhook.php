<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/telegram_bot.php';

function webhook_respond(array $payload, int $statusCode = 200): never
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function webhook_log(string $message): void
{
    error_log('[telegram-webhook] ' . $message);
}

function webhook_normalize_update(array $update): array
{
    $updateId = isset($update['update_id']) ? (int) $update['update_id'] : 0;

    if (isset($update['message']) && is_array($update['message'])) {
        return [
            'update_id' => $updateId,
            'type' => 'message',
            'chat_id' => isset($update['message']['chat']['id']) ? (string) $update['message']['chat']['id'] : '',
            'text' => isset($update['message']['text']) ? (string) $update['message']['text'] : '',
        ];
    }

    if (isset($update['callback_query']) && is_array($update['callback_query'])) {
        return [
            'update_id' => $updateId,
            'type' => 'callback_query',
            'chat_id' => isset($update['callback_query']['message']['chat']['id']) ? (string) $update['callback_query']['message']['chat']['id'] : '',
            'text' => isset($update['callback_query']['data']) ? (string) $update['callback_query']['data'] : '',
        ];
    }

    return [
        'update_id' => $updateId,
        'type' => 'unsupported',
        'chat_id' => '',
        'text' => '',
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    webhook_respond([
        'ok' => false,
        'error' => 'Method not allowed',
    ], 405);
}

if (TELEGRAM_BOT_TOKEN === '') {
    webhook_log('missing TELEGRAM_BOT_TOKEN');
    webhook_respond([
        'ok' => false,
        'error' => 'Webhook unavailable',
    ], 503);
}

$expectedSecret = env_value('TELEGRAM_WEBHOOK_SECRET');
if ($expectedSecret !== '') {
    $providedSecret = (string) ($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? '');
    if (!hash_equals($expectedSecret, $providedSecret)) {
        webhook_log('secret validation failed');
        webhook_respond([
            'ok' => false,
            'error' => 'Unauthorized',
        ], 401);
    }
}

$rawBody = file_get_contents('php://input');
if ($rawBody === false || trim($rawBody) === '') {
    webhook_log('empty payload');
    webhook_respond([
        'ok' => true,
        'status' => 'ignored',
        'reason' => 'empty_payload',
    ]);
}

try {
    $decoded = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $exception) {
    webhook_log('invalid json payload');
    webhook_respond([
        'ok' => true,
        'status' => 'ignored',
        'reason' => 'invalid_json',
    ]);
}

if (!is_array($decoded)) {
    webhook_log('payload was not object');
    webhook_respond([
        'ok' => true,
        'status' => 'ignored',
        'reason' => 'invalid_payload_shape',
    ]);
}

$update = webhook_normalize_update($decoded);
if ($update['type'] === 'unsupported') {
    webhook_log('ignored unsupported update type for update_id=' . $update['update_id']);
    webhook_respond([
        'ok' => true,
        'status' => 'ignored',
        'reason' => 'unsupported_update_type',
    ]);
}

webhook_log(
    sprintf(
        'accepted type=%s update_id=%d chat_id=%s',
        $update['type'],
        $update['update_id'],
        $update['chat_id'] === '' ? 'unknown' : $update['chat_id']
    )
);

$replyText = '';
if ($update['chat_id'] !== '') {
    $session = telegram_bot_load_session($update['chat_id']);
    [$nextSession, $replyText] = telegram_bot_transition($session, $update['text']);
    telegram_bot_save_session($nextSession);

    if (telegram_bot_is_completion_transition($session, $nextSession)) {
        $notify = telegram_bot_notify_seller($nextSession, $update['chat_id']);
        if (($notify['attempted'] ?? false) !== true) {
            webhook_log('seller notification skipped: ' . ($notify['reason'] ?? 'unknown'));
        } elseif (($notify['sent'] ?? false) !== true) {
            webhook_log('seller notification failed: ' . ($notify['reason'] ?? 'unknown'));
        } else {
            webhook_log('seller notification sent via mode=' . ($notify['mode'] ?? 'unknown'));
        }
    }

    if ($replyText !== '') {
        $sent = telegram_bot_send_message($update['chat_id'], $replyText);
        if (!$sent) {
            webhook_log('failed to send bot reply for chat_id=' . $update['chat_id']);
        }
    }
}

webhook_respond([
    'ok' => true,
    'status' => 'accepted',
    'update_type' => $update['type'],
    'handled_chat' => $update['chat_id'] !== '',
]);
