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

$root = dirname(__DIR__);
$tempDir = sys_get_temp_dir() . '/bloom-bootstrap-' . bin2hex(random_bytes(4));
$publicDir = $tempDir . '/public_html';
$includesDir = $publicDir . '/includes';

foreach ([$tempDir, $publicDir, $includesDir] as $dir) {
    if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
        fail("Could not create directory: {$dir}");
    }
}

register_shutdown_function(static function () use ($tempDir): void {
    if (!is_dir($tempDir)) {
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($tempDir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $fileInfo) {
        $path = $fileInfo->getPathname();
        $fileInfo->isDir() ? @rmdir($path) : @unlink($path);
    }

    @rmdir($tempDir);
});

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

assert_true(copy($root . '/config.php.example', $tempDir . '/config.php'), 'Could not copy config.php.example');
assert_true(copy($root . '/public_html/includes/bootstrap.php', $includesDir . '/bootstrap.php'), 'Could not copy bootstrap include');
assert_true(file_put_contents($tempDir . '/.env', $envContents) !== false, 'Could not write temp .env');

$_SERVER['HTTPS'] = 'on';

require $includesDir . '/bootstrap.php';

assert_true(function_exists('app_start_session'), 'app_start_session missing');
assert_true(function_exists('app_require_admin'), 'app_require_admin missing');
assert_true(function_exists('app_json'), 'app_json missing');
assert_true(function_exists('app_flash_set'), 'app_flash_set missing');
assert_true(function_exists('app_flash_get'), 'app_flash_get missing');
assert_true(function_exists('app_flash_has'), 'app_flash_has missing');
assert_true(function_exists('get_db'), 'get_db missing');
assert_true(function_exists('get_config'), 'get_config missing');

app_start_session();
assert_true(session_status() === PHP_SESSION_ACTIVE, 'session did not start');

app_flash_set('notice', 'Saved');
assert_true(app_flash_has('notice'), 'flash message was not stored');
assert_true(app_flash_get('notice') === 'Saved', 'flash message was not retrieved');
assert_true(app_flash_get('notice', 'fallback') === 'fallback', 'flash message was not cleared after retrieval');
assert_true(get_config('missing_key', 'fallback') === 'fallback', 'get_config fallback failed');

fwrite(STDOUT, "PASS: PHP bootstrap helper smoke test\n");
