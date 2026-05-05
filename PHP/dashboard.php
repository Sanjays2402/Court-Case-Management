<?php
require_once __DIR__ . '/config/auth.php';
$user = ccms_require_login();
$db   = ccms_db();

$page_title = 'Dashboard';
$active_nav = 'dashboard';

// --- Stats ---
$stmt = $db->prepare('SELECT
        COUNT(*) AS total,
        SUM(status = "open")        AS open_cnt,
        SUM(status = "in_progress") AS active_cnt,
        SUM(status IN ("closed","won","lost","dismissed")) AS closed_cnt,
        SUM(next_hearing IS NOT NULL AND next_hearing >= CURDATE()
            AND next_hearing <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)) AS upcoming_cnt
    FROM cases WHERE user_id = ?');
$stmt->execute([$user['id']]);
$stats = $stmt->fetch() ?: ['total'=>0,'open_cnt'=>0,'active_cnt'=>0,'closed_cnt'=>0,'upcoming_cnt'=>0];

// --- Upcoming hearings (next 14 days) ---
$stmt = $db->prepare('SELECT id, case_number, case_type, advocate_name, next_hearing, status, priority
    FROM cases
    WHERE user_id = ?
      AND next_hearing IS NOT NULL
      AND next_hearing >= CURDATE()
      AND next_hearing <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)
    ORDER BY next_hearing ASC
    LIMIT 5');
$stmt->execute([$user['id']]);
$upcoming = $stmt->fetchAll();

// --- Recent cases ---
$stmt = $db->prepare('SELECT id, case_number, case_type, advocate_name, status, priority, filed_on
    FROM cases WHERE user_id = ? ORDER BY created_at DESC LIMIT 5');
$stmt->execute([$user['id']]);
$recent = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-end mb-4">
  <div>
    <h2 class="mb-1">Hello, <?= ccms_e($user['name']) ?> 👋</h2>
    <p class="text-muted mb-0">Here's what's happening with your cases.</p>
  </div>
  <a href="addcase.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> File new case</a>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-sm-6">
    <div class="ccms-stat">
      <div class="ccms-stat-icon" style="background:#1d3557"><i class="bi bi-folder"></i></div>
      <div>
        <div class="ccms-stat-value"><?= (int)$stats['total'] ?></div>
        <div class="ccms-stat-label">Total cases</div>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="ccms-stat">
      <div class="ccms-stat-icon" style="background:#3b82f6"><i class="bi bi-folder2-open"></i></div>
      <div>
        <div class="ccms-stat-value"><?= (int)$stats['open_cnt'] ?></div>
        <div class="ccms-stat-label">Open</div>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="ccms-stat">
      <div class="ccms-stat-icon" style="background:#b45309"><i class="bi bi-arrow-repeat"></i></div>
      <div>
        <div class="ccms-stat-value"><?= (int)$stats['active_cnt'] ?></div>
        <div class="ccms-stat-label">In progress</div>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="ccms-stat">
      <div class="ccms-stat-icon" style="background:#15803d"><i class="bi bi-check2-circle"></i></div>
      <div>
        <div class="ccms-stat-value"><?= (int)$stats['closed_cnt'] ?></div>
        <div class="ccms-stat-label">Closed</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-calendar-event"></i> Upcoming hearings</strong>
        <span class="text-muted small">next 14 days</span>
      </div>
      <div class="card-body p-0">
        <?php if (!$upcoming): ?>
          <div class="empty-state">
            <i class="bi bi-calendar2-x"></i>
            <p class="mb-0 mt-2">No hearings scheduled.</p>
          </div>
        <?php else: ?>
          <table class="table table-hover mb-0">
            <thead><tr>
              <th>Date</th><th>Case #</th><th>Type</th><th>Advocate</th><th></th>
            </tr></thead>
            <tbody>
              <?php foreach ($upcoming as $c):
                $days = (strtotime($c['next_hearing']) - strtotime(date('Y-m-d'))) / 86400;
              ?>
                <tr>
                  <td>
                    <strong><?= ccms_e(date('M d', strtotime($c['next_hearing']))) ?></strong>
                    <div class="small text-muted">
                      <?= $days == 0 ? 'Today'
                        : ($days == 1 ? 'Tomorrow'
                        : 'in ' . (int)$days . ' days') ?>
                    </div>
                  </td>
                  <td><?= ccms_e($c['case_number']) ?></td>
                  <td><?= ccms_e($c['case_type']) ?></td>
                  <td><?= ccms_e($c['advocate_name']) ?></td>
                  <td><a href="case_view.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-primary">Open</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-clock-history"></i> Recent cases</strong>
        <a href="cases.php" class="small">View all →</a>
      </div>
      <div class="card-body p-0">
        <?php if (!$recent): ?>
          <div class="empty-state">
            <i class="bi bi-folder2"></i>
            <p class="mb-0 mt-2">No cases yet.</p>
            <a href="addcase.php" class="btn btn-sm btn-primary mt-3">File your first case</a>
          </div>
        <?php else: ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($recent as $c): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center"
                  style="background:transparent">
                <div>
                  <a href="case_view.php?id=<?= (int)$c['id'] ?>" class="fw-semibold text-decoration-none">
                    <?= ccms_e($c['case_number']) ?>
                  </a>
                  <div class="small text-muted">
                    <span class="priority-dot <?= ccms_e($c['priority']) ?>"></span>
                    <?= ccms_e($c['case_type']) ?> · <?= ccms_e($c['advocate_name']) ?>
                  </div>
                </div>
                <span class="status-pill <?= ccms_e($c['status']) ?>">
                  <?= ccms_e(str_replace('_', ' ', $c['status'])) ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
