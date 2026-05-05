<?php
// Backwards-compatible redirect — old hardcoded "case detail" page is gone.
require_once __DIR__ . '/config/auth.php';
ccms_redirect(ccms_user() ? 'cases.php' : 'login.php');
