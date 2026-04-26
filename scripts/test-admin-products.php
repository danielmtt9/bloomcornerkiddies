<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/public_html/includes/products.php';

$form = admin_product_form_data([
    'name' => 'Party Dress',
    'gender' => 'girls',
    'price' => '12000',
    'original_price' => '15000',
    'categories' => ['4', '7'],
    'sizes' => [
        ['size_label' => '3-4Y', 'stock_qty' => '2', 'is_sold_out' => '0'],
        ['size_label' => '4-5Y', 'stock_qty' => '0', 'is_sold_out' => '1'],
    ],
]);

if (count($form['categories']) !== 2 || count($form['sizes']) !== 2) {
    fwrite(STDERR, "Expected normalized categories and sizes.\n");
    exit(1);
}

[$errors, $normalizedSizes] = admin_validate_product_form(
    $form,
    [
        ['id' => 4, 'name' => 'Girls'],
        ['id' => 7, 'name' => 'Occasions'],
    ],
    1,
    0
);

if ($errors !== []) {
    fwrite(STDERR, "Expected valid product form payload.\n");
    exit(1);
}

if ($normalizedSizes[1]['is_sold_out'] !== true) {
    fwrite(STDERR, "Expected zero-stock size to normalize as sold out.\n");
    exit(1);
}

$processingMode = admin_image_processing_mode();
if (!in_array($processingMode, ['off', 'auto', 'required'], true)) {
    fwrite(STDERR, "Expected valid image processing mode.\n");
    exit(1);
}

$gdSupport = admin_gd_processing_supported();
if (!is_bool($gdSupport)) {
    fwrite(STDERR, "Expected GD support probe to return bool.\n");
    exit(1);
}

ob_start();
admin_page_start('Product Render', 'products');
echo '<div class="actions"><a class="primary" href="/admin/add.php">Add New Product</a></div>';
admin_page_end();
$html = ob_get_clean();

foreach (['Products', 'Referrals', 'Settings', 'Logout', 'Add New Product'] as $needle) {
    if (strpos($html, $needle) === false) {
        fwrite(STDERR, "Expected render output to include: {$needle}\n");
        exit(1);
    }
}

echo "PASS: admin products PHP smoke test\n";
