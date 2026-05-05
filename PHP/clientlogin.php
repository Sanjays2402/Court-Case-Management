<?php
// Backwards-compatible redirect — kept so any old bookmarks still work.
require_once __DIR__ . '/config/auth.php';
ccms_redirect(ccms_user() ? 'dashboard.php' : 'login.php');
