<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/admin.php';

admin_enforce_https();
app_start_session();
admin_apply_no_cache_headers();

if (admin_is_authenticated()) {
    header('Location: /admin/index.php', true, 302);
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = admin_handle_login_attempt((string) ($_POST['password'] ?? ''));
    if ($result['success']) {
        header('Location: /admin/index.php', true, 302);
        exit;
    }

    $error = (string) $result['error'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | Bloom Corner Kiddies</title>
  <style>
    :root {
      color-scheme: light;
      --bg: #fff8f0;
      --surface: #ffffff;
      --text: #23180f;
      --muted: #7a624d;
      --accent: #d56a2d;
      --border: #ebd7c5;
      --danger: #9a3412;
      --shadow: 0 18px 44px rgba(83, 46, 26, 0.12);
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 20px;
      font-family: "Trebuchet MS", Verdana, sans-serif;
      background: radial-gradient(circle at top, #fff1dd 0%, #fff8f0 42%, #fffdf9 100%);
      color: var(--text);
      overflow-x: hidden;
    }
    .card {
      width: min(100%, 420px);
      border-radius: 24px;
      border: 1px solid var(--border);
      background: var(--surface);
      box-shadow: var(--shadow);
      padding: 24px;
    }
    h1 {
      margin: 0 0 10px;
      font-size: 1.75rem;
      line-height: 1.1;
    }
    p {
      margin: 0 0 18px;
      color: var(--muted);
      line-height: 1.5;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 700;
    }
    input[type="password"] {
      width: 100%;
      border-radius: 14px;
      border: 1px solid var(--border);
      padding: 14px 16px;
      font: inherit;
    }
    button {
      width: 100%;
      margin-top: 16px;
      min-height: 48px;
      border: 0;
      border-radius: 14px;
      background: var(--accent);
      color: #fff8f1;
      font: inherit;
      font-weight: 700;
      cursor: pointer;
    }
    .error {
      margin-bottom: 16px;
      padding: 12px 14px;
      border-radius: 14px;
      background: #fff1ec;
      border: 1px solid #f7c4b0;
      color: var(--danger);
    }
  </style>
</head>
<body>
  <section class="card">
    <h1>Admin Login</h1>
    <p>Enter the single admin password to manage products, referrals, and store settings.</p>

    <?php if ($error !== ''): ?>
      <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="/admin/login.php">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" autocomplete="current-password" required>
      <button type="submit">Log In</button>
    </form>
  </section>
</body>
</html>
