<?php

declare(strict_types=1);

$password = 'sprint-2-admin-pass';
putenv('ADMIN_PASSWORD_HASH=' . password_hash($password, PASSWORD_BCRYPT));
$_ENV['ADMIN_PASSWORD_HASH'] = getenv('ADMIN_PASSWORD_HASH');
$_SERVER['HTTPS'] = 'on';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'example.test';
$_SERVER['REQUEST_URI'] = '/admin/login.php';

require_once dirname(__DIR__) . '/public_html/includes/admin.php';

if (!admin_verify_password($password)) {
    fwrite(STDERR, "Expected password verification success.\n");
    exit(1);
}

if (admin_verify_password('wrong-password')) {
    fwrite(STDERR, "Expected password verification failure.\n");
    exit(1);
}

$success = admin_handle_login_attempt($password);
if ($success['success'] !== true || admin_auth_redirect_target() !== null) {
    fwrite(STDERR, "Expected successful login and authenticated session.\n");
    exit(1);
}

$_SESSION['last_activity_at'] = time() - (ADMIN_SESSION_TIMEOUT + 10);
if (admin_auth_redirect_target() !== '/admin/login.php') {
    fwrite(STDERR, "Expected expired session to redirect to login.\n");
    exit(1);
}

app_start_session();
$_SESSION['authenticated'] = true;
$_SESSION['last_activity_at'] = time();
admin_logout_user();
app_start_session();

if (isset($_SESSION['authenticated'])) {
    fwrite(STDERR, "Expected logout to clear authenticated session state.\n");
    exit(1);
}

$_SERVER['HTTPS'] = 'off';
$_SERVER['REQUEST_URI'] = '/admin/login.php';
$_SERVER['HTTP_HOST'] = 'bloom.example';

if (admin_https_redirect_url() !== 'https://bloom.example/admin/login.php') {
    fwrite(STDERR, "Expected production HTTP login to redirect to HTTPS.\n");
    exit(1);
}

echo "PASS: admin auth smoke test\n";
