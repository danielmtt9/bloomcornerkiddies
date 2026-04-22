<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/storefront.php';

$category = trim((string) ($_GET['category'] ?? ''));

try {
    app_json(storefront_products_payload($category !== '' ? $category : null));
} catch (Throwable $e) {
    app_json(['error' => 'Could not load products.'], 500);
}
