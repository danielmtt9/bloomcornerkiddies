<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/admin.php';

app_start_session();
admin_apply_no_cache_headers();
admin_logout_user();

header('Location: /admin/login.php', true, 302);
exit;
