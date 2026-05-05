<?php
require_once __DIR__ . '/config/auth.php';
$user = ccms_require_login();
$db   = ccms_db();

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) ccms_redirect('cases.php');

// --- POST: add note OR update status ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ccms_csrf_check()) {
        ccms_flash('error', 'Invalid session token.');
        ccms_redirect('case_view.php?id=' . $id);
    }

    if (isset($_POST['add_note'])) {
        $body = trim($_POST['body'] ?? '');
        if ($body === '') {
            ccms_flash('error', 'Note cannot be empty.');
        } else {
            $stmt = $db->prepare(
                'INSERT INTO case_notes (case_id, user_id, body)
                 SELECT id, ?, ? FROM cases WHERE id = ? AND user_id = ?'
            );
            $stmt->execute([$user['id'], $body, $id, $user['id']]);
            ccms_flash('success', 'Note added.');
        }
    } elseif (isset($_POST['update_status'])) {
        $valid = ['open','in_progress','adjourned','closed','dismissed','won','lost'];
        $new   = $_POST['status'] ?? '';
        if (in_array($new, $valid, true)) {
            $stmt = $db->prepare('UPDATE cases SET status = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([$new, $id, $user['id']]);
            ccms_flash('success', 'Status updated.');
        }
    }
    ccms_redirect('case_view.php?id=' . $id);
}

// --- Load case ---
$stmt = $db->prepare(
    'SELECT * FROM cases WHERE id = ? AND user_id = ? LIMIT 1'
);
$stmt->execute([$id, $user['id']]);
$case = $stmt->fetch();

if (!$case) {
    ccms_flash('error', 'Case not found or access denied.');
    ccms_redirect('cases.php');
}

// notes
$nstmt = $db->prepare(
    'SELECT n.body, n.created_at, u.name AS author
       FROM case_notes n
       JOIN users u ON u.id = n.user_id
      WHERE n.case_id = ?
      ORDER BY n.created_at DESC'
);
$nstmt->execute([$id]);
$notes = $nstmt->fetchAll();

$page_title = 'Case ' . $case['case_number'];
$active_nav = 'cases';
include __DIR__ . '/includes/header.php';

$valid_status = ['open','in_progress','adjourned','closed','dismissed','won','lost'];
?>

<nav aria-label="breadcrumb" class="mb-3">
  <ol class="breadcrumb small">
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="cases.php">Cases</a></li>
    <li class="breadcrumb-item active"><?= ccms_e($case['case_number']) ?></li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
  <div>
    <h2 class="mb-1">
      <span class="priority-dot <?= ccms_e($case['priority']) ?>"></span>
      <?= ccms_e($case['case_number']) ?>
    </h2>
    <div class="d-flex align-items-center gap-2">
      <span class="status-pill <?= ccms_e($case['status']) ?>">
        <?= ccms_e(str_replace('_',' ', $case['status'])) ?>
      </span>
      <span class="text-muted">·</span>
      <span class="text-muted"><?= ccms_e($case['case_type']) ?></span>
    </div>
  </div>
  <div class="d-flex gap-2">
    <a href="case_edit.php?id=<?= (int)$case['id'] ?>" class="btn btn-outline-primary">
      <i class="bi bi-pencil"></i> Edit
    </a>
    <form method="post" action="case_delete.php" class="d-inline">
      <?= ccms_csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$case['id'] ?>">
      <button class="btn btn-outline-danger"
              data-confirm="Delete this case permanently? Notes and hearings will be removed.">
        <i class="bi bi-trash"></i> Delete
      </button>
    </form>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="card mb-4">
      <div class="card-header"><strong>Case details</strong></div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-sm-6">
            <div class="case-detail-key">Case type</div>
            <div class="case-detail-value"><?= ccms_e($case['case_type']) ?></div>
          </div>
          <div class="col-sm-6">
            <div class="case-detail-key">FIR number</div>
            <div class="case-detail-value"><?= ccms_e($case['fir_number'] ?: '—') ?></div>
          </div>
          <div class="col-sm-6">
            <div class="case-detail-key">Advocate</div>
            <div class="case-detail-value"><?= ccms_e($case['advocate_name']) ?></div>
          </div>
          <div class="col-sm-6">
            <div class="case-detail-key">Advocate ID</div>
            <div class="case-detail-value"><?= ccms_e($case['advocate_id'] ?: '—') ?></div>
          </div>
          <div class="col-sm-6">
            <div class="case-detail-key">Filed on</div>
            <div class="case-detail-value"><?= ccms_e(date('F j, Y', strtotime($case['filed_on']))) ?></div>
          </div>
          <div class="col-sm-6">
            <div class="case-detail-key">Next hearing</div>
            <div class="case-detail-value">
              <?= $case['next_hearing'] ? ccms_e(date('F j, Y', strtotime($case['next_hearing']))) : '—' ?>
            </div>
          </div>
          <div class="col-12">
            <div class="case-detail-key">Description</div>
            <div class="case-detail-value">
              <?= nl2br(ccms_e($case['description'] ?: '—')) ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><strong><i class="bi bi-chat-left-text"></i> Notes &amp; timeline</strong></div>
      <div class="card-body">
        <form method="post" class="mb-3">
          <?= ccms_csrf_field() ?>
          <input type="hidden" name="add_note" value="1">
          <textarea name="body" class="form-control mb-2" rows="2"
                    placeholder="Add a note about hearings, calls, or filings..." required></textarea>
          <button class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> Add note</button>
        </form>

        <?php if (!$notes): ?>
          <p class="text-muted mb-0 small">No notes yet. Add the first one above.</p>
        <?php else: ?>
          <ul class="list-unstyled mb-0">
            <?php foreach ($notes as $n): ?>
              <li class="border-start ps-3 py-2 mb-2" style="border-color:var(--ccms-border)!important; border-width:2px!important">
                <div class="small text-muted mb-1">
                  <strong><?= ccms_e($n['author']) ?></strong> ·
                  <?= ccms_e(date('M j, Y g:i A', strtotime($n['created_at']))) ?>
                </div>
                <div><?= nl2br(ccms_e($n['body'])) ?></div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card">
      <div class="card-header"><strong>Update status</strong></div>
      <div class="card-body">
        <form method="post">
          <?= ccms_csrf_field() ?>
          <input type="hidden" name="update_status" value="1">
          <select name="status" class="form-select mb-2">
            <?php foreach ($valid_status as $s): ?>
              <option value="<?= $s ?>" <?= $s === $case['status'] ? 'selected' : '' ?>>
                <?= ucfirst(str_replace('_',' ', $s)) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-primary w-100"><i class="bi bi-check2"></i> Save status</button>
        </form>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-header"><strong>Metadata</strong></div>
      <div class="card-body small">
        <div class="d-flex justify-content-between py-1">
          <span class="text-muted">Created</span>
          <span><?= ccms_e(date('M j, Y g:i A', strtotime($case['created_at']))) ?></span>
        </div>
        <div class="d-flex justify-content-between py-1">
          <span class="text-muted">Last updated</span>
          <span><?= ccms_e(date('M j, Y g:i A', strtotime($case['updated_at']))) ?></span>
        </div>
        <div class="d-flex justify-content-between py-1">
          <span class="text-muted">Priority</span>
          <span><?= ccms_e(ucfirst($case['priority'])) ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
