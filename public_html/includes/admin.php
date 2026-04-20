<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

const ADMIN_SESSION_TIMEOUT = 28800;

function admin_is_production(): bool
{
    return APP_ENV === 'production';
}

function admin_is_https_request(): bool
{
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return true;
    }

    if ((string) ($_SERVER['SERVER_PORT'] ?? '') === '443') {
        return true;
    }

    if (strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https') {
        return true;
    }

    return false;
}

function admin_https_redirect_url(): ?string
{
    if (!admin_is_production() || admin_is_https_request()) {
        return null;
    }

    $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
    $uri = (string) ($_SERVER['REQUEST_URI'] ?? '/admin/login.php');

    if ($host === '') {
        return null;
    }

    return 'https://' . $host . $uri;
}

function admin_enforce_https(): void
{
    if (PHP_SAPI === 'cli') {
        return;
    }

    $redirect = admin_https_redirect_url();
    if ($redirect === null) {
        return;
    }

    header('Location: ' . $redirect, true, 302);
    exit;
}

function admin_apply_no_cache_headers(): void
{
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
}

function admin_verify_password(string $password): bool
{
    if (ADMIN_PASSWORD_HASH === '') {
        return false;
    }

    return password_verify($password, ADMIN_PASSWORD_HASH);
}

function admin_mark_authenticated(): void
{
    app_start_session();
    session_regenerate_id(true);
    $_SESSION['authenticated'] = true;
    $_SESSION['last_activity_at'] = time();
}

function admin_is_authenticated(): bool
{
    app_start_session();

    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        return false;
    }

    $lastActivityAt = (int) ($_SESSION['last_activity_at'] ?? 0);
    if ($lastActivityAt > 0 && (time() - $lastActivityAt) > ADMIN_SESSION_TIMEOUT) {
        admin_logout_user();
        return false;
    }

    $_SESSION['last_activity_at'] = time();

    return true;
}

function admin_auth_redirect_target(): ?string
{
    return admin_is_authenticated() ? null : '/admin/login.php';
}

function admin_require_auth(): void
{
    admin_enforce_https();
    admin_apply_no_cache_headers();

    $redirect = admin_auth_redirect_target();
    if ($redirect === null) {
        return;
    }

    header('Location: ' . $redirect, true, 302);
    exit;
}

function admin_logout_user(): void
{
    app_start_session();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }

    session_destroy();
}

function admin_handle_login_attempt(string $password): array
{
    $password = trim($password);
    if ($password === '') {
        return ['success' => false, 'error' => 'Enter your admin password.'];
    }

    if (!admin_verify_password($password)) {
        return ['success' => false, 'error' => 'Incorrect password.'];
    }

    admin_mark_authenticated();

    return ['success' => true, 'error' => ''];
}

function admin_current_status(): string
{
    $status = strtolower(get_config('seller_status', 'online'));
    $allowed = ['online', 'brb', 'offline'];

    return in_array($status, $allowed, true) ? $status : 'online';
}

function admin_status_badge(string $status): string
{
    return match ($status) {
        'brb' => '🟡 BRB',
        'offline' => '🔴 Offline',
        default => '🟢 Online',
    };
}

function admin_navigation_items(): array
{
    return [
        'products' => ['label' => 'Products', 'href' => '/admin/index.php'],
        'referrals' => ['label' => 'Referrals', 'href' => '/admin/referrals.php'],
        'settings' => ['label' => 'Settings', 'href' => '/admin/settings.php'],
        'logout' => ['label' => 'Logout', 'href' => '/admin/logout.php'],
    ];
}

