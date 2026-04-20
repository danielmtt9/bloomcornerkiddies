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

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['confirm'] ?? '') !== 'delete') {
        $error = 'Confirm deletion to continue.';
    } else {
        try {
            admin_delete_product($productId);
            app_flash_set('success', 'Product deleted.');
            header('Location: /admin/products.php', true, 302);
            exit;
        } catch (Throwable $e) {
            $error = 'Product could not be deleted.';
        }
    }
}

admin_page_start('Delete Product', 'products');
?>
  <section class="panel">
    <h1 class="page-title">Delete Product</h1>
    <p class="page-copy">Are you sure you want to permanently delete “<?= htmlspecialchars($state['name'], ENT_QUOTES, 'UTF-8') ?>”? This cannot be undone.</p>
  </section>

  <?php if ($error !== ''): ?>
    <div class="flash flash--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <section class="panel">
    <form method="post">
      <input type="hidden" name="id" value="<?= $productId ?>">
      <input type="hidden" name="confirm" value="delete">
      <div class="button-row">
        <button type="submit">Yes, Delete</button>
        <a class="button-link" href="/admin/products.php">Cancel</a>
      </div>
    </form>
  </section>
<?php admin_page_end(); ?>
