<?php

declare(strict_types=1);

require_once __DIR__ . '/admin.php';

const PRODUCT_AGE_SIZES = ['0-3M', '3-6M', '6-12M', '1Y', '2Y', '3-4Y', '4-5Y', '5-6Y', '6-7Y', '7-8Y', '8-9Y', '9-10Y', '10-12Y'];
const PRODUCT_NUMBER_SIZES = ['2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14'];
const PRODUCT_ALLOWED_MIME_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];
const PRODUCT_MAX_IMAGE_BYTES = 5242880;
const PRODUCT_IMAGE_TARGET_RATIO_WIDTH = 4;
const PRODUCT_IMAGE_TARGET_RATIO_HEIGHT = 5;

function admin_product_defaults(): array
{
    return [
        'name' => '',
        'gender' => 'unisex',
        'price' => '',
        'original_price' => '',
        'description' => '',
        'brand' => '',
        'material' => '',
        'season_tag' => '',
        'tiktok_url' => '',
        'is_available' => true,
        'categories' => [],
        'sizes' => [
            ['size_label' => '', 'stock_qty' => '1', 'is_sold_out' => false],
        ],
        'remove_image_ids' => [],
    ];
}

function admin_normalize_bool(mixed $value): bool
{
    return in_array($value, ['1', 1, true, 'on', 'yes'], true);
}

function admin_normalize_sizes(array $rawSizes): array
{
    $sizes = [];

    foreach ($rawSizes as $row) {
        $label = trim((string) ($row['size_label'] ?? ''));
        $stock = trim((string) ($row['stock_qty'] ?? '0'));
        $isSoldOut = admin_normalize_bool($row['is_sold_out'] ?? false);

        if ($label === '' && $stock === '' && !$isSoldOut) {
            continue;
        }

        $sizes[] = [
            'size_label' => $label,
            'stock_qty' => $stock === '' ? '0' : $stock,
            'is_sold_out' => $isSoldOut,
        ];
    }

    if ($sizes === []) {
        $sizes[] = ['size_label' => '', 'stock_qty' => '1', 'is_sold_out' => false];
    }

    return $sizes;
}

function admin_product_form_data(array $source): array
{
    $data = admin_product_defaults();
    $data['name'] = trim((string) ($source['name'] ?? ''));
    $data['gender'] = trim((string) ($source['gender'] ?? 'unisex'));
    $data['price'] = trim((string) ($source['price'] ?? ''));
    $data['original_price'] = trim((string) ($source['original_price'] ?? ''));
    $data['description'] = trim((string) ($source['description'] ?? ''));
    $data['brand'] = trim((string) ($source['brand'] ?? ''));
    $data['material'] = trim((string) ($source['material'] ?? ''));
    $data['season_tag'] = trim((string) ($source['season_tag'] ?? ''));
    $data['tiktok_url'] = trim((string) ($source['tiktok_url'] ?? ''));
    $data['is_available'] = !isset($source['is_available']) || admin_normalize_bool($source['is_available']);
    $data['categories'] = array_values(array_filter(array_map('intval', (array) ($source['categories'] ?? []))));
    $data['sizes'] = admin_normalize_sizes((array) ($source['sizes'] ?? []));
    $data['remove_image_ids'] = array_values(array_filter(array_map('intval', (array) ($source['remove_image_ids'] ?? []))));

    return $data;
}

function admin_fetch_categories(): array
{
    return get_db()->query('SELECT id, name, slug FROM categories ORDER BY sort_order ASC, name ASC')->fetchAll();
}

