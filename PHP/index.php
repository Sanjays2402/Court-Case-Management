<?php
require_once __DIR__ . '/config/auth.php';

$page_title = 'Welcome';
$active_nav = null;

if (ccms_user()) {
    ccms_redirect('dashboard.php');
}

include __DIR__ . '/includes/header.php';
?>

<section class="ccms-hero mb-5">
  <h1>Court Case Management, <span style="color:var(--ccms-accent)">simplified.</span></h1>
  <p class="lead mb-4">
    File cases, track hearings, manage advocates, and keep every party on the same page —
    all from one secure portal.
  </p>
  <div class="d-flex justify-content-center gap-2">
    <a href="signup.php" class="btn btn-primary px-4"><i class="bi bi-person-plus"></i> Get started</a>
    <a href="login.php" class="btn btn-outline-primary px-4"><i class="bi bi-box-arrow-in-right"></i> Sign in</a>
  </div>
</section>

<section class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="ccms-feature h-100">
      <i class="bi bi-folder2-open"></i>
      <h5 class="mt-2">Centralised case records</h5>
      <p class="text-muted mb-0 small">
        Store case type, FIR number, advocate, hearings, and outcomes in one structured place.
      </p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="ccms-feature h-100">
      <i class="bi bi-calendar-event"></i>
      <h5 class="mt-2">Hearing reminders</h5>
      <p class="text-muted mb-0 small">
        Surface upcoming hearings on your dashboard so nothing slips between dates.
      </p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="ccms-feature h-100">
      <i class="bi bi-shield-lock"></i>
      <h5 class="mt-2">Secure by default</h5>
      <p class="text-muted mb-0 small">
        Hashed passwords, CSRF-protected forms, prepared statements. No more plaintext.
      </p>
    </div>
  </div>
</section>

<section class="ccms-card p-4">
  <h4 class="mb-3">About the system</h4>
  <p class="text-muted">
    A web-based case management system for advocates, clients, and court staff. Track every case
    from filing through final order: parties, hearings, notes, and status — searchable, exportable,
    and reportable.
  </p>
  <p class="text-muted mb-0">
    Originally built as an SIH (Smart India Hackathon) capstone, now rewritten with PDO,
    prepared statements, and a modern Bootstrap 5 UI.
  </p>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
