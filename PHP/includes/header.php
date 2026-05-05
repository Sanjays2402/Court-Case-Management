<?php
/**
 * Shared HTML head + navbar. Include after auth.php.
 *
 * Required before include:
 *   $page_title (string)
 *   $active_nav (string|null) — 'dashboard'|'cases'|'new'|'contact'
 */

require_once __DIR__ . '/../config/auth.php';
$user        = ccms_user();
$page_title  = $page_title  ?? 'CCMS';
$active_nav  = $active_nav  ?? null;
$flash_ok    = ccms_flash('success');
$flash_err   = ccms_flash('error');
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#1f2937">
  <title><?= ccms_e($page_title) ?> · CCMS</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-md sticky-top ccms-nav">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $user ? 'dashboard.php' : 'index.php' ?>">
      <span class="ccms-logo"><i class="bi bi-bank2"></i></span>
      <span class="fw-semibold">CCMS</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainnav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainnav">
      <ul class="navbar-nav me-auto">
        <?php if ($user): ?>
          <li class="nav-item">
            <a class="nav-link <?= $active_nav === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $active_nav === 'cases' ? 'active' : '' ?>" href="cases.php">
              <i class="bi bi-folder2-open"></i> Cases
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $active_nav === 'new' ? 'active' : '' ?>" href="addcase.php">
              <i class="bi bi-plus-circle"></i> File Case
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link <?= $active_nav === 'contact' ? 'active' : '' ?>" href="contact.php">
            <i class="bi bi-envelope"></i> Contact
          </a>
        </li>
      </ul>
      <ul class="navbar-nav align-items-md-center">
        <li class="nav-item me-md-2">
          <button id="theme-toggle" type="button" class="btn btn-sm btn-outline-secondary" aria-label="Toggle theme">
            <i class="bi bi-moon-stars"></i>
          </button>
        </li>
        <?php if ($user): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> <?= ccms_e($user['name']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><span class="dropdown-item-text small text-muted"><?= ccms_e($user['email']) ?></span></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sign out</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item">
            <a class="btn btn-primary btn-sm ms-md-2" href="signup.php">Sign up</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
  <?php if ($flash_ok): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle"></i> <?= ccms_e($flash_ok) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if ($flash_err): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle"></i> <?= ccms_e($flash_err) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
