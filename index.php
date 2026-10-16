<?php
require_once __DIR__ . '/config.php';
require_login();
$total = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$departments = (int)$pdo->query('SELECT COUNT(DISTINCT department) FROM students')->fetchColumn();
$year1 = (int)$pdo->query('SELECT COUNT(*) FROM students WHERE year_level = 1')->fetchColumn();
$recent = $pdo->query('SELECT student_number, first_name, last_name, department, year_level, created_at FROM students ORDER BY created_at DESC, id DESC LIMIT 6')->fetchAll();
$page_title = 'Dashboard';
require __DIR__ . '/header.php';
?>
<section class="welcome"><div><p class="eyebrow light">OVERVIEW</p><h2>Welcome back, <?= e($_SESSION['admin_username'] ?? 'Admin') ?> 👋</h2><p>Here is what is happening with your student records.</p></div><a class="button button-white" href="student_form.php">＋ Add student</a></section>
<section class="stats-grid">
<article class="stat-card"><div class="stat-icon purple">♙</div><p>Total students</p><strong><?= $total ?></strong><small>Registered student records</small></article>
<article class="stat-card"><div class="stat-icon blue">▤</div><p>Departments</p><strong><?= $departments ?></strong><small>Distinct departments</small></article>
<article class="stat-card"><div class="stat-icon orange">◎</div><p>First-year students</p><strong><?= $year1 ?></strong><small>Year level 1</small></article>
</section>
<section class="panel"><div class="panel-heading"><div><h2>Recently added students</h2><p>Latest records in the system</p></div><a class="text-link" href="students.php">View all →</a></div>
<?php if (!$recent): ?><div class="empty-state"><div class="empty-icon">♙</div><h3>No students yet</h3><p>Add your first student to start building your records.</p><a class="button" href="student_form.php">Add first student</a></div>
<?php else: ?><div class="table-wrap"><table><thead><tr><th>Student</th><th>Student ID</th><th>Department</th><th>Year</th><th>Added</th></tr></thead><tbody>
<?php foreach ($recent as $s): ?><tr><td><div class="person"><span class="person-avatar"><?= e(strtoupper(substr($s['first_name'],0,1).substr($s['last_name'],0,1))) ?></span><strong><?= e($s['first_name'].' '.$s['last_name']) ?></strong></div></td><td><?= e($s['student_number']) ?></td><td><?= e($s['department']) ?></td><td><span class="year-pill">Year <?= (int)$s['year_level'] ?></span></td><td><?= e(date('M j, Y', strtotime($s['created_at']))) ?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require __DIR__ . '/footer.php'; ?>
