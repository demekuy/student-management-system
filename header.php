<?php
require_once __DIR__ . '/config.php';
require_login();
$page_title = $page_title ?? 'Dashboard';
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($page_title) ?> | SMS</title><link rel="stylesheet" href="assets/style.css"></head><body>
<div class="app-shell"><aside class="sidebar"><a class="logo" href="index.php"><span class="logo-icon">S</span><span>Student<span class="logo-light">Desk</span></span></a><p class="nav-label">MAIN MENU</p><nav><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">▦ &nbsp; Dashboard</a><a href="students.php" class="<?= in_array(basename($_SERVER['PHP_SELF']), ['students.php','student_form.php'], true) ? 'active' : '' ?>">♙ &nbsp; Students</a></nav><div class="sidebar-bottom"><div class="avatar"><?= e(strtoupper(substr($_SESSION['admin_username'] ?? 'A',0,1))) ?></div><div class="admin-name"><?= e($_SESSION['admin_username'] ?? 'Admin') ?><small>Administrator</small></div><form action="logout.php" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button class="logout-button" type="submit" title="Sign out">↪</button></form></div></aside>
<main class="main-content"><header class="topbar"><div><p class="eyebrow">STUDENT ADMINISTRATION</p><h1><?= e($page_title) ?></h1></div><div class="topbar-right"><span class="status-dot"></span> System online <span class="top-avatar"><?= e(strtoupper(substr($_SESSION['admin_username'] ?? 'A',0,1))) ?></span></div></header>