function admin_fetch_product_list(): array
{
    $sql = <<<SQL
SELECT
    p.id,
    p.name,
    p.price,
    p.gender,
    p.is_available,
    p.updated_at,
    COALESCE(primary_image.file_path, '') AS primary_image,
    COALESCE(category_names.categories, '') AS categories,
    COALESCE(size_rows.size_summary, '') AS size_summary
FROM products p
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
        pc.product_id,
        GROUP_CONCAT(DISTINCT c.name ORDER BY c.sort_order SEPARATOR ', ') AS categories
    FROM product_categories pc
    INNER JOIN categories c ON c.id = pc.category_id
    GROUP BY pc.product_id
) category_names ON category_names.product_id = p.id
LEFT JOIN (
    SELECT
        ps.product_id,
        GROUP_CONCAT(
            CONCAT(ps.size_label, ': ', ps.stock_qty, IF(ps.is_sold_out = 1 OR ps.stock_qty = 0, ' sold out', ''))
            ORDER BY ps.id SEPARATOR ', '
        ) AS size_summary
    FROM product_sizes ps
    GROUP BY ps.product_id
) size_rows ON size_rows.product_id = p.id
ORDER BY p.updated_at DESC, p.id DESC
SQL;

    return get_db()->query($sql)->fetchAll();
}

function admin_fetch_product(int $productId): ?array
{
    $stmt = get_db()->prepare(
        'SELECT id, name, gender, price, original_price, description, brand, material, season_tag, tiktok_url, is_available
         FROM products WHERE id = ? LIMIT 1'
    );
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    return $product ?: null;
}

function admin_fetch_product_form_state(int $productId): ?array
{
    $product = admin_fetch_product($productId);
    if ($product === null) {
        return null;
    }

    $categoryStmt = get_db()->prepare('SELECT category_id FROM product_categories WHERE product_id = ? ORDER BY category_id ASC');
    $categoryStmt->execute([$productId]);

    $sizeStmt = get_db()->prepare('SELECT id, size_label, stock_qty, is_sold_out FROM product_sizes WHERE product_id = ? ORDER BY id ASC');
    $sizeStmt->execute([$productId]);

    $imageStmt = get_db()->prepare('SELECT id, file_path, sort_order FROM product_images WHERE product_id = ? ORDER BY sort_order ASC, id ASC');
    $imageStmt->execute([$productId]);

    return [
        'id' => (int) $product['id'],
        'name' => (string) $product['name'],
        'gender' => (string) $product['gender'],
        'price' => (string) $product['price'],
        'original_price' => $product['original_price'] === null ? '' : (string) $product['original_price'],
        'description' => (string) ($product['description'] ?? ''),
        'brand' => (string) ($product['brand'] ?? ''),
        'material' => (string) ($product['material'] ?? ''),
        'season_tag' => (string) ($product['season_tag'] ?? ''),
        'tiktok_url' => (string) ($product['tiktok_url'] ?? ''),
        'is_available' => (int) $product['is_available'] === 1,
        'categories' => array_map(static fn(array $row): int => (int) $row['category_id'], $categoryStmt->fetchAll()),
        'sizes' => array_map(
            static fn(array $row): array => [
                'id' => (int) $row['id'],
                'size_label' => (string) $row['size_label'],
                'stock_qty' => (string) $row['stock_qty'],
                'is_sold_out' => (int) $row['is_sold_out'] === 1,
            ],
            $sizeStmt->fetchAll()
        ),
        'images' => array_map(
            static fn(array $row): array => [
                'id' => (int) $row['id'],
                'file_path' => (string) $row['file_path'],
                'sort_order' => (int) $row['sort_order'],
            ],
            $imageStmt->fetchAll()
        ),
    ];
}

