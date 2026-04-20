<?php

declare(strict_types=1);

function fail(string $message): never
{
    fwrite(STDERR, "FAIL: {$message}\n");
    exit(1);
}

function assert_true(bool $condition, string $message): void
{
    if (!$condition) {
        fail($message);
    }
}

$tempDir = sys_get_temp_dir() . '/bloom-config-test-' . bin2hex(random_bytes(4));
if (!mkdir($tempDir, 0777, true) && !is_dir($tempDir)) {
    fail('Could not create temp directory');
}

register_shutdown_function(static function () use ($tempDir): void {
    if (!is_dir($tempDir)) {
        return;
    }

    $files = scandir($tempDir);
    if ($files === false) {
        return;
    }

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        @unlink($tempDir . '/' . $file);
    }

    @rmdir($tempDir);
});

$configSource = __DIR__ . '/../config.php.example';
$envContents = <<<ENV
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=test_db
DB_USER=test_user
DB_PASS=test_pass
ADMIN_PASSWORD_HASH=test_hash
TELEGRAM_BOT_TOKEN=test_bot_token
TELEGRAM_SELLER_CHAT_ID=123456
WA_NUMBER=2349049308656
TELEGRAM_LINK=https://t.me/+2349049308656
APP_ENV=production
ENV;

assert_true(copy($configSource, $tempDir . '/config.php'), 'Could not copy config.php.example');
assert_true(file_put_contents($tempDir . '/.env', $envContents) !== false, 'Could not write temp .env');

require $tempDir . '/config.php';

assert_true(defined('DB_HOST') && DB_HOST === '127.0.0.1', 'DB_HOST not loaded');
assert_true(defined('DB_PORT') && DB_PORT === 3306, 'DB_PORT not loaded as int');
assert_true(defined('DB_NAME') && DB_NAME === 'test_db', 'DB_NAME not loaded');
assert_true(defined('ADMIN_PASSWORD_HASH') && ADMIN_PASSWORD_HASH === 'test_hash', 'ADMIN_PASSWORD_HASH not loaded');
assert_true(defined('TELEGRAM_BOT_TOKEN') && TELEGRAM_BOT_TOKEN === 'test_bot_token', 'TELEGRAM_BOT_TOKEN not loaded');
assert_true(defined('WA_NUMBER') && WA_NUMBER === '2349049308656', 'WA_NUMBER not loaded');
assert_true(function_exists('get_db'), 'get_db helper missing');
assert_true(function_exists('get_config'), 'get_config helper missing');
assert_true(get_config('missing_key', 'fallback') === 'fallback', 'get_config default fallback failed');

fwrite(STDOUT, "PASS: config.php.example bootstrap smoke test\n");
