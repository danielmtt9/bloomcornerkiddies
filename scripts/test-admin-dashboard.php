<?php

declare(strict_types=1);

$_SERVER['HTTPS'] = 'on';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'example.test';
$_SERVER['REQUEST_URI'] = '/admin/index.php';

require_once dirname(__DIR__) . '/public_html/includes/admin.php';

try {
    ob_start();
    admin_page_start('Dashboard Smoke', 'products');
    admin_page_end();
    $shellHtml = ob_get_clean();

    foreach (['Products', 'Referrals', 'Settings', 'Logout'] as $label) {
        if (strpos($shellHtml, $label) === false) {
            throw new RuntimeException('Expected admin shell to include nav label: ' . $label);
        }
    }

    if (strpos($shellHtml, 'overflow-x: hidden') === false) {
        throw new RuntimeException('Expected admin shell CSS to guard against horizontal scrolling.');
    }
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}

echo "PASS: admin dashboard smoke test\n";