function admin_validate_product_form(array $data, array $categories, int $existingImageCount = 0, int $newImageCount = 0): array
{
    $errors = [];
    $validCategoryIds = array_map(static fn(array $row): int => (int) $row['id'], $categories);

    if ($data['name'] === '') {
        $errors[] = 'Product name is required.';
    }

    if (!in_array($data['gender'], ['girls', 'boys', 'unisex', 'babies'], true)) {
        $errors[] = 'Select a valid gender.';
    }

    if ($data['price'] === '' || !ctype_digit($data['price']) || (int) $data['price'] < 1) {
        $errors[] = 'Price must be a positive whole number.';
    }

    if ($data['original_price'] !== '' && (!ctype_digit($data['original_price']) || (int) $data['original_price'] < 1)) {
        $errors[] = 'Original price must be blank or a positive whole number.';
    }

    if ($data['original_price'] !== '' && $data['price'] !== '' && ctype_digit($data['price']) && ctype_digit($data['original_price']) && (int) $data['original_price'] <= (int) $data['price']) {
        $errors[] = 'Original price must be higher than the current price when provided.';
    }

    if ($data['categories'] === []) {
        $errors[] = 'Select at least one category.';
    } elseif (array_diff($data['categories'], $validCategoryIds) !== []) {
        $errors[] = 'One or more selected categories are invalid.';
    }

    $normalizedSizes = [];
    $seenLabels = [];
    foreach ($data['sizes'] as $row) {
        $label = trim((string) ($row['size_label'] ?? ''));
        $stock = trim((string) ($row['stock_qty'] ?? '0'));
        $isSoldOut = admin_normalize_bool($row['is_sold_out'] ?? false);

        if ($label === '') {
            $errors[] = 'Every size row needs a size label.';
            continue;
        }

        if (isset($seenLabels[strtolower($label)])) {
            $errors[] = 'Size labels must be unique per product.';
            continue;
        }

        if ($stock === '' || !ctype_digit($stock)) {
            $errors[] = 'Every size row needs a whole-number stock value.';
            continue;
        }

        $seenLabels[strtolower($label)] = true;
        $normalizedSizes[] = [
            'size_label' => $label,
            'stock_qty' => (int) $stock,
            'is_sold_out' => $isSoldOut || (int) $stock === 0,
        ];
    }

    if ($normalizedSizes === []) {
        $errors[] = 'Add at least one size row.';
    }

    if ($data['tiktok_url'] !== '' && filter_var($data['tiktok_url'], FILTER_VALIDATE_URL) === false) {
        $errors[] = 'TikTok URL must be a valid URL.';
    }

    if (($existingImageCount + $newImageCount) < 1) {
        $errors[] = 'Upload at least one product photo.';
    }

    return [$errors, $normalizedSizes];
}

function admin_uploaded_files(array $fileInput): array
{
    if (!isset($fileInput['name']) || !is_array($fileInput['name'])) {
        return [];
    }

    $files = [];
    foreach ($fileInput['name'] as $index => $name) {
        $error = (int) ($fileInput['error'][$index] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        $files[] = [
            'name' => (string) $name,
            'type' => (string) ($fileInput['type'][$index] ?? ''),
            'tmp_name' => (string) ($fileInput['tmp_name'][$index] ?? ''),
            'error' => $error,
            'size' => (int) ($fileInput['size'][$index] ?? 0),
        ];
    }

    return $files;
}

function admin_validate_uploaded_images(array $files): array
{
    $errors = [];
    $validated = [];
    $finfo = new finfo(FILEINFO_MIME_TYPE);

    foreach ($files as $file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'One or more uploaded images failed to transfer.';
            continue;
        }

        if ($file['size'] > PRODUCT_MAX_IMAGE_BYTES) {
            $errors[] = sprintf('%s exceeds the 5MB image limit.', $file['name']);
            continue;
        }

        $mime = $finfo->file($file['tmp_name']) ?: '';
        if (!array_key_exists($mime, PRODUCT_ALLOWED_MIME_TYPES)) {
            $errors[] = sprintf('%s must be JPEG, PNG, or WebP.', $file['name']);
            continue;
        }

        $validated[] = $file + ['mime' => $mime, 'extension' => PRODUCT_ALLOWED_MIME_TYPES[$mime]];
    }

    return [$errors, $validated];
}

function admin_uploads_root(): string
{
    return dirname(__DIR__) . '/uploads/products';
}

function admin_relative_upload_path(int $productId, string $filename): string
{
    return 'uploads/products/' . $productId . '/' . $filename;
}

function admin_absolute_upload_path(string $relativePath): string
{
    return dirname(__DIR__) . '/' . ltrim($relativePath, '/');
}

function admin_ensure_directory(string $path): void
{
    if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
        throw new RuntimeException('Could not create image upload directory.');
    }
}

