<?php
require_once __DIR__ . '/config/auth.php';
$user = ccms_require_login();
$db   = ccms_db();

$page_title = 'My cases';
$active_nav = 'cases';

$valid_status = ['open','in_progress','adjourned','closed','dismissed','won','lost'];

$q       = trim($_GET['q'] ?? '');
$status  = $_GET['status'] ?? '';
$status  = in_array($status, $valid_status, true) ? $status : '';
$sort    = $_GET['sort'] ?? 'recent';
$page    = max(1, (int)($_GET['page'] ?? 1));
$per     = 15;
$offset  = ($page - 1) * $per;

$where  = ['user_id = ?'];
$params = [$user['id']];

if ($q !== '') {
    $where[]  = '(case_number LIKE ? OR case_type LIKE ? OR advocate_name LIKE ? OR description LIKE ?)';
    $like     = '%' . $q . '%';
    $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like;
}
if ($status !== '') {
    $where[]  = 'status = ?';
    $params[] = $status;
}
$wsql = 'WHERE ' . implode(' AND ', $where);

$order = 'created_at DESC';
switch ($sort) {
    case 'oldest':   $order = 'created_at ASC'; break;
    case 'hearing':  $order = 'COALESCE(next_hearing, "9999-12-31") ASC'; break;
    case 'priority': $order = "FIELD(priority,'urgent','high','medium','low'), created_at DESC"; break;
}

// total
$stmt = $db->prepare("SELECT COUNT(*) FROM cases $wsql");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();
$pages = max(1, (int)ceil($total / $per));

// rows
$stmt = $db->prepare(
    "SELECT id, case_number, case_type, advocate_name, status, priority, filed_on, next_hearing
       FROM cases $wsql ORDER BY $order LIMIT $per OFFSET $offset"
);
$stmt->execute($params);
$rows = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div>
    <h2 class="mb-1">My cases</h2>
    <p class="text-muted mb-0"><?= $total ?> total</p>
  </div>
  <a href="addcase.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> File new case</a>
</div>

<form class="card p-3 mb-3" method="get" action="cases.php">
  <div class="row g-2 align-items-end">
    <div class="col-md-5">
      <label class="form-label">Search</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="search" name="q" class="form-control" value="<?= ccms_e($q) ?>"
               placeholder="Case number, type, advocate, description...">
      </div>
    </div>
    <div class="col-md-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        <option value="">All</option>
        <?php foreach ($valid_status as $s): ?>
          <option value="<?= $s ?>" <?= $s === $status ? 'selected' : '' ?>>
            <?= ucfirst(str_replace('_',' ', $s)) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Sort</label>
      <select name="sort" class="form-select">
        <option value="recent"   <?= $sort==='recent'?'selected':'' ?>>Most recent</option>
        <option value="oldest"   <?= $sort==='oldest'?'selected':'' ?>>Oldest first</option>
        <option value="hearing"  <?= $sort==='hearing'?'selected':'' ?>>Next hearing</option>
        <option value="priority" <?= $sort==='priority'?'selected':'' ?>>Priority</option>
      </select>
    </div>
    <div class="col-md-2 d-grid">
      <button class="btn btn-outline-primary" type="submit"><i class="bi bi-funnel"></i> Apply</button>
    </div>
  </div>
</form>

<div class="card">
  <?php if (!$rows): ?>
    <div class="empty-state">
      <i class="bi bi-folder2"></i>
      <p class="mt-2 mb-0">
        <?= $q || $status ? 'No cases match your filter.' : 'You haven\'t filed any cases yet.' ?>
      </p>
      <?php if (!$q && !$status): ?>
        <a href="addcase.php" class="btn btn-primary mt-3">File your first case</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr>
          <th>Case #</th><th>Type</th><th>Advocate</th>
          <th>Status</th><th>Filed</th><th>Next hearing</th><th></th>
        </tr></thead>
        <tbody>
          <?php foreach ($rows as $c): ?>
            <tr>
              <td>
                <span class="priority-dot <?= ccms_e($c['priority']) ?>"
                      title="<?= ccms_e(ucfirst($c['priority'])) ?> priority"></span>
                <a href="case_view.php?id=<?= (int)$c['id'] ?>" class="fw-semibold text-decoration-none">
                  <?= ccms_e($c['case_number']) ?>
                </a>
              </td>
              <td><?= ccms_e($c['case_type']) ?></td>
              <td><?= ccms_e($c['advocate_name']) ?></td>
              <td><span class="status-pill <?= ccms_e($c['status']) ?>">
                <?= ccms_e(str_replace('_',' ', $c['status'])) ?>
              </span></td>
              <td class="small text-muted"><?= ccms_e(date('M j, Y', strtotime($c['filed_on']))) ?></td>
              <td class="small">
                <?= $c['next_hearing'] ? ccms_e(date('M j, Y', strtotime($c['next_hearing']))) : '<span class="text-muted">—</span>' ?>
              </td>
              <td class="text-end">
                <a href="case_view.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php if ($pages > 1):
  $base = '?q=' . urlencode($q) . '&status=' . urlencode($status) . '&sort=' . urlencode($sort);
?>
<nav class="mt-3">
  <ul class="pagination justify-content-center">
    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $base ?>&page=<?= $page-1 ?>">Previous</a>
    </li>
    <?php for ($i=1; $i <= $pages; $i++): ?>
      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
        <a class="page-link" href="<?= $base ?>&page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
    <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $base ?>&page=<?= $page+1 ?>">Next</a>
    </li>
  </ul>
</nav>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
