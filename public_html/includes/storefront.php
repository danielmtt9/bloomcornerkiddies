<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function storefront_base_url(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');

    return $scheme . '://' . $host;
}

function storefront_product_rows(?string $categorySlug = null): array
{
    $pdo = get_db();
    $params = [];
    $categoryJoin = '';
    $categoryWhere = '';

    if ($categorySlug !== null && $categorySlug !== '') {
        $categoryJoin = 'INNER JOIN product_categories filter_pc ON filter_pc.product_id = p.id
                         INNER JOIN categories filter_c ON filter_c.id = filter_pc.category_id';
        $categoryWhere = ' AND filter_c.slug = ?';
        $params[] = $categorySlug;
    }

    $sql = <<<SQL
SELECT
    p.id,
    p.name,
    p.price,
    p.original_price,
    p.brand,
    p.gender,
    p.description,
    p.material,
    p.season_tag,
    p.tiktok_url,
    COALESCE(primary_image.file_path, '') AS primary_image_path,
    COALESCE(image_rows.images_json, '[]') AS images_json,
    COALESCE(size_rows.sizes_json, '[]') AS sizes_json,
    COALESCE(category_rows.categories_json, '[]') AS categories_json,
    COALESCE(stock_rows.has_in_stock, 0) AS has_in_stock,
    COALESCE(stock_rows.has_sizes, 0) AS has_sizes,
    p.updated_at
FROM products p
{$categoryJoin}
LEFT JOIN (
    SELECT pi.product_id, pi.file_path
    FROM product_images pi
    INNER JOIN (
        SELECT product_id, MIN(sort_order) AS min_sort_order
        FROM product_images
        GROUP BY product_id
    ) mins ON mins.product_id = pi.product_id AND mins.min_sort_order = pi.sort_order
) primary_image ON primary_image.product_id = p.id
LEFT JOIN (
    SELECT
        pi.product_id,
        CONCAT(
            '[',
            GROUP_CONCAT(
                JSON_OBJECT(
                    'file_path', pi.file_path,
                    'sort_order', pi.sort_order
                )
                ORDER BY pi.sort_order, pi.id SEPARATOR ','
            ),
            ']'
        ) AS images_json
    FROM product_images pi
    GROUP BY pi.product_id
) image_rows ON image_rows.product_id = p.id
LEFT JOIN (
    SELECT
        ps.product_id,
        CONCAT(
            '[',
            GROUP_CONCAT(
                JSON_OBJECT(
                    'size_label', ps.size_label,
                    'stock_qty', ps.stock_qty,
                    'is_sold_out', IF(ps.is_sold_out = 1 OR ps.stock_qty = 0, true, false)
                )
                ORDER BY ps.id SEPARATOR ','
            ),
            ']'
        ) AS sizes_json
    FROM product_sizes ps
    GROUP BY ps.product_id
) size_rows ON size_rows.product_id = p.id
LEFT JOIN (
    SELECT
        pc.product_id,
        CONCAT(
            '[',
            GROUP_CONCAT(
                JSON_OBJECT(
                    'id', c.id,
                    'name', c.name,
                    'slug', c.slug
                )
                ORDER BY c.sort_order SEPARATOR ','
            ),
            ']'
        ) AS categories_json
    FROM product_categories pc
    INNER JOIN categories c ON c.id = pc.category_id
    GROUP BY pc.product_id
) category_rows ON category_rows.product_id = p.id
LEFT JOIN (
    SELECT
        ps.product_id,
        MAX(CASE WHEN ps.stock_qty > 0 AND ps.is_sold_out = 0 THEN 1 ELSE 0 END) AS has_in_stock,
        MAX(1) AS has_sizes
    FROM product_sizes ps
    GROUP BY ps.product_id
) stock_rows ON stock_rows.product_id = p.id
WHERE p.is_available = 1{$categoryWhere}
GROUP BY
    p.id,
    p.name,
    p.price,
    p.original_price,
    p.brand,
    p.gender,
    p.description,
    p.material,
    p.season_tag,
    p.tiktok_url,
    primary_image.file_path,
    image_rows.images_json,
    size_rows.sizes_json,
    category_rows.categories_json,
    stock_rows.has_in_stock,
    stock_rows.has_sizes,
    p.updated_at
ORDER BY p.updated_at DESC, p.id DESC
SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function storefront_products_payload(?string $categorySlug = null): array
{
    $products = [];
    foreach (storefront_product_rows($categorySlug) as $row) {
        $images = json_decode((string) $row['images_json'], true, 512, JSON_THROW_ON_ERROR);
        $sizes = json_decode((string) $row['sizes_json'], true, 512, JSON_THROW_ON_ERROR);
        $categories = json_decode((string) $row['categories_json'], true, 512, JSON_THROW_ON_ERROR);
        $soldOut = ((int) $row['has_sizes'] === 1) && ((int) $row['has_in_stock'] === 0);
        $imageUrls = [];

        foreach ($images as $image) {
            $filePath = (string) ($image['file_path'] ?? '');
            if ($filePath === '') {
                continue;
            }

            $imageUrls[] = '/' . ltrim($filePath, '/');
        }

        $products[] = [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'price' => (int) $row['price'],
            'original_price' => $row['original_price'] === null ? null : (int) $row['original_price'],
            'brand' => $row['brand'] === null ? null : (string) $row['brand'],
            'gender' => (string) $row['gender'],
            'description' => $row['description'] === null ? null : (string) $row['description'],
            'material' => $row['material'] === null ? null : (string) $row['material'],
            'season_tag' => $row['season_tag'] === null ? null : (string) $row['season_tag'],
            'tiktok_url' => $row['tiktok_url'] === null ? null : (string) $row['tiktok_url'],
            'primary_image_url' => (string) $row['primary_image_path'] !== '' ? '/' . ltrim((string) $row['primary_image_path'], '/') : '',
            'image_urls' => $imageUrls,
            'sizes' => $sizes,
            'categories' => $categories,
            'badges' => [
                'on_sale' => $row['original_price'] !== null,
                'sold_out' => $soldOut,
            ],
        ];
    }

    return ['products' => $products, 'total' => count($products)];
}

function storefront_categories_payload(): array
{
    $rows = get_db()->query(
        'SELECT c.id, c.name, c.slug, c.sort_order, COUNT(DISTINCT pc.product_id) AS product_count
         FROM categories c
         LEFT JOIN product_categories pc ON pc.category_id = c.id
         LEFT JOIN products p ON p.id = pc.product_id AND p.is_available = 1
         GROUP BY c.id, c.name, c.slug, c.sort_order
         ORDER BY c.sort_order ASC, c.name ASC'
    )->fetchAll();

    $categories = [];
    foreach ($rows as $row) {
        $categories[] = [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'slug' => (string) $row['slug'],
            'sort_order' => (int) $row['sort_order'],
            'product_count' => (int) $row['product_count'],
        ];
    }

    return ['categories' => $categories];
}

function storefront_status_payload(): array
{
    return [
        'status' => get_config('seller_status', 'online'),
        'message' => get_config('status_message', 'Replies as soon as possible.'),
        'wa_number' => get_config('wa_number', WA_NUMBER),
        'telegram_link' => get_config('telegram_link', TELEGRAM_LINK),
        'delivery_info' => get_config('delivery_info', 'Delivery details are confirmed in chat before dispatch.'),
        'store_name' => get_config('store_name', 'Bloom Corner Kiddies'),
        'tagline' => get_config('tagline', 'Beautiful kids wear, ready for your next message.'),
        'intro_text' => get_config('intro_text', 'Browse the catalogue and message us when you find something you love.'),
        'instagram_link' => get_config('instagram_link', ''),
        'facebook_link' => get_config('facebook_link', ''),
        'tiktok_link' => get_config('tiktok_link', ''),
        'store_address' => get_config('store_address', ''),
        'logo_url' => get_config('logo_url', ''),
    ];
}
