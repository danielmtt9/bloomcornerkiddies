<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/admin.php';

admin_require_auth();
admin_page_start('Settings', 'settings');
?>
  <section class="panel">
    <h1 class="page-title">Settings</h1>
    <p class="page-copy">Store settings and password management land in Sprint 4. This protected route exists now so the admin header is reusable across the admin area.</p>
  </section>
<?php admin_page_end(); ?>
