<?php
session_start();
require_once __DIR__ . '/../classes/User.php';
$userModel = new User();

$errors = [];
$values = ['name'=>'','email'=>''];

// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    $values['name'] = trim(htmlspecialchars($_POST['name'] ?? ''));
    $values['email'] = trim(htmlspecialchars($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $role = in_array($_POST['role'] ?? 'student', ['student','teacher','admin']) ? $_POST['role'] : 'student';

    if ($values['name'] === '') $errors['name'] = 'Name is required';
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required';
    if (strlen($password) < 6) $errors['password'] = 'Password must be 6+ characters';

    if (empty($errors)) {
        if ($userModel->existsByEmail($values['email'])) {
            $errors['email'] = 'Email already registered';
        } else {
            $uid = $userModel->register($values['name'], $values['email'], $password, $role);
            if ($uid) {
                $_SESSION['user_id'] = $uid;
                header('Location: login.php');
                exit;
            } else {
                $errors['general'] = 'Registration failed';
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <!-- <link rel="stylesheet" href="../assets/css/auth.css"> -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="auth">
    <div class="auth-card">
        <h1>Create account</h1>
        <p class="lead">Sign up to join quizzes — choose a role below.</p>
        <?php if (!empty($errors['general'])): ?><div class="error"><?= htmlspecialchars($errors['general']) ?></div><?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <div class="form-row">
                <label class="input">Full name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($values['name']) ?>" required>
                <div class="muted"><?= $errors['name'] ?? '' ?></div>
            </div>
            <div class="form-row">
                <label class="input">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($values['email']) ?>" required>
                <div class="muted"><?= $errors['email'] ?? '' ?></div>
            </div>
            <div class="form-row">
                <label class="input">Password</label>
                <input type="password" name="password" class="auth-password" required>
                <div class="muted"><?= $errors['password'] ?? '' ?></div>
            </div>
            <div class="form-row">
                <label class="muted"><input type="checkbox" class="show-password-toggle"> Show password</label>
            </div>
            <div class="form-row">
                <label class="input">Role</label>
                <select name="role">
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>
            <div class="form-row">
                <button class="btn-primary" type="submit">Create account</button>
            </div>
        </form>
        <div class="auth-footer muted">Already have an account? <a href="login.php">Sign in</a></div>
    </div>
</body>
<script src="../assets/js/auth.js"></script>
</html>