function admin_move_uploaded_file_compat(string $tmpName, string $destination): bool
{
    if (PHP_SAPI === 'cli') {
        return rename($tmpName, $destination);
    }

    return move_uploaded_file($tmpName, $destination);
}

function admin_store_product_images(int $productId, array $files, int $startingSortOrder): array
{
    $stored = [];
    $productDir = admin_uploads_root() . '/' . $productId;
    admin_ensure_directory($productDir);

    foreach ($files as $index => $file) {
        $safeBase = preg_replace('/[^a-z0-9]+/i', '-', pathinfo($file['name'], PATHINFO_FILENAME)) ?: 'image';
        $filename = sprintf('%s-%s-%d.%s', trim($safeBase, '-'), date('YmdHis'), $index, $file['extension']);
        $absolutePath = $productDir . '/' . $filename;
        $relativePath = admin_relative_upload_path($productId, $filename);

        if (!admin_move_uploaded_file_compat($file['tmp_name'], $absolutePath)) {
            throw new RuntimeException('Could not persist uploaded image file.');
        }

        admin_process_uploaded_image_if_enabled($absolutePath, $file['mime']);

        $stored[] = [
            'file_path' => $relativePath,
            'sort_order' => $startingSortOrder + $index,
            'absolute_path' => $absolutePath,
        ];
    }

    return $stored;
}

function admin_image_processing_mode(): string
{
    $mode = strtolower(trim(env_value('PRODUCT_IMAGE_PROCESSING_MODE', 'off')));
    if (in_array($mode, ['off', 'auto', 'required'], true)) {
        return $mode;
    }
    return 'off';
}

function admin_gd_processing_supported(): bool
{
    return function_exists('imagecreatetruecolor')
        && function_exists('imagecopyresampled')
        && function_exists('imagejpeg')
        && function_exists('imagepng')
        && function_exists('imagewebp')
        && function_exists('imagealphablending')
        && function_exists('imagesavealpha')
        && function_exists('imagedestroy')
        && function_exists('getimagesize');
}

function admin_process_uploaded_image_if_enabled(string $absolutePath, string $mime): void
{
    $mode = admin_image_processing_mode();
    if ($mode === 'off') {
        return;
    }

    if (!admin_gd_processing_supported()) {
        if ($mode === 'required') {
            throw new RuntimeException('Image processing is required but GD is not available.');
        }
        return;
    }

    admin_reframe_to_four_by_five($absolutePath, $mime);
}

function admin_reframe_to_four_by_five(string $absolutePath, string $mime): void
{
    [$width, $height] = getimagesize($absolutePath) ?: [0, 0];
    if ($width <= 0 || $height <= 0) {
        throw new RuntimeException('Could not read uploaded image dimensions.');
    }

    $source = match ($mime) {
        'image/jpeg' => imagecreatefromjpeg($absolutePath),
        'image/png' => imagecreatefrompng($absolutePath),
        'image/webp' => imagecreatefromwebp($absolutePath),
        default => false,
    };

    if ($source === false) {
        throw new RuntimeException('Could not decode uploaded image.');
    }

    $targetRatio = PRODUCT_IMAGE_TARGET_RATIO_WIDTH / PRODUCT_IMAGE_TARGET_RATIO_HEIGHT;
    $currentRatio = $width / $height;

    $cropWidth = $width;
    $cropHeight = $height;
    $srcX = 0;
    $srcY = 0;

    if ($currentRatio > $targetRatio) {
        $cropWidth = (int) round($height * $targetRatio);
        $srcX = (int) floor(($width - $cropWidth) / 2);
    } else {
        $cropHeight = (int) round($width / $targetRatio);
        $srcY = (int) floor(($height - $cropHeight) / 2);
    }

    $targetWidth = $cropWidth;
    $targetHeight = (int) round($targetWidth / $targetRatio);

    $target = imagecreatetruecolor($targetWidth, $targetHeight);
    if ($target === false) {
        imagedestroy($source);
        throw new RuntimeException('Could not create image canvas for processing.');
    }

    if ($mime === 'image/png' || $mime === 'image/webp') {
        imagealphablending($target, false);
        imagesavealpha($target, true);
    }

    $copied = imagecopyresampled(
        $target,
        $source,
        0,
        0,
        $srcX,
        $srcY,
        $targetWidth,
        $targetHeight,
        $cropWidth,
        $cropHeight
    );

    if ($copied === false) {
        imagedestroy($source);
        imagedestroy($target);
        throw new RuntimeException('Could not process uploaded image.');
    }

    $written = match ($mime) {
        'image/jpeg' => imagejpeg($target, $absolutePath, 90),
        'image/png' => imagepng($target, $absolutePath, 6),
        'image/webp' => imagewebp($target, $absolutePath, 85),
        default => false,
    };

    imagedestroy($source);
    imagedestroy($target);

    if ($written === false) {
        throw new RuntimeException('Could not save processed uploaded image.');
    }
}

