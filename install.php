<?php
require_once __DIR__ . '/config.php';

$count = (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $count === 0) {
    verify_csrf();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (!preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username)) {
        $message = 'Username must be 3–50 characters and use letters, numbers, dot, underscore, or hyphen.';
    } elseif (strlen($password) < 12) {
        $message = 'Use a password with at least 12 characters.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
        $message = 'Admin created. Delete install.php now, then sign in.';
        $count = 1;
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Initial setup</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="login-page"><main class="login-card"><h1>Initial setup</h1>
<?php if ($message): ?><p class="notice"><?= e($message) ?></p><?php endif; ?>
<?php if ($count > 0): ?><p>An administrator already exists. If setup is complete, delete <code>install.php</code> from your project folder.</p><a class="button" href="login.php">Go to login</a>
<?php else: ?><p>Create the first administrator account. Use a strong password you have not used elsewhere.</p>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Username<input name="username" required minlength="3" maxlength="50" autocomplete="username"></label><label>Password (12+ characters)<input type="password" name="password" required minlength="12" autocomplete="new-password"></label><button type="submit">Create administrator</button></form><?php endif; ?>
</main></body></html>
