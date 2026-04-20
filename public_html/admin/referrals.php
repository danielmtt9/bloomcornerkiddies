<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/admin.php';

admin_require_auth();
admin_page_start('Referrals', 'referrals');
?>
  <section class="panel">
    <h1 class="page-title">Referrals</h1>
    <p class="page-copy">Referral management lands in Sprint 4. This protected route exists now so the admin shell is consistent across navigation.</p>
  </section>
<?php admin_page_end(); ?>
