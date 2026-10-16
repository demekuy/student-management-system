<?php
require_once __DIR__ . '/config.php';
require_login();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: null;
$is_edit = $id !== null;
$data = ['student_number'=>'','first_name'=>'','last_name'=>'','gender'=>'','email'=>'','phone'=>'','department'=>'','year_level'=>'1'];
if ($is_edit) {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if (!$existing) { http_response_code(404); exit('Student not found. <a href="students.php">Return</a>'); }
    $data = $existing;
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach (['student_number','first_name','last_name','gender','email','phone','department','year_level'] as $key) {
        $data[$key] = trim((string)($_POST[$key] ?? ''));
    }
    if ($data['student_number'] === '' || strlen($data['student_number']) > 30) $errors[] = 'Student ID is required (maximum 30 characters).';
    if ($data['first_name'] === '' || strlen($data['first_name']) > 80) $errors[] = 'First name is required (maximum 80 characters).';
    if ($data['last_name'] === '' || strlen($data['last_name']) > 80) $errors[] = 'Last name is required (maximum 80 characters).';
    if (!in_array($data['gender'], ['Male','Female','Other'], true)) $errors[] = 'Choose a valid gender.';
    if ($data['email'] !== '' && (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || strlen($data['email']) > 150)) $errors[] = 'Enter a valid email address (maximum 150 characters).';
    if (strlen($data['phone']) > 30) $errors[] = 'Phone number must be 30 characters or fewer.';
    if ($data['department'] === '' || strlen($data['department']) > 100) $errors[] = 'Department is required (maximum 100 characters).';
    if (!in_array($data['year_level'], ['1','2','3','4','5','6'], true)) $errors[] = 'Choose a year level from 1 to 6.';
    if (!$errors) {
        try {
            if ($is_edit) {
                $stmt = $pdo->prepare('UPDATE students SET student_number=?, first_name=?, last_name=?, gender=?, email=?, phone=?, department=?, year_level=? WHERE id=?');
                $stmt->execute([$data['student_number'],$data['first_name'],$data['last_name'],$data['gender'],$data['email'] ?: null,$data['phone'] ?: null,$data['department'],(int)$data['year_level'],$id]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO students (student_number,first_name,last_name,gender,email,phone,department,year_level) VALUES (?,?,?,?,?,?,?,?)');
                $stmt->execute([$data['student_number'],$data['first_name'],$data['last_name'],$data['gender'],$data['email'] ?: null,$data['phone'] ?: null,$data['department'],(int)$data['year_level']]);
            }
            header('Location: students.php'); exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') $errors[] = 'That student ID is already registered. Use a unique ID.';
            else $errors[] = 'Could not save the record. Check the database configuration and try again.';
        }
    }
}
$page_title = $is_edit ? 'Edit student' : 'Register student';
require __DIR__ . '/header.php';
?>
<section class="panel form-panel"><div class="panel-heading"><div><h2><?= $is_edit ? 'Update student details' : 'New student registration' ?></h2><p>Fields marked * are required.</p></div><a class="button button-outline" href="students.php">← Back to records</a></div>
<?php if ($errors): ?><div class="error-list"><strong>Please fix the following:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" class="student-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Student ID *<input name="student_number" required maxlength="30" value="<?= e($data['student_number']) ?>" placeholder="e.g. STU-2026-001"></label>
<label>First name *<input name="first_name" required maxlength="80" value="<?= e($data['first_name']) ?>"></label>
<label>Last name *<input name="last_name" required maxlength="80" value="<?= e($data['last_name']) ?>"></label>
<label>Gender *<select name="gender" required><option value="">Select gender</option><?php foreach (['Male','Female','Other'] as $g): ?><option value="<?= e($g) ?>" <?= $data['gender']===$g?'selected':'' ?>><?= e($g) ?></option><?php endforeach; ?></select></label>
<label>Email<input type="email" name="email" maxlength="150" value="<?= e($data['email']) ?>" placeholder="student@example.com"></label>
<label>Phone number<input name="phone" maxlength="30" value="<?= e($data['phone']) ?>" placeholder="+251…"></label>
<label>Department *<input name="department" required maxlength="100" value="<?= e($data['department']) ?>" placeholder="e.g. Information Technology"></label>
<label>Year of study *<select name="year_level" required><?php for ($i=1;$i<=6;$i++): ?><option value="<?= $i ?>" <?= (string)$data['year_level']===(string)$i?'selected':'' ?>>Year <?= $i ?></option><?php endfor; ?></select></label>
<div class="form-actions"><button type="submit"><?= $is_edit ? 'Save changes' : 'Register student' ?></button><a class="button button-outline" href="students.php">Cancel</a></div>
</form></section>
<?php require __DIR__ . '/footer.php'; ?>
