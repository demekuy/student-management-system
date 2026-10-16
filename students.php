<?php
require_once __DIR__ . '/config.php';
require_login();
$q = trim((string)($_GET['q'] ?? ''));
if ($q !== '') {
    $like = '%'.$q.'%';
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_number LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR department LIKE ? OR email LIKE ? ORDER BY id DESC");
    $stmt->execute([$like,$like,$like,$like,$like]);
    $students = $stmt->fetchAll();
} else {
    $students = $pdo->query('SELECT * FROM students ORDER BY id DESC')->fetchAll();
}
$page_title = 'Student records';
require __DIR__ . '/header.php';
?>
<section class="panel"><div class="panel-heading"><div><h2>All students</h2><p><?= count($students) ?> record(s)<?= $q !== '' ? ' matching “'.e($q).'”' : '' ?></p></div><a class="button" href="student_form.php">＋ Register student</a></div>
<form class="search-form" method="get"><input type="search" name="q" placeholder="Search name, ID, department or email…" value="<?= e($q) ?>"><button type="submit" class="button">Search</button><?php if ($q !== ''): ?><a class="button button-outline" href="students.php">Clear</a><?php endif; ?></form>
<?php if (!$students): ?><div class="empty-state"><div class="empty-icon">⌕</div><h3>No matching students</h3><p>Try a different search or register a new student.</p></div>
<?php else: ?><div class="table-wrap"><table><thead><tr><th>Student</th><th>Student ID</th><th>Gender</th><th>Department</th><th>Year</th><th>Contact</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($students as $s): ?><tr><td><div class="person"><span class="person-avatar"><?= e(strtoupper(substr($s['first_name'],0,1).substr($s['last_name'],0,1))) ?></span><div><strong><?= e($s['first_name'].' '.$s['last_name']) ?></strong><small class="subline"><?= e($s['email'] ?: 'No email') ?></small></div></div></td><td><?= e($s['student_number']) ?></td><td><?= e($s['gender']) ?></td><td><?= e($s['department']) ?></td><td><span class="year-pill">Year <?= (int)$s['year_level'] ?></span></td><td><?= e($s['phone'] ?: '—') ?></td><td><div class="actions"><a class="action-link" href="student_form.php?id=<?= (int)$s['id'] ?>">Edit</a><form method="post" action="student_delete.php" onsubmit="return confirm('Delete this student record? This cannot be undone.');"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$s['id'] ?>"><button class="action-delete" type="submit">Delete</button></form></div></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require __DIR__ . '/footer.php'; ?>
