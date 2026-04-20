<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/products.php';

admin_require_auth();
$messages = admin_flash_messages();
$products = admin_fetch_product_list();
admin_page_start('Products', 'products');
?>
  <section class="panel">
    <div class="button-row" style="justify-content:space-between; align-items:flex-start;">
      <div>
        <h1 class="page-title">Product List</h1>
        <p class="page-copy">All catalogue items, including hidden products, sorted by the most recently updated first.</p>
      </div>
      <a class="button-link" href="/admin/add.php">Add New Product</a>
    </div>
  </section>

  <?php if (isset($messages['success'])): ?>
    <div class="flash flash--success"><?= htmlspecialchars($messages['success'], ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (isset($messages['error'])): ?>
    <div class="flash flash--error"><?= htmlspecialchars($messages['error'], ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <section class="panel">
    <?php if ($products === []): ?>
      <p class="page-copy">No products yet. Start with your first catalogue item.</p>
      <div class="button-row" style="margin-top:14px;">
        <a class="button-link" href="/admin/add.php">Create First Product</a>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Photo</th>
              <th>Name</th>
              <th>Price</th>
              <th>Categories</th>
              <th>Availability</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product): ?>
              <tr>
                <td>
                  <?php if ((string) $product['primary_image'] !== ''): ?>
                    <img class="thumbnail" src="/<?= htmlspecialchars((string) $product['primary_image'], ENT_QUOTES, 'UTF-8') ?>" alt="">
                  <?php else: ?>
                    <div class="thumbnail" style="display:grid;place-items:center;">No photo</div>
                  <?php endif; ?>
                </td>
                <td>
                  <strong><?= htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                  <span class="muted"><?= htmlspecialchars(ucfirst((string) $product['gender']), ENT_QUOTES, 'UTF-8') ?></span><br>
                  <span class="muted"><?= htmlspecialchars((string) $product['size_summary'], ENT_QUOTES, 'UTF-8') ?></span>
                </td>
                <td>₦<?= number_format((int) $product['price']) ?></td>
                <td><?= htmlspecialchars((string) $product['categories'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <span class="status-badge <?= (int) $product['is_available'] === 1 ? 'is-active' : 'is-hidden' ?>">
                    <?= (int) $product['is_available'] === 1 ? 'Active' : 'Hidden' ?>
                  </span>
                </td>
                <td>
                  <div class="actions">
                    <a class="primary" href="/admin/edit.php?id=<?= (int) $product['id'] ?>">Edit</a>
                    <a href="/admin/stock.php?id=<?= (int) $product['id'] ?>">Stock</a>
                    <a class="danger-link" href="/admin/delete.php?id=<?= (int) $product['id'] ?>">Delete</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
<?php admin_page_end(); ?>
