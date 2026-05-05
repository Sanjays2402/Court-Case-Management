<?php
// Legacy thank-you page kept for any inbound links. Real signup flow auto-redirects to dashboard.
require_once __DIR__ . '/config/auth.php';
$page_title = 'Thank you';
$active_nav = null;
include __DIR__ . '/includes/header.php';
?>
<div class="text-center py-5">
  <div class="mb-3" style="font-size:4rem; color: var(--ccms-success)"><i class="bi bi-check-circle"></i></div>
  <h1 class="mb-2">All set!</h1>
  <p class="text-muted mb-4">Your account is ready. Sign in to continue.</p>
  <a href="<?= ccms_user() ? 'dashboard.php' : 'login.php' ?>" class="btn btn-primary px-4">
    <?= ccms_user() ? 'Go to dashboard' : 'Sign in' ?>
  </a>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
