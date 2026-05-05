<?php
require_once __DIR__ . '/config/auth.php';
$user = ccms_require_login();
$db   = ccms_db();

$page_title = 'File new case';
$active_nav = 'new';

$errors = [];
$f = [
    'case_type'     => '',
    'case_number'   => '',
    'fir_number'    => '',
    'advocate_name' => '',
    'advocate_id'   => '',
    'filed_on'      => date('Y-m-d'),
    'next_hearing'  => '',
    'description'   => '',
    'priority'      => 'medium',
    'status'        => 'open',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ccms_csrf_check()) {
        $errors[] = 'Invalid session token. Please retry.';
    } else {
        foreach (array_keys($f) as $k) {
            $f[$k] = trim($_POST[$k] ?? $f[$k]);
        }
        if ($f['case_type']     === '') $errors[] = 'Case type is required.';
        if ($f['case_number']   === '') $errors[] = 'Case number is required.';
        if ($f['advocate_name'] === '') $errors[] = 'Advocate name is required.';
        if ($f['filed_on']      === '') $errors[] = 'Filed-on date is required.';

        if (!in_array($f['priority'], ['low','medium','high','urgent'], true)) $f['priority'] = 'medium';
        if (!in_array($f['status'], ['open','in_progress','adjourned','closed','dismissed','won','lost'], true)) {
            $f['status'] = 'open';
        }

        if (!$errors) {
            $stmt = $db->prepare('SELECT id FROM cases WHERE case_number = ? LIMIT 1');
            $stmt->execute([$f['case_number']]);
            if ($stmt->fetch()) {
                $errors[] = 'A case with this number already exists.';
            }
        }
        if (!$errors) {
            $stmt = $db->prepare(
                'INSERT INTO cases
                  (user_id, case_type, case_number, fir_number, advocate_name, advocate_id,
                   filed_on, next_hearing, description, priority, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $user['id'],
                $f['case_type'], $f['case_number'], $f['fir_number'] ?: null,
                $f['advocate_name'], $f['advocate_id'] ?: null,
                $f['filed_on'], $f['next_hearing'] ?: null,
                $f['description'] ?: null,
                $f['priority'], $f['status'],
            ]);
            ccms_flash('success', 'Case ' . $f['case_number'] . ' filed successfully.');
            ccms_redirect('case_view.php?id=' . $db->lastInsertId());
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb small">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="cases.php">Cases</a></li>
    <li class="breadcrumb-item active">File new case</li>
  </ol>
</nav>

<h2 class="mb-3"><i class="bi bi-plus-circle"></i> File a new case</h2>

<div class="card">
  <div class="card-body">
    <?php include __DIR__ . '/includes/errors.php'; ?>
    <form method="post" novalidate>
      <?= ccms_csrf_field() ?>
      <?php include __DIR__ . '/includes/case_form_fields.php'; ?>
      <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="cases.php" class="btn btn-outline-secondary">Cancel</a>
        <button class="btn btn-primary"><i class="bi bi-check2"></i> Register case</button>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
