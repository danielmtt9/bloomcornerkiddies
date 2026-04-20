<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/admin.php';

admin_require_auth();
$metrics = admin_fetch_dashboard_metrics();
admin_page_start('Products Dashboard', 'products');
?>
  <section class="panel">
    <h1 class="page-title">Products Dashboard</h1>
    <p class="page-copy">Quick mobile view of catalogue health, referral usage, and recently updated products.</p>

    <div class="metrics">
      <article class="metric-card">
        <p class="metric-label">Total Products</p>
        <p class="metric-value"><?= (int) $metrics['total_products'] ?></p>
      </article>
      <article class="metric-card">
        <p class="metric-label">Sold-Out Products</p>
        <p class="metric-value"><?= (int) $metrics['sold_out_products'] ?></p>
      </article>
      <article class="metric-card">
        <p class="metric-label">Referral Codes</p>
        <p class="metric-value"><?= (int) $metrics['total_referral_codes'] ?></p>
      </article>
    </div>
  </section>

  <section class="panel">
    <h2 class="page-title" style="font-size:1.25rem;">Recently Updated Products</h2>
    <?php if ($metrics['recent_products'] === []): ?>
      <p class="page-copy">No products yet. Sprint 3 adds product creation, but the dashboard shell is ready now.</p>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th>Categories</th>
              <th>Gender</th>
              <th>Total Stock</th>
              <th>Updated</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($metrics['recent_products'] as $product): ?>
              <tr>
                <td><?= htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $product['categories'], ENT_QUOTES, 'UTF-8') ?: '<span class="muted">Unassigned</span>' ?></td>
                <td><?= htmlspecialchars(ucfirst((string) $product['gender']), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int) $product['total_stock'] ?></td>
                <td><?= htmlspecialchars((string) $product['updated_at'], ENT_QUOTES, 'UTF-8') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>

  <section class="panel">
    <h2 class="page-title" style="font-size:1.25rem;">Next Admin Surfaces</h2>
    <div class="placeholder-links">
      <a href="/admin/referrals.php">Open Referrals</a>
      <a href="/admin/settings.php">Open Settings</a>
    </div>
  </section>
<?php admin_page_end(); ?>
