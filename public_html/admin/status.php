<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/settings.php';

admin_require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/settings.php', true, 302);
    exit;
}

$status = strtolower(trim((string) ($_POST['seller_status'] ?? 'online')));
$message = trim((string) ($_POST['status_message'] ?? ''));

try {
    if (!in_array($status, ['online', 'brb', 'offline'], true)) {
        throw new InvalidArgumentException('Invalid seller status.');
    }

    admin_update_status($status, $message);
    app_flash_set('success', 'Seller status updated.');
} catch (Throwable $e) {
    app_flash_set('error', $e instanceof InvalidArgumentException ? $e->getMessage() : 'Seller status could not be updated.');
}

header('Location: /admin/settings.php', true, 302);
exit;
