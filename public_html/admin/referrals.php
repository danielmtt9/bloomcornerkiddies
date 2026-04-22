<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/settings.php';

admin_require_auth();
$messages = admin_flash_messages();
$referralId = (int) ($_GET['id'] ?? $_POST['referral_id'] ?? 0);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    try {
        if ($action === 'create_referral') {
            $code = admin_create_referral_code((string) ($_POST['referrer_name'] ?? ''), (string) ($_POST['referrer_wa'] ?? ''));
            app_flash_set('success', 'New referral code created: ' . $code . ' ✅');
            header('Location: /admin/referrals.php', true, 302);
            exit;
        }

        if ($action === 'record_redemption') {
            admin_record_referral_redemption($referralId, (string) ($_POST['new_buyer_name'] ?? ''), (string) ($_POST['new_buyer_wa'] ?? ''));
            app_flash_set('success', 'Redemption recorded.');
            header('Location: /admin/referrals.php?id=' . $referralId, true, 302);
            exit;
        }

        if ($action === 'toggle_status') {
            admin_set_referral_status($referralId, (string) ($_POST['status'] ?? 'inactive'));
            app_flash_set('success', 'Referral code status updated.');
            header('Location: /admin/referrals.php' . ($referralId > 0 ? '?id=' . $referralId : ''), true, 302);
            exit;
        }
    } catch (Throwable $e) {
        $errors[] = $e instanceof InvalidArgumentException ? $e->getMessage() : 'Referral action could not be completed.';
    }
}

$referrals = admin_fetch_referral_codes();
$detail = $referralId > 0 ? admin_fetch_referral_code_detail($referralId) : null;

admin_page_start('Referrals', 'referrals');
?>
  <section class="panel">
    <h1 class="page-title">Referral Admin</h1>
    <p class="page-copy">Create referral codes, review redemption history, record new redemptions, and deactivate codes without deleting history.</p>
  </section>

  <?php if (isset($messages['success'])): ?>
    <div class="flash flash--success"><?= htmlspecialchars($messages['success'], ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (isset($messages['error'])): ?>
    <div class="flash flash--error"><?= htmlspecialchars($messages['error'], ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php foreach ($errors as $error): ?>
    <div class="flash flash--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endforeach; ?>

  <section class="panel">
    <h2 class="page-title" style="font-size:1.25rem;">Create New Referral Code</h2>
    <form method="post" style="margin-top:16px;">
      <input type="hidden" name="action" value="create_referral">
      <div class="field-grid">
        <div class="field">
          <label for="referrer_name">Referrer Name</label>
          <input id="referrer_name" name="referrer_name" type="text" required>
        </div>
        <div class="field">
          <label for="referrer_wa">WhatsApp Number</label>
          <input id="referrer_wa" name="referrer_wa" type="text" required>
        </div>
      </div>
      <div class="button-row" style="margin-top:16px;">
        <button type="submit">Create Referral Code</button>
      </div>
    </form>
  </section>

  <section class="panel">
    <h2 class="page-title" style="font-size:1.25rem;">Referral Codes</h2>
    <?php if ($referrals === []): ?>
      <p class="page-copy">No referral codes yet.</p>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Code</th>
              <th>Referrer</th>
              <th>WhatsApp</th>
              <th>Total Referrals</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($referrals as $referral): ?>
              <tr>
                <td><a href="/admin/referrals.php?id=<?= (int) $referral['id'] ?>"><?= htmlspecialchars((string) $referral['code'], ENT_QUOTES, 'UTF-8') ?></a></td>
                <td><?= htmlspecialchars((string) $referral['referrer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $referral['referrer_wa'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int) $referral['total_referrals'] ?></td>
                <td><?= htmlspecialchars(ucfirst((string) $referral['status']), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <div class="actions">
                    <a href="/admin/referrals.php?id=<?= (int) $referral['id'] ?>">Open</a>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="action" value="toggle_status">
                      <input type="hidden" name="referral_id" value="<?= (int) $referral['id'] ?>">
                      <input type="hidden" name="status" value="<?= (string) $referral['status'] === 'active' ? 'inactive' : 'active' ?>">
                      <button type="submit"><?= (string) $referral['status'] === 'active' ? 'Deactivate' : 'Activate' ?></button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>

  <?php if ($detail !== null): ?>
    <section class="panel">
      <h2 class="page-title" style="font-size:1.25rem;">Referral Detail</h2>
      <p class="page-copy">
        <?= htmlspecialchars((string) $detail['referral']['code'], ENT_QUOTES, 'UTF-8') ?> ·
        <?= htmlspecialchars((string) $detail['referral']['referrer_name'], ENT_QUOTES, 'UTF-8') ?> ·
        <?= (int) $detail['referral']['total_referrals'] ?> total referrals
      </p>

      <form method="post" style="margin-top:16px;">
        <input type="hidden" name="action" value="record_redemption">
        <input type="hidden" name="referral_id" value="<?= (int) $detail['referral']['id'] ?>">
        <div class="field-grid">
          <div class="field">
            <label for="new_buyer_name">New Buyer Name</label>
            <input id="new_buyer_name" name="new_buyer_name" type="text" required>
          </div>
          <div class="field">
            <label for="new_buyer_wa">New Buyer WhatsApp</label>
            <input id="new_buyer_wa" name="new_buyer_wa" type="text" required>
          </div>
        </div>
        <div class="button-row" style="margin-top:16px;">
          <button type="submit">Record Redemption</button>
        </div>
      </form>

      <div style="margin-top:18px;">
        <h3 class="page-title" style="font-size:1.1rem;">Redemption History</h3>
        <?php if ($detail['uses'] === []): ?>
          <p class="page-copy">No redemptions recorded yet.</p>
        <?php else: ?>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Buyer</th>
                  <th>WhatsApp</th>
                  <th>Discount %</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($detail['uses'] as $use): ?>
                  <tr>
                    <td><?= htmlspecialchars((string) $use['new_buyer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $use['new_buyer_wa'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $use['discount_percent'] ?></td>
                    <td><?= htmlspecialchars((string) $use['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>
<?php admin_page_end(); ?>
