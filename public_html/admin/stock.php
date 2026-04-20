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

$errors = [];
$sizes = $state['sizes'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sizes = admin_normalize_sizes((array) ($_POST['sizes'] ?? []));
    try {
        admin_update_product_stock($productId, $sizes);
        app_flash_set('success', 'Stock updated.');
        header('Location: /admin/products.php', true, 302);
        exit;
    } catch (InvalidArgumentException $e) {
        $errors = array_filter(explode("\n", $e->getMessage()));
    } catch (Throwable $e) {
        $errors = ['Stock could not be updated.'];
    }
}

admin_page_start('Quick Stock Update', 'products');
?>
  <section class="panel">
    <h1 class="page-title">Quick Stock Update</h1>
    <p class="page-copy"><?= htmlspecialchars($state['name'], ENT_QUOTES, 'UTF-8') ?></p>
  </section>

  <?php foreach ($errors as $error): ?>
    <div class="flash flash--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endforeach; ?>

  <section class="panel">
    <form method="post">
      <input type="hidden" name="id" value="<?= $productId ?>">
      <div class="table-wrap">
        <table class="size-table">
          <thead>
            <tr>
              <th>Size</th>
              <th>Stock</th>
              <th>Sold Out</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sizes as $index => $size): ?>
              <tr>
                <td>
                  <input type="text" name="sizes[<?= $index ?>][size_label]" value="<?= htmlspecialchars((string) $size['size_label'], ENT_QUOTES, 'UTF-8') ?>" required>
                </td>
                <td>
                  <input type="number" min="0" name="sizes[<?= $index ?>][stock_qty]" value="<?= htmlspecialchars((string) $size['stock_qty'], ENT_QUOTES, 'UTF-8') ?>" required>
                </td>
                <td>
                  <label class="checkbox">
                    <input type="checkbox" name="sizes[<?= $index ?>][is_sold_out]" value="1" <?= !empty($size['is_sold_out']) ? 'checked' : '' ?>>
                    <span class="muted">Mark this size sold out.</span>
                  </label>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="button-row" style="margin-top:16px;">
        <button type="submit">Save Stock</button>
        <a class="button-link" href="/admin/products.php">Back to Products</a>
      </div>
    </form>
  </section>
<?php admin_page_end(); ?>
