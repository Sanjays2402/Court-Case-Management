<?php
require_once __DIR__ . '/config/auth.php';
$user = ccms_require_login();
$db   = ccms_db();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !ccms_csrf_check()) {
    ccms_flash('error', 'Invalid request.');
    ccms_redirect('cases.php');
}

$id = (int) ($_POST['id'] ?? 0);
$stmt = $db->prepare('DELETE FROM cases WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $user['id']]);

ccms_flash('success', $stmt->rowCount() ? 'Case deleted.' : 'Case not found.');
ccms_redirect('cases.php');
