<?php
require_once __DIR__ . '/config/auth.php';

$page_title = 'Sign up';
$active_nav = null;
$errors = [];
$name = $email = $designation = '';
$dob  = '';

if (ccms_user()) {
    ccms_redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ccms_csrf_check()) {
        $errors[] = 'Invalid session token. Please try again.';
    } else {
        $name        = trim($_POST['name']        ?? '');
        $email       = trim($_POST['email']       ?? '');
        $dob         = trim($_POST['dob']         ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $pass        = (string) ($_POST['password']  ?? '');
        $cpass       = (string) ($_POST['cpassword'] ?? '');

        if ($name === '' || !preg_match('/^[A-Za-z][A-Za-z .\'-]{1,99}$/', $name)) {
            $errors[] = 'Enter a valid name (letters, spaces, dots, hyphens).';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }
        if ($dob !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
            $errors[] = 'Enter a valid date of birth.';
        }
        if ($designation === '' || strlen($designation) > 80) {
            $errors[] = 'Designation is required.';
        }
        if (strlen($pass) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if ($pass !== $cpass) {
            $errors[] = 'Passwords do not match.';
        }

        if (!$errors) {
            $db   = ccms_db();
            $stmt = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'An account with this email already exists.';
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $ins  = $db->prepare(
                    'INSERT INTO users (name, email, password_hash, dob, designation, role)
                     VALUES (?, ?, ?, ?, ?, "client")'
                );
                $ins->execute([
                    $name,
                    $email,
                    $hash,
                    $dob !== '' ? $dob : null,
                    $designation,
                ]);
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $db->lastInsertId();
                ccms_flash('success', 'Account created. Welcome to CCMS!');
                ccms_redirect('dashboard.php');
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="auth-shell">
  <div class="card">
    <h4 class="text-center"><i class="bi bi-person-plus"></i> Create an account</h4>
    <?php include __DIR__ . '/includes/errors.php'; ?>
    <form method="post" action="signup.php" novalidate>
      <?= ccms_csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" type="text" class="form-control" value="<?= ccms_e($name) ?>" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" value="<?= ccms_e($email) ?>" required>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Date of birth</label>
          <input name="dob" type="date" class="form-control" value="<?= ccms_e($dob) ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Designation</label>
          <input name="designation" type="text" class="form-control"
                 value="<?= ccms_e($designation) ?>" placeholder="Client / Advocate / Junior" required>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Password</label>
          <input name="password" type="password" class="form-control" minlength="8" required>
          <div class="form-text">At least 8 characters.</div>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Confirm password</label>
          <input name="cpassword" type="password" class="form-control" minlength="8" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100">Create account</button>
      <div class="text-center mt-3 small">
        <a href="login.php">Already have an account? Sign in →</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
