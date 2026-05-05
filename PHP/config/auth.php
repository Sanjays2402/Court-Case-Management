<?php
/**
 * Session bootstrap, CSRF helpers, and auth guards.
 */

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    // Harden session cookies.
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function ccms_csrf_token(): string
{
    return $_SESSION['csrf'];
}

function ccms_csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(ccms_csrf_token(), ENT_QUOTES) . '">';
}

function ccms_csrf_check(): bool
{
    $sent = $_POST['_csrf'] ?? '';
    return is_string($sent) && hash_equals($_SESSION['csrf'] ?? '', $sent);
}

function ccms_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    static $cached = null;
    if ($cached && $cached['id'] === $_SESSION['user_id']) {
        return $cached;
    }
    $stmt = ccms_db()->prepare(
        'SELECT id, name, email, designation, role FROM users WHERE id = ? AND is_active = 1'
    );
    $stmt->execute([$_SESSION['user_id']]);
    $cached = $stmt->fetch() ?: null;
    return $cached;
}

function ccms_require_login(): array
{
    $u = ccms_user();
    if (!$u) {
        $_SESSION['flash_error'] = 'Please sign in to continue.';
        header('Location: login.php');
        exit;
    }
    return $u;
}

function ccms_redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function ccms_flash(string $type, ?string $msg = null)
{
    $key = 'flash_' . $type;
    if ($msg === null) {
        $v = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);
        return $v;
    }
    $_SESSION[$key] = $msg;
}

function ccms_e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}
