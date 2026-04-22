<?php

declare(strict_types=1);

require_once __DIR__ . '/products.php';

const SELLER_CONFIG_KEYS = [
    'store_name',
    'tagline',
    'intro_text',
    'wa_number',
    'telegram_link',
    'delivery_info',
    'status_message',
    'payment_info',
    'seller_status',
    'referral_discount_percent',
];

function admin_project_root(): string
{
    return dirname(__DIR__, 2);
}

function admin_env_file_path(): string
{
    return admin_project_root() . '/.env';
}

function admin_fetch_seller_config_map(): array
{
    $rows = get_db()->query(
        'SELECT `key`, value FROM seller_config WHERE `key` IN ("' . implode('","', SELLER_CONFIG_KEYS) . '") ORDER BY `key` ASC'
    )->fetchAll();

    $map = [];
    foreach ($rows as $row) {
        $map[(string) $row['key']] = $row['value'];
    }

    foreach (SELLER_CONFIG_KEYS as $key) {
        if (!array_key_exists($key, $map)) {
            $map[$key] = null;
        }
    }

    return $map;
}

function admin_settings_form_defaults(): array
{
    $config = admin_fetch_seller_config_map();

    return [
        'store_name' => (string) ($config['store_name'] ?? ''),
        'tagline' => (string) ($config['tagline'] ?? ''),
        'intro_text' => (string) ($config['intro_text'] ?? ''),
        'wa_number' => (string) ($config['wa_number'] ?? ''),
        'telegram_link' => (string) ($config['telegram_link'] ?? ''),
        'delivery_info' => (string) ($config['delivery_info'] ?? ''),
        'status_message' => (string) ($config['status_message'] ?? ''),
        'payment_info' => (string) ($config['payment_info'] ?? ''),
        'seller_status' => (string) ($config['seller_status'] ?? 'online'),
        'referral_discount_percent' => $config['referral_discount_percent'] === null ? '' : (string) $config['referral_discount_percent'],
    ];
}

function admin_normalize_referral_discount(?string $value): ?string
{
    $value = trim((string) $value);
    if ($value === '') {
        return null;
    }

    if (!ctype_digit($value)) {
        throw new InvalidArgumentException('Referral discount must be blank or a whole number.');
    }

    $int = (int) $value;
    if ($int < 0 || $int > 100) {
        throw new InvalidArgumentException('Referral discount must be between 0 and 100.');
    }

    return (string) $int;
}

function admin_validate_settings_payload(array $data): array
{
    $errors = [];

    if (trim((string) ($data['store_name'] ?? '')) === '') {
        $errors[] = 'Store name is required.';
    }

    if (trim((string) ($data['wa_number'] ?? '')) === '') {
        $errors[] = 'WhatsApp number is required.';
    }

    $status = strtolower(trim((string) ($data['seller_status'] ?? 'online')));
    if (!in_array($status, ['online', 'brb', 'offline'], true)) {
        $errors[] = 'Seller status must be online, brb, or offline.';
    }

    $telegramLink = trim((string) ($data['telegram_link'] ?? ''));
    if ($telegramLink !== '' && filter_var($telegramLink, FILTER_VALIDATE_URL) === false) {
        $errors[] = 'Telegram link must be a valid URL.';
    }

    try {
        $referralDiscount = admin_normalize_referral_discount((string) ($data['referral_discount_percent'] ?? ''));
    } catch (InvalidArgumentException $e) {
        $errors[] = $e->getMessage();
        $referralDiscount = null;
    }

    return [$errors, [
        'store_name' => trim((string) ($data['store_name'] ?? '')),
        'tagline' => trim((string) ($data['tagline'] ?? '')),
        'intro_text' => trim((string) ($data['intro_text'] ?? '')),
        'wa_number' => trim((string) ($data['wa_number'] ?? '')),
        'telegram_link' => $telegramLink,
        'delivery_info' => trim((string) ($data['delivery_info'] ?? '')),
        'status_message' => trim((string) ($data['status_message'] ?? '')),
        'payment_info' => trim((string) ($data['payment_info'] ?? '')),
        'seller_status' => $status,
        'referral_discount_percent' => $referralDiscount,
    ]];
}

function admin_save_seller_config(array $values): void
{
    $pdo = get_db();
    $stmt = $pdo->prepare(
        'INSERT INTO seller_config (`key`, value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE value = VALUES(value), updated_at = CURRENT_TIMESTAMP'
    );

    foreach (SELLER_CONFIG_KEYS as $key) {
        $stmt->execute([$key, $values[$key] ?? null]);
    }
}

function admin_update_status(string $status, string $message): void
{
    admin_save_seller_config([
        'store_name' => get_config('store_name', 'Bloom Corner Kiddies'),
        'tagline' => get_config('tagline', ''),
        'intro_text' => get_config('intro_text', ''),
        'wa_number' => get_config('wa_number', WA_NUMBER),
        'telegram_link' => get_config('telegram_link', TELEGRAM_LINK),
        'delivery_info' => get_config('delivery_info', ''),
        'status_message' => $message,
        'payment_info' => get_config('payment_info', ''),
        'seller_status' => $status,
        'referral_discount_percent' => get_config('referral_discount_percent', ''),
    ]);
}

