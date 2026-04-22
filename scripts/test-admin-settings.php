<?php

declare(strict_types=1);

putenv('ADMIN_PASSWORD_HASH=' . password_hash('current-secret', PASSWORD_BCRYPT));
$_ENV['ADMIN_PASSWORD_HASH'] = getenv('ADMIN_PASSWORD_HASH');

require_once dirname(__DIR__) . '/public_html/includes/settings.php';

[$errors, $normalized] = admin_validate_settings_payload([
    'store_name' => 'Bloom Corner Kiddies',
    'tagline' => 'Placeholder',
    'intro_text' => 'Hello',
    'wa_number' => '2349049308656',
    'telegram_link' => 'https://t.me/placeholder',
    'delivery_info' => 'Delivery info',
    'status_message' => 'Replies fast',
    'payment_info' => 'Transfer only',
    'seller_status' => 'brb',
    'referral_discount_percent' => '',
]);

if ($errors !== [] || $normalized['referral_discount_percent'] !== null || $normalized['seller_status'] !== 'brb') {
    fwrite(STDERR, "Expected normalized settings payload.\n");
    exit(1);
}

if (admin_generate_referral_code_seed('Sarah O.') !== 'BCK-SARAHO') {
    fwrite(STDERR, "Expected referral seed generation.\n");
    exit(1);
}

$tempEnv = tempnam(sys_get_temp_dir(), 'bck-env-');
file_put_contents($tempEnv, "APP_ENV=production\nADMIN_PASSWORD_HASH=" . $_ENV['ADMIN_PASSWORD_HASH'] . "\n");
admin_change_password('current-secret', 'new-secret', 'new-secret', $tempEnv);
$updatedEnv = (string) file_get_contents($tempEnv);
@unlink($tempEnv);

if (strpos($updatedEnv, 'ADMIN_PASSWORD_HASH=$2') === false) {
    fwrite(STDERR, "Expected hashed password to be written to env file.\n");
    exit(1);
}

ob_start();
admin_page_start('Settings Smoke', 'settings');
echo '<div class="actions"><a href="/admin/referrals.php">Referrals</a></div>';
admin_page_end();
$html = ob_get_clean();

foreach (['Products', 'Referrals', 'Settings', 'Logout'] as $needle) {
    if (strpos($html, $needle) === false) {
        fwrite(STDERR, "Expected admin shell to include {$needle}.\n");
        exit(1);
    }
}

echo "PASS: admin settings PHP smoke test\n";