function admin_delete_files(array $paths): void
{
    foreach ($paths as $path) {
        if (is_file($path)) {
            @unlink($path);
        }
    }
}

function admin_sync_product_categories(PDO $pdo, int $productId, array $categoryIds): void
{
    $pdo->prepare('DELETE FROM product_categories WHERE product_id = ?')->execute([$productId]);
    $stmt = $pdo->prepare('INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)');

    foreach ($categoryIds as $categoryId) {
        $stmt->execute([$productId, $categoryId]);
    }
}

function admin_sync_product_sizes(PDO $pdo, int $productId, array $sizes): void
{
    $pdo->prepare('DELETE FROM product_sizes WHERE product_id = ?')->execute([$productId]);
    $stmt = $pdo->prepare('INSERT INTO product_sizes (product_id, size_label, stock_qty, is_sold_out) VALUES (?, ?, ?, ?)');

    foreach ($sizes as $size) {
        $stmt->execute([$productId, $size['size_label'], $size['stock_qty'], $size['is_sold_out'] ? 1 : 0]);
    }
}

function admin_fetch_product_images_for_ids(int $productId, array $imageIds): array
{
    if ($imageIds === []) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($imageIds), '?'));
    $stmt = get_db()->prepare("SELECT id, file_path FROM product_images WHERE product_id = ? AND id IN ($placeholders)");
    $stmt->execute(array_merge([$productId], $imageIds));

    return $stmt->fetchAll();
}

