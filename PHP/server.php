<?php
/**
 * Legacy compatibility shim. The original server.php contained mixed
 * "controller" code (login/signup/case insert) plus DB connection. The
 * codebase has since moved to PDO + dedicated controllers in each page,
 * so this file is now a no-op include guard for any old `include('server.php')`
 * lines that may still exist in third-party copies.
 */
require_once __DIR__ . '/config/auth.php';
