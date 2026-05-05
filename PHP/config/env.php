<?php
/**
 * Tiny .env loader. Loads ../.env (relative to project root) into $_ENV / getenv().
 * Falls back silently if file is missing.
 */

if (!function_exists('ccms_load_env')) {
    function ccms_load_env(string $path): void
    {
        if (!is_readable($path)) {
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }
            [$k, $v] = $parts;
            $k = trim($k);
            $v = trim($v);
            // strip wrapping quotes
            if (
                (str_starts_with($v, '"') && str_ends_with($v, '"')) ||
                (str_starts_with($v, "'") && str_ends_with($v, "'"))
            ) {
                $v = substr($v, 1, -1);
            }
            if (getenv($k) === false) {
                putenv("$k=$v");
                $_ENV[$k] = $v;
            }
        }
    }

    function ccms_env(string $key, $default = null)
    {
        $v = getenv($key);
        return $v === false ? $default : $v;
    }
}

ccms_load_env(__DIR__ . '/../../.env');
