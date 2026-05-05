<?php
require_once __DIR__ . '/config/auth.php';
$user = ccms_require_login();
$db   = ccms_db();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) ccms_redirect('cases.php');

$stmt = $db->prepare('SELECT * FROM cases WHERE id = ? AND user_id = ? LIMIT 1');
$stmt->execute([$id, $user['id']]);
$case = $stmt->fetch();
if (!$case) {
    ccms_flash('error', 'Case not found.');
    ccms_redirect('cases.php');
}

$errors = [];
$f = $case;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ccms_csrf_check()) {
        $errors[] = 'Invalid session token.';
    } else {
        $f['case_type']     = trim($_POST['case_type']     ?? '');
        $f['case_number']   = trim($_POST['case_number']   ?? '');
        $f['fir_number']    = trim($_POST['fir_number']    ?? '');
        $f['advocate_name'] = trim($_POST['advocate_name'] ?? '');
        $f['advocate_id']   = trim($_POST['advocate_id']   ?? '');
        $f['filed_on']      = trim($_POST['filed_on']      ?? '');
        $f['next_hearing']  = trim($_POST['next_hearing']  ?? '');
        $f['description']   = trim($_POST['description']   ?? '');
        $f['priority']      = $_POST['priority'] ?? 'medium';
        $f['status']        = $_POST['status']   ?? 'open';

        if ($f['case_type']     === '') $errors[] = 'Case type is required.';
        if ($f['case_number']   === '') $errors[] = 'Case number is required.';
        if ($f['advocate_name'] === '') $errors[] = 'Advocate name is required.';
        if ($f['filed_on']      === '') $errors[] = 'Filed-on date is required.';
        if (!in_array($f['priority'], ['low','medium','high','urgent'], true)) $f['priority'] = 'medium';
        if (!in_array($f['status'], ['open','in_progress','adjourned','closed','dismissed','won','lost'], true)) $f['status'] = 'open';

        // dup check (different row, same case_number)
        if (!$errors) {
            $stmt = $db->prepare('SELECT id FROM cases WHERE case_number = ? AND id <> ? LIMIT 1');
            $stmt->execute([$f['case_number'], $id]);
            if ($stmt->fetch()) $errors[] = 'Another case with this case number already exists.';
        }

        if (!$errors) {
            $stmt = $db->prepare(
                'UPDATE cases SET case_type = ?, case_number = ?, fir_number = ?, advocate_name = ?,
                                  advocate_id = ?, filed_on = ?, next_hearing = ?, description = ?,
                                  priority = ?, status = ?
                  WHERE id = ? AND user_id = ?'
            );
            $stmt->execute([
                $f['case_type'], $f['case_number'], $f['fir_number'] ?: null,
                $f['advocate_name'], $f['advocate_id'] ?: null, $f['filed_on'],
                $f['next_hearing'] ?: null, $f['description'] ?: null,
                $f['priority'], $f['status'],
                $id, $user['id'],
            ]);
            ccms_flash('success', 'Case updated.');
            ccms_redirect('case_view.php?id=' . $id);
        }
    }
}

$page_title = 'Edit ' . $case['case_number'];
$active_nav = 'cases';
include __DIR__ . '/includes/header.php';
?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb small">
    <li class="breadcrumb-item"><a href="cases.php">Cases</a></li>
    <li class="breadcrumb-item"><a href="case_view.php?id=<?= $id ?>"><?= ccms_e($case['case_number']) ?></a></li>
    <li class="breadcrumb-item active">Edit</li>
  </ol>
</nav>

<h2 class="mb-3">Edit case</h2>

<div class="card">
  <div class="card-body">
    <?php include __DIR__ . '/includes/errors.php'; ?>
    <form method="post">
      <?= ccms_csrf_field() ?>
      <input type="hidden" name="id" value="<?= $id ?>">
      <?php include __DIR__ . '/includes/case_form_fields.php'; ?>
      <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="case_view.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
        <button class="btn btn-primary"><i class="bi bi-save"></i> Save changes</button>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
