<?php
require_once __DIR__ . '/../config/auth.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$user = ccms_user();
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthenticated']);
    exit;
}

$db = ccms_db();
$stmt = $db->prepare('SELECT
        COUNT(*) AS total,
        SUM(status = "open")        AS open_cnt,
        SUM(status = "in_progress") AS active_cnt,
        SUM(status IN ("closed","won","lost","dismissed")) AS closed_cnt,
        SUM(priority = "urgent" AND status NOT IN ("closed","won","lost","dismissed")) AS urgent_cnt
    FROM cases WHERE user_id = ?');
$stmt->execute([$user['id']]);
$row = $stmt->fetch();

echo json_encode([
    'total'    => (int)($row['total']      ?? 0),
    'open'     => (int)($row['open_cnt']   ?? 0),
    'active'   => (int)($row['active_cnt'] ?? 0),
    'closed'   => (int)($row['closed_cnt'] ?? 0),
    'urgent'   => (int)($row['urgent_cnt'] ?? 0),
]);