function admin_save_product(array $data, array $files, ?int $productId = null): int
{
    $pdo = get_db();
    $categories = admin_fetch_categories();
    $existingImageCount = $productId === null ? 0 : count(admin_fetch_product_form_state($productId)['images'] ?? []);
    $remainingImageCount = max(0, $existingImageCount - count($data['remove_image_ids']));
    [$uploadErrors, $validatedFiles] = admin_validate_uploaded_images($files);
    [$validationErrors, $normalizedSizes] = admin_validate_product_form($data, $categories, $remainingImageCount, count($validatedFiles));

    $errors = array_merge($uploadErrors, $validationErrors);
    if ($errors !== []) {
        throw new InvalidArgumentException(implode("\n", $errors));
    }

    $movedFiles = [];
    $removedImages = [];

    try {
        $pdo->beginTransaction();

        if ($productId === null) {
            $stmt = $pdo->prepare(
                'INSERT INTO products (name, gender, price, original_price, description, brand, material, season_tag, tiktok_url, is_available)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $data['name'],
                $data['gender'],
                (int) $data['price'],
                $data['original_price'] === '' ? null : (int) $data['original_price'],
                $data['description'] === '' ? null : $data['description'],
                $data['brand'] === '' ? null : $data['brand'],
                $data['material'] === '' ? null : $data['material'],
                $data['season_tag'] === '' ? null : $data['season_tag'],
                $data['tiktok_url'] === '' ? null : $data['tiktok_url'],
                $data['is_available'] ? 1 : 0,
            ]);
            $productId = (int) $pdo->lastInsertId();
        } else {
            $stmt = $pdo->prepare(
                'UPDATE products
                 SET name = ?, gender = ?, price = ?, original_price = ?, description = ?, brand = ?, material = ?, season_tag = ?, tiktok_url = ?, is_available = ?, updated_at = CURRENT_TIMESTAMP
                 WHERE id = ?'
            );
            $stmt->execute([
                $data['name'],
                $data['gender'],
                (int) $data['price'],
                $data['original_price'] === '' ? null : (int) $data['original_price'],
                $data['description'] === '' ? null : $data['description'],
                $data['brand'] === '' ? null : $data['brand'],
                $data['material'] === '' ? null : $data['material'],
                $data['season_tag'] === '' ? null : $data['season_tag'],
                $data['tiktok_url'] === '' ? null : $data['tiktok_url'],
                $data['is_available'] ? 1 : 0,
                $productId,
            ]);
        }

        admin_sync_product_categories($pdo, $productId, $data['categories']);
        admin_sync_product_sizes($pdo, $productId, $normalizedSizes);

        if ($data['remove_image_ids'] !== []) {
            $removedImages = admin_fetch_product_images_for_ids($productId, $data['remove_image_ids']);
            $placeholders = implode(',', array_fill(0, count($data['remove_image_ids']), '?'));
            $pdo->prepare("DELETE FROM product_images WHERE product_id = ? AND id IN ($placeholders)")
                ->execute(array_merge([$productId], $data['remove_image_ids']));
        }

        $sortStmt = $pdo->prepare('SELECT COALESCE(MAX(sort_order), -1) FROM product_images WHERE product_id = ?');
        $sortStmt->execute([$productId]);
        $startingSortOrder = ((int) $sortStmt->fetchColumn()) + 1;
        $storedImages = admin_store_product_images($productId, $validatedFiles, $startingSortOrder);
        $imageStmt = $pdo->prepare('INSERT INTO product_images (product_id, file_path, sort_order) VALUES (?, ?, ?)');

        foreach ($storedImages as $image) {
            $imageStmt->execute([$productId, $image['file_path'], $image['sort_order']]);
            $movedFiles[] = $image['absolute_path'];
        }

        $pdo->commit();

        foreach ($removedImages as $image) {
            $absolute = admin_absolute_upload_path((string) $image['file_path']);
            if (is_file($absolute)) {
                @unlink($absolute);
            }
        }

        return $productId;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        admin_delete_files($movedFiles);
        throw $e;
    }
}

function admin_delete_product(int $productId): void
{
    $pdo = get_db();
    $state = admin_fetch_product_form_state($productId);
    if ($state === null) {
        throw new RuntimeException('Product not found.');
    }

    $paths = array_map(static fn(array $image): string => admin_absolute_upload_path((string) $image['file_path']), $state['images']);

    $pdo->beginTransaction();
    try {
        $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$productId]);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }

    admin_delete_files($paths);
    $dir = admin_uploads_root() . '/' . $productId;
    if (is_dir($dir)) {
        @rmdir($dir);
    }
}

function admin_update_product_stock(int $productId, array $sizes): void
{
    $product = admin_fetch_product_form_state($productId);
    if ($product === null) {
        throw new RuntimeException('Product not found.');
    }

    [$errors, $normalizedSizes] = admin_validate_product_form(
        [
            'name' => $product['name'],
            'gender' => $product['gender'],
            'price' => $product['price'],
            'original_price' => $product['original_price'],
            'categories' => $product['categories'],
            'sizes' => $sizes,
            'tiktok_url' => $product['tiktok_url'],
        ] + admin_product_defaults(),
        admin_fetch_categories(),
        count($product['images']),
        0
    );

    if ($errors !== []) {
        throw new InvalidArgumentException(implode("\n", $errors));
    }

    $pdo = get_db();
    $pdo->beginTransaction();
    try {
        admin_sync_product_sizes($pdo, $productId, $normalizedSizes);
        $pdo->prepare('UPDATE products SET updated_at = CURRENT_TIMESTAMP WHERE id = ?')->execute([$productId]);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function admin_flash_messages(): array
{
    $messages = [];
    foreach (['success', 'error'] as $key) {
        if (app_flash_has($key)) {
            $messages[$key] = app_flash_get($key);
        }
    }

    return $messages;
}
