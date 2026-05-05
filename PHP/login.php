<?php
require_once __DIR__ . '/config/auth.php';

$page_title = 'Sign in';
$active_nav = null;
$errors = [];
$email  = '';

if (ccms_user()) {
    ccms_redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ccms_csrf_check()) {
        $errors[] = 'Invalid session token. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $pass  = (string) ($_POST['password'] ?? '');

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email.';
        }
        if ($pass === '') {
            $errors[] = 'Password is required.';
        }

        if (!$errors) {
            $stmt = ccms_db()->prepare(
                'SELECT id, name, password_hash, is_active FROM users WHERE email = ? LIMIT 1'
            );
            $stmt->execute([$email]);
            $row = $stmt->fetch();

            if ($row && $row['is_active'] && password_verify($pass, $row['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $row['id'];
                ccms_flash('success', 'Welcome back, ' . $row['name'] . '!');
                ccms_redirect('dashboard.php');
            }
            $errors[] = 'Wrong email/password combination.';
            // small constant-ish delay to deter bruteforce
            usleep(250000);
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="auth-shell">
  <div class="card">
    <h4 class="text-center"><i class="bi bi-shield-lock"></i> Sign in</h4>
    <?php include __DIR__ . '/includes/errors.php'; ?>
    <form method="post" action="login.php" novalidate>
      <?= ccms_csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" autocomplete="email"
               value="<?= ccms_e($email) ?>" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" autocomplete="current-password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Sign in</button>
      <div class="text-center mt-3 small">
        <a href="signup.php">New here? Create an account →</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
