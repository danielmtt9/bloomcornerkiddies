<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/products.php';

admin_require_auth();
$categories = admin_fetch_categories();
$form = admin_product_defaults();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = admin_product_form_data($_POST);
    try {
        $productId = admin_save_product($form, admin_uploaded_files($_FILES['images'] ?? []));
        app_flash_set('success', 'Product added! ✅');
        header('Location: /admin/edit.php?id=' . $productId, true, 302);
        exit;
    } catch (InvalidArgumentException $e) {
        $errors = array_filter(explode("\n", $e->getMessage()));
    } catch (Throwable $e) {
        $errors = ['Product could not be saved.'];
    }
}

admin_page_start('Add Product', 'products');
require __DIR__ . '/partials/product-form.php';
admin_page_end();