function admin_env_replace_key(string $envPath, string $key, string $value): void
{
    $content = file_exists($envPath) ? (string) file_get_contents($envPath) : '';
    $replacement = $key . '=' . $value;
    $lines = $content === '' ? [] : preg_split('/\R/', rtrim($content));
    $updatedLines = [];
    $replaced = false;

    foreach ($lines as $line) {
        if (str_starts_with($line, $key . '=')) {
            $updatedLines[] = $replacement;
            $replaced = true;
            continue;
        }

        $updatedLines[] = $line;
    }

    if (!$replaced) {
        $updatedLines[] = $replacement;
    }

    $updated = implode(PHP_EOL, $updatedLines) . PHP_EOL;

    if (file_put_contents($envPath, $updated) === false) {
        throw new RuntimeException('Could not update the environment file.');
    }
}

function admin_change_password(string $currentPassword, string $newPassword, string $confirmPassword, ?string $envPath = null): void
{
    if (!admin_verify_password($currentPassword)) {
        throw new InvalidArgumentException('Current password is incorrect.');
    }

    if ($newPassword === '') {
        throw new InvalidArgumentException('New password is required.');
    }

    if ($newPassword !== $confirmPassword) {
        throw new InvalidArgumentException('New password and confirmation do not match.');
    }

    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    if (!is_string($hash) || $hash === '') {
        throw new RuntimeException('Could not hash the new password.');
    }

    admin_env_replace_key($envPath ?? admin_env_file_path(), 'ADMIN_PASSWORD_HASH', $hash);
}

function admin_generate_referral_code_seed(string $name): string
{
    $clean = strtoupper(preg_replace('/[^A-Za-z]/', '', $name) ?? '');
    $prefix = substr($clean !== '' ? $clean : 'REF', 0, 8);

    return 'BCK-' . $prefix;
}

function admin_generate_referral_code(string $name): string
{
    $pdo = get_db();
    $prefix = admin_generate_referral_code_seed($name);
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM referral_codes WHERE code = ?');

    for ($attempt = 0; $attempt < 25; $attempt++) {
        $candidate = sprintf('%s%03d', $prefix, random_int(0, 999));
        $stmt->execute([$candidate]);
        if ((int) $stmt->fetchColumn() === 0) {
            return $candidate;
        }
    }

    throw new RuntimeException('Could not generate a unique referral code.');
}

function admin_fetch_referral_codes(): array
{
    return get_db()->query(
        'SELECT id, code, referrer_name, referrer_wa, total_referrals, status, created_at
         FROM referral_codes
         ORDER BY total_referrals DESC, created_at DESC, id DESC'
    )->fetchAll();
}

function admin_fetch_referral_code_detail(int $referralId): ?array
{
    $pdo = get_db();
    $stmt = $pdo->prepare(
        'SELECT id, code, referrer_name, referrer_wa, total_referrals, status, created_at
         FROM referral_codes WHERE id = ? LIMIT 1'
    );
    $stmt->execute([$referralId]);
    $referral = $stmt->fetch();
    if (!$referral) {
        return null;
    }

    $historyStmt = $pdo->prepare(
        'SELECT id, new_buyer_name, new_buyer_wa, discount_percent, created_at
         FROM referral_uses WHERE code = ? ORDER BY created_at DESC, id DESC'
    );
    $historyStmt->execute([(string) $referral['code']]);

    return [
        'referral' => $referral,
        'uses' => $historyStmt->fetchAll(),
    ];
}

function admin_create_referral_code(string $referrerName, string $referrerWa): string
{
    $referrerName = trim($referrerName);
    $referrerWa = trim($referrerWa);
    if ($referrerName === '' || $referrerWa === '') {
        throw new InvalidArgumentException('Referrer name and WhatsApp number are required.');
    }

    $code = admin_generate_referral_code($referrerName);
    $stmt = get_db()->prepare(
        'INSERT INTO referral_codes (code, referrer_name, referrer_wa) VALUES (?, ?, ?)'
    );
    $stmt->execute([$code, $referrerName, $referrerWa]);

    return $code;
}

function admin_set_referral_status(int $referralId, string $status): void
{
    if (!in_array($status, ['active', 'inactive'], true)) {
        throw new InvalidArgumentException('Invalid referral status.');
    }

    $stmt = get_db()->prepare('UPDATE referral_codes SET status = ? WHERE id = ?');
    $stmt->execute([$status, $referralId]);
}

function admin_record_referral_redemption(int $referralId, string $buyerName, string $buyerWa): void
{
    $buyerName = trim($buyerName);
    $buyerWa = trim($buyerWa);
    if ($buyerName === '' || $buyerWa === '') {
        throw new InvalidArgumentException('Buyer name and WhatsApp number are required.');
    }

    $pdo = get_db();
    $detail = admin_fetch_referral_code_detail($referralId);
    if ($detail === null) {
        throw new RuntimeException('Referral code not found.');
    }

    $discount = admin_normalize_referral_discount(get_config('referral_discount_percent', ''));
    $snapshot = $discount === null ? 0 : (int) $discount;

    $pdo->beginTransaction();
    try {
        $pdo->prepare(
            'INSERT INTO referral_uses (code, new_buyer_name, new_buyer_wa, discount_percent) VALUES (?, ?, ?, ?)'
        )->execute([
            (string) $detail['referral']['code'],
            $buyerName,
            $buyerWa,
            $snapshot,
        ]);

        $pdo->prepare(
            'UPDATE referral_codes SET total_referrals = total_referrals + 1 WHERE id = ?'
        )->execute([$referralId]);

        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}
