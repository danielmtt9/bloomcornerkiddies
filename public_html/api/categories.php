<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/storefront.php';

try {
    app_json(storefront_categories_payload());
} catch (Throwable $e) {
    app_json(['error' => 'Could not load categories.'], 500);
}
