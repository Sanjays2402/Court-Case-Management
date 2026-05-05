<?php
require_once __DIR__ . '/env.php';

/**
 * PDO connection singleton. Uses prepared statements everywhere.
 */
function ccms_db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = ccms_env('DB_HOST', 'localhost');
    $port = ccms_env('DB_PORT', '3306');
    $name = ccms_env('DB_NAME', 'ccms');
    $user = ccms_env('DB_USER', 'root');
    $pass = ccms_env('DB_PASS', '');

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // Don't leak credentials. Log full error, show generic.
        error_log('[CCMS] DB connect failed: ' . $e->getMessage());
        http_response_code(500);
        die('Database unavailable. Please try again later.');
    }
    return $pdo;
}
