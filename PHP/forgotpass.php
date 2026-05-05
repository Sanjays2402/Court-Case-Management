<?php
require_once __DIR__ . '/config/auth.php';

$page_title = 'Forgot password';
$active_nav = null;

include __DIR__ . '/includes/header.php';
?>
<div class="auth-shell">
  <div class="card">
    <h4 class="text-center"><i class="bi bi-key"></i> Forgot password</h4>
    <p class="text-muted small text-center">
      Password reset over email is not yet wired up in this build.
      For now, contact an administrator at <a href="mailto:support@ccms.local">support@ccms.local</a>
      to request a reset.
    </p>
    <div class="text-center mt-3">
      <a href="login.php" class="btn btn-outline-primary">← Back to sign in</a>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
