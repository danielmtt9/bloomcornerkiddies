<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config.php';

function app_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Strict',
    ]);

    session_start();
}

function app_require_admin(): void
{
    app_start_session();

    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('Location: /admin/login.php');
        exit;
    }
}

function app_json(array $payload, int $statusCode = 200): never
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function app_flash_set(string $key, string $message): void
{
    app_start_session();
    $_SESSION['_flash'][$key] = $message;
}

function app_flash_get(string $key, string $default = ''): string
{
    app_start_session();
    if (!isset($_SESSION['_flash'][$key])) {
        return $default;
    }

    $message = (string) $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);

    return $message;
}

function app_flash_has(string $key): bool
{
    app_start_session();
    return isset($_SESSION['_flash'][$key]);
}