function admin_page_start(string $title, string $activeNav = 'products'): void
{
    $status = admin_status_badge(admin_current_status());
    $navItems = admin_navigation_items();
    ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> | Bloom Corner Kiddies Admin</title>
  <style>
    :root {
      color-scheme: light;
      --bg: #fffaf5;
      --surface: #ffffff;
      --surface-alt: #fff2df;
      --text: #23180f;
      --muted: #7a624d;
      --accent: #d56a2d;
      --accent-soft: #f7d6b6;
      --border: #ebd7c5;
      --danger: #9a3412;
      --ok: #166534;
      --shadow: 0 12px 32px rgba(83, 46, 26, 0.08);
    }

    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: "Trebuchet MS", Verdana, sans-serif;
      background: linear-gradient(180deg, #fff8f0 0%, #fffdf9 100%);
      color: var(--text);
      overflow-x: hidden;
    }
    a { color: inherit; }
    .admin-shell {
      width: min(100%, 960px);
      margin: 0 auto;
      padding: 16px;
    }
    .admin-header {
      position: sticky;
      top: 0;
      z-index: 10;
      background: rgba(255, 250, 245, 0.94);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
    }
    .admin-header__inner {
      width: min(100%, 960px);
      margin: 0 auto;
      padding: 12px 16px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .admin-brand {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      font-weight: 700;
    }
    .admin-status {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 12px;
      border-radius: 999px;
      background: var(--surface-alt);
      border: 1px solid var(--border);
      color: var(--muted);
      font-size: 0.95rem;
    }
    .admin-nav {
      display: flex;
      gap: 10px;
      overflow-x: auto;
      padding-bottom: 4px;
      scrollbar-width: thin;
    }
    .admin-nav a {
      text-decoration: none;
      white-space: nowrap;
      padding: 10px 14px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: var(--surface);
      box-shadow: var(--shadow);
      color: var(--muted);
      font-weight: 700;
      font-size: 0.95rem;
    }
    .admin-nav a.is-active {
      background: var(--accent);
      border-color: var(--accent);
      color: #fff7ef;
    }
    .panel {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 20px;
      box-shadow: var(--shadow);
      padding: 18px;
      margin-bottom: 16px;
    }
    .page-title {
      margin: 0 0 8px;
      font-size: 1.6rem;
      line-height: 1.15;
    }
    .page-copy {
      margin: 0;
      color: var(--muted);
      line-height: 1.5;
    }
    .metrics {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 12px;
      margin: 18px 0;
    }
    .metric-card {
      padding: 16px;
      border-radius: 18px;
      background: var(--surface-alt);
      border: 1px solid var(--border);
    }
    .metric-label {
      margin: 0 0 6px;
      color: var(--muted);
      font-size: 0.92rem;
    }
    .metric-value {
      margin: 0;
      font-size: 1.8rem;
      font-weight: 800;
    }
    .table-wrap {
      overflow-x: auto;
      border-radius: 16px;
      border: 1px solid var(--border);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 520px;
      background: var(--surface);
    }
    th, td {
      text-align: left;
      padding: 12px;
      border-bottom: 1px solid var(--border);
      vertical-align: top;
    }
    th {
      background: #fff4ea;
      font-size: 0.88rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: var(--muted);
    }
    .muted { color: var(--muted); }
    .pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: #fff;
      font-size: 0.86rem;
      color: var(--muted);
      margin-right: 6px;
      margin-bottom: 6px;
    }
    .flash {
      padding: 12px 14px;
      border-radius: 14px;
      margin-bottom: 14px;
      border: 1px solid var(--border);
    }
    .flash--error {
      background: #fff1ec;
      border-color: #f7c4b0;
      color: var(--danger);
    }
    .flash--success {
      background: #edfdf1;
      border-color: #b7ebc1;
      color: var(--ok);
    }
    .stack { display: grid; gap: 12px; }
    .placeholder-links { display: flex; flex-wrap: wrap; gap: 10px; }
    .placeholder-links a {
      color: var(--accent);
      font-weight: 700;
    }
    .login-shell {
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 20px;
    }
    .login-card {
      width: min(100%, 420px);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 24px;
      box-shadow: var(--shadow);
      padding: 24px;
    }
    label {
      display: block;
      font-weight: 700;
      margin-bottom: 6px;
    }
    input[type="password"] {
      width: 100%;
      border-radius: 14px;
      border: 1px solid var(--border);
      padding: 14px 16px;
      font: inherit;
      background: #fff;
    }
    button,
    .button-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      min-height: 46px;
      padding: 0 18px;
      border-radius: 14px;
      border: 0;
      background: var(--accent);
      color: #fff8f1;
      font: inherit;
      font-weight: 700;
      text-decoration: none;
      cursor: pointer;
    }
    .button-row { display: flex; flex-wrap: wrap; gap: 10px; }
    @media (max-width: 480px) {
      .admin-shell,
      .admin-header__inner {
        padding-left: 14px;
        padding-right: 14px;
      }
      .page-title {
        font-size: 1.35rem;
      }
      th, td {
        padding: 10px;
      }
    }
  </style>
</head>
<body>
  <header class="admin-header">
    <div class="admin-header__inner">
      <div class="admin-brand">
        <span>Bloom Corner Kiddies Admin</span>
        <span class="admin-status"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span>
      </div>
      <nav class="admin-nav" aria-label="Admin navigation">
        <?php foreach ($navItems as $key => $item): ?>
          <a class="<?= $activeNav === $key ? 'is-active' : '' ?>" href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
          </a>
        <?php endforeach; ?>
      </nav>
    </div>
  </header>
  <main class="admin-shell">
<?php
}

function admin_page_end(): void
{
    echo "  </main>\n</body>\n</html>";
}

function admin_fetch_dashboard_metrics(): array
{
    $pdo = get_db();

    $totalProducts = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    $soldOutProducts = (int) $pdo->query(
        'SELECT COUNT(*) FROM products p
         WHERE EXISTS (
             SELECT 1 FROM product_sizes ps WHERE ps.product_id = p.id
         ) AND NOT EXISTS (
             SELECT 1 FROM product_sizes ps WHERE ps.product_id = p.id AND ps.stock_qty > 0
         )'
    )->fetchColumn();
    $totalReferralCodes = (int) $pdo->query('SELECT COUNT(*) FROM referral_codes')->fetchColumn();

    $stmt = $pdo->query(
        'SELECT
            p.id,
            p.name,
            p.gender,
            p.updated_at,
            COALESCE(GROUP_CONCAT(DISTINCT c.name ORDER BY c.sort_order SEPARATOR ", "), "") AS categories,
            COALESCE(stock_totals.total_stock, 0) AS total_stock
         FROM products p
         LEFT JOIN product_categories pc ON pc.product_id = p.id
         LEFT JOIN categories c ON c.id = pc.category_id
         LEFT JOIN (
             SELECT product_id, SUM(stock_qty) AS total_stock
             FROM product_sizes
             GROUP BY product_id
         ) stock_totals ON stock_totals.product_id = p.id
         GROUP BY p.id, p.name, p.gender, p.updated_at
         ORDER BY p.updated_at DESC, p.id DESC
         LIMIT 5'
    );

    return [
        'total_products' => $totalProducts,
        'sold_out_products' => $soldOutProducts,
        'total_referral_codes' => $totalReferralCodes,
        'recent_products' => $stmt->fetchAll(),
    ];
}
