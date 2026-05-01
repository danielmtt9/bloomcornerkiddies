<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/settings.php';

admin_require_auth();
$messages = admin_flash_messages();
$settings = admin_settings_form_defaults();
$errors = [];
$passwordErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? 'save_settings');

    if ($action === 'save_settings') {
        [$errors, $normalized] = admin_validate_settings_payload($_POST);
        $settings = array_merge($settings, $normalized);

        if ($errors === []) {
            try {
                admin_save_seller_config($normalized);
                app_flash_set('success', 'Settings saved.');
                header('Location: /admin/settings.php', true, 302);
                exit;
            } catch (Throwable $e) {
                $errors[] = 'Settings could not be saved.';
            }
        }
    }

    if ($action === 'change_password') {
        try {
            admin_change_password(
                (string) ($_POST['current_password'] ?? ''),
                (string) ($_POST['new_password'] ?? ''),
                (string) ($_POST['confirm_password'] ?? '')
            );
            app_flash_set('success', 'Admin password updated.');
            header('Location: /admin/settings.php', true, 302);
            exit;
        } catch (Throwable $e) {
            $passwordErrors[] = $e instanceof InvalidArgumentException ? $e->getMessage() : 'Password could not be updated.';
        }
    }
}

admin_page_start('Settings', 'settings');
?>
  <section class="panel">
    <h1 class="page-title">Store Settings</h1>
    <p class="page-copy">Storefront-facing copy, contact details, seller status, referral discount, and admin password all live here.</p>
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
    <form method="post">
      <input type="hidden" name="action" value="save_settings">
      <div class="field-grid">
        <div class="field">
          <label for="store_name">Store Name</label>
          <input id="store_name" name="store_name" type="text" value="<?= htmlspecialchars($settings['store_name'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="field">
          <label for="tagline">Tagline</label>
          <input id="tagline" name="tagline" type="text" value="<?= htmlspecialchars($settings['tagline'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Placeholder copy is acceptable for MVP.">
        </div>
        <div class="field">
          <label for="wa_number">WhatsApp Number</label>
          <input id="wa_number" name="wa_number" type="text" value="<?= htmlspecialchars($settings['wa_number'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="field">
          <label for="telegram_link">Telegram Link</label>
          <input id="telegram_link" name="telegram_link" type="url" value="<?= htmlspecialchars($settings['telegram_link'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://t.me/placeholder">
        </div>
        <div class="field">
          <label for="seller_status">Seller Status</label>
          <select id="seller_status" name="seller_status" required>
            <?php foreach (['online' => 'Online', 'brb' => 'BRB', 'offline' => 'Offline'] as $value => $label): ?>
              <option value="<?= $value ?>" <?= $settings['seller_status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="referral_discount_percent">Referral Discount Percent</label>
          <input id="referral_discount_percent" name="referral_discount_percent" type="number" min="0" max="100" step="1" value="<?= htmlspecialchars($settings['referral_discount_percent'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Leave blank for NULL">
          <div class="hint">Blank stores `NULL` so referrals can be tracking-only.</div>
        </div>
      </div>

      <div class="field" style="margin-top:14px;">
        <label for="intro_text">Intro Text</label>
        <textarea id="intro_text" name="intro_text"><?= htmlspecialchars($settings['intro_text'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <div class="field" style="margin-top:14px;">
        <label for="delivery_info">Delivery Info</label>
        <textarea id="delivery_info" name="delivery_info"><?= htmlspecialchars($settings['delivery_info'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <div class="field" style="margin-top:14px;">
        <label for="status_message">Status Message</label>
        <input id="status_message" name="status_message" type="text" value="<?= htmlspecialchars($settings['status_message'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Replies within a few hours.">
      </div>

      <div class="field" style="margin-top:14px;">
        <label for="payment_info">Payment Info</label>
        <textarea id="payment_info" name="payment_info"><?= htmlspecialchars($settings['payment_info'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <h3 style="margin-top:24px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">Footer & Socials</h3>
      <div class="field-grid" style="margin-top:16px;">
        <div class="field">
          <label for="instagram_link">Instagram Link</label>
          <input id="instagram_link" name="instagram_link" type="url" value="<?= htmlspecialchars($settings['instagram_link'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://instagram.com/...">
        </div>
        <div class="field">
          <label for="facebook_link">Facebook Link</label>
          <input id="facebook_link" name="facebook_link" type="url" value="<?= htmlspecialchars($settings['facebook_link'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://facebook.com/...">
        </div>
        <div class="field">
          <label for="tiktok_link">TikTok Link</label>
          <input id="tiktok_link" name="tiktok_link" type="url" value="<?= htmlspecialchars($settings['tiktok_link'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://tiktok.com/@...">
        </div>
        <div class="field">
          <label for="logo_url">Logo URL</label>
          <input id="logo_url" name="logo_url" type="url" value="<?= htmlspecialchars($settings['logo_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://.../logo.png">
        </div>
      </div>
      <div class="field" style="margin-top:14px;">
        <label for="store_address">Store Address</label>
        <textarea id="store_address" name="store_address" placeholder="123 Example Street, City"><?= htmlspecialchars($settings['store_address'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <div class="button-row" style="margin-top:16px;">
        <button type="submit">Save Settings</button>
      </div>
    </form>
  </section>

  <section class="panel">
    <h2 class="page-title" style="font-size:1.25rem;">Quick Seller Status</h2>
    <p class="page-copy">Use the focused status form when you only need to change availability and reply note.</p>
    <form method="post" action="/admin/status.php" style="margin-top:16px;">
      <div class="field-grid">
        <div class="field">
          <label for="quick_status">Status</label>
          <select id="quick_status" name="seller_status">
            <?php foreach (['online' => 'Online', 'brb' => 'BRB', 'offline' => 'Offline'] as $value => $label): ?>
              <option value="<?= $value ?>" <?= $settings['seller_status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="quick_status_message">Status Message</label>
          <input id="quick_status_message" name="status_message" type="text" value="<?= htmlspecialchars($settings['status_message'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
      </div>
      <div class="button-row" style="margin-top:16px;">
        <button type="submit">Update Status</button>
      </div>
    </form>
  </section>

  <section class="panel">
    <h2 class="page-title" style="font-size:1.25rem;">Change Admin Password</h2>
    <?php foreach ($passwordErrors as $error): ?>
      <div class="flash flash--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endforeach; ?>
    <form method="post" style="margin-top:16px;">
      <input type="hidden" name="action" value="change_password">
      <div class="field-grid">
        <div class="field">
          <label for="current_password">Current Password</label>
          <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
        </div>
        <div class="field">
          <label for="new_password">New Password</label>
          <input id="new_password" name="new_password" type="password" autocomplete="new-password" required>
        </div>
        <div class="field">
          <label for="confirm_password">Confirm New Password</label>
          <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" required>
        </div>
      </div>
      <div class="button-row" style="margin-top:16px;">
        <button type="submit">Update Password</button>
      </div>
    </form>
  </section>
<?php admin_page_end(); ?>
