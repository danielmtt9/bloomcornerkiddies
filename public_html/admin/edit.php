<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/products.php';

admin_require_auth();
$productId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$state = $productId > 0 ? admin_fetch_product_form_state($productId) : null;

if ($state === null) {
    app_flash_set('error', 'Product not found.');
    header('Location: /admin/products.php', true, 302);
    exit;
}

$categories = admin_fetch_categories();
$errors = [];
$form = [
    'name' => $state['name'],
    'gender' => $state['gender'],
    'price' => $state['price'],
    'original_price' => $state['original_price'],
    'description' => $state['description'],
    'brand' => $state['brand'],
    'material' => $state['material'],
    'season_tag' => $state['season_tag'],
    'tiktok_url' => $state['tiktok_url'],
    'is_available' => $state['is_available'],
    'categories' => $state['categories'],
    'sizes' => $state['sizes'] === [] ? admin_product_defaults()['sizes'] : $state['sizes'],
    'remove_image_ids' => [],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = admin_product_form_data($_POST);
    try {
        admin_save_product($form, admin_uploaded_files($_FILES['images'] ?? []), $productId);
        app_flash_set('success', 'Product updated! ✅');
        header('Location: /admin/edit.php?id=' . $productId, true, 302);
        exit;
    } catch (InvalidArgumentException $e) {
        $errors = array_filter(explode("\n", $e->getMessage()));
    } catch (Throwable $e) {
        $errors = ['Product could not be updated.'];
    }
}

admin_page_start('Edit Product', 'products');
require __DIR__ . '/partials/product-form.php';
admin_page_end();
