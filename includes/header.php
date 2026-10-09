<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$prefix = $asset_prefix ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? "Portfolio Showcase" ?> - Portfolio Showcase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $prefix ?>css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= $prefix ?>index.php">
        <i class="bi bi-mortarboard-fill text-warning"></i> Portfolio Showcase
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "student"): ?>
            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>student/dashboard.php"><i class="bi bi-house"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>student/submit.php"><i class="bi bi-upload"></i> Submit Project</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>student/my_submissions.php"><i class="bi bi-folder"></i> My Portfolio</a></li>
        <?php elseif (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>admin/dashboard.php"><i class="bi bi-speedometer2"></i> Admin Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>admin/categories.php"><i class="bi bi-tags"></i> Categories</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>admin/submissions.php"><i class="bi bi-list-task"></i> All Submissions</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION["user_id"])): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-info" href="#" id="userDrop" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION["full_name"]) ?> (<?= ucfirst($_SESSION["role"]) ?>)
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item text-danger" href="<?= $prefix ?>logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>register.php"><i class="bi bi-person-plus"></i> Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">