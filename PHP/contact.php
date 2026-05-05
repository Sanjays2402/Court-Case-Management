<?php
require_once __DIR__ . '/config/auth.php';
$db = ccms_db();

$page_title = 'Contact us';
$active_nav = 'contact';

$errors = [];
$f = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ccms_csrf_check()) {
        $errors[] = 'Invalid session token. Please try again.';
    } else {
        foreach (array_keys($f) as $k) {
            $f[$k] = trim($_POST[$k] ?? '');
        }
        if ($f['name']    === '' || strlen($f['name']) > 100)         $errors[] = 'Name is required.';
        if (!filter_var($f['email'], FILTER_VALIDATE_EMAIL))           $errors[] = 'Enter a valid email.';
        if ($f['subject'] === '' || strlen($f['subject']) > 150)      $errors[] = 'Subject is required.';
        if (strlen($f['message']) < 10 || strlen($f['message']) > 5000) $errors[] = 'Message must be 10–5000 characters.';

        if (!$errors) {
            $stmt = $db->prepare(
                'INSERT INTO contact_messages (name, email, subject, message)
                 VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$f['name'], $f['email'], $f['subject'], $f['message']]);
            ccms_flash('success', 'Thanks! Your message has been received.');
            ccms_redirect('contact.php');
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="row g-4">
  <div class="col-lg-7">
    <h2 class="mb-2">Contact us</h2>
    <p class="text-muted mb-4">
      Questions about filing a case, your account, or technical issues? Send us a message and
      we'll get back to you within 1–2 working days.
    </p>

    <div class="card">
      <div class="card-body">
        <?php include __DIR__ . '/includes/errors.php'; ?>
        <form method="post" novalidate>
          <?= ccms_csrf_field() ?>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name *</label>
              <input name="name" class="form-control" maxlength="100" required
                     value="<?= ccms_e($f['name']) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email *</label>
              <input name="email" type="email" class="form-control" required
                     value="<?= ccms_e($f['email']) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Subject *</label>
              <input name="subject" class="form-control" maxlength="150" required
                     value="<?= ccms_e($f['subject']) ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Message *</label>
              <textarea name="message" class="form-control" rows="5" required
                        minlength="10" maxlength="5000"><?= ccms_e($f['message']) ?></textarea>
            </div>
          </div>
          <button class="btn btn-primary mt-3"><i class="bi bi-send"></i> Send message</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card">
      <div class="card-body">
        <h5 class="mb-3"><i class="bi bi-info-circle"></i> About</h5>
        <p class="text-muted">
          The Court Case Management System (CCMS) is a repository of departmental court cases
          built to provide end-to-end management from a client perspective: filing, advocate
          assignment, hearings, invoices, and outcomes.
        </p>
        <hr>
        <h6 class="mb-3">Reach us</h6>
        <ul class="list-unstyled small mb-0">
          <li class="mb-2"><i class="bi bi-envelope me-2"></i> support@ccms.local</li>
          <li class="mb-2"><i class="bi bi-clock me-2"></i> Mon–Fri, 9 AM – 6 PM</li>
          <li><i class="bi bi-shield-check me-2"></i> Replies within 1–2 working days</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
