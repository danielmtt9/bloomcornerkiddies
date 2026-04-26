<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../public_html/includes/telegram_bot.php';

function assert_true(bool $condition, string $message): void
{
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

$session = telegram_bot_default_session('12345');

[$session, $reply] = telegram_bot_transition($session, '/start');
assert_true($session['step'] === 'awaiting_product', 'start should reset to awaiting_product');
assert_true(str_contains($reply, 'What product'), 'start reply should ask for product');

[$session, $reply] = telegram_bot_transition($session, 'Floral Dress');
assert_true($session['step'] === 'awaiting_size', 'should move to awaiting_size');

[$session, $reply] = telegram_bot_transition($session, '4-5Y');
assert_true($session['step'] === 'awaiting_buyer_name', 'should move to awaiting_buyer_name');

[$session, $reply] = telegram_bot_transition($session, 'Ada Obi');
assert_true($session['step'] === 'awaiting_address', 'should move to awaiting_address');

[$session, $reply] = telegram_bot_transition($session, 'Lekki Phase 1');
assert_true($session['step'] === 'awaiting_gift_flow', 'should move to awaiting_gift_flow');

[$session, $reply] = telegram_bot_transition($session, 'yes');
assert_true($session['step'] === 'awaiting_confirmation', 'should move to awaiting_confirmation');
assert_true(str_contains($reply, 'Please confirm your order'), 'should show explicit confirmation summary');

[$session, $reply] = telegram_bot_transition($session, 'confirm');
assert_true($session['step'] === 'completed', 'confirm should complete flow');
assert_true(str_contains(strtolower($reply), 'complete'), 'confirm reply should indicate completion');

$previous = ['step' => 'awaiting_confirmation'];
$next = ['step' => 'completed'];
assert_true(telegram_bot_is_completion_transition($previous, $next), 'completion transition detection should be true');

$notification = telegram_bot_build_order_notification([
    'product' => 'Floral Dress',
    'size' => '4-5Y',
    'buyer_name' => 'Ada Obi',
    'address' => 'Lekki Phase 1',
    'gift_flow' => 'yes',
], '12345');
assert_true(str_contains($notification, 'New Telegram order confirmed'), 'notification payload should include heading');
assert_true(str_contains($notification, 'Source chat: 12345'), 'notification payload should include source chat id');

echo "PASS: telegram conversational order flow\n";
