<?php
session_start();
require_once __DIR__ . '/../classes/User.php';
$userModel = new User();

$errors = [];

// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    $email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required';
    if (empty($password)) $errors['password'] = 'Password is required';

    if (empty($errors)) {
        $user = $userModel->login($email, $password);
        if ($user) {
            // set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors['general'] = 'Invalid credentials';
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <!-- <link rel="stylesheet" href="../assets/css/auth.css"> -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="auth">
    <div class="auth-card">
        <h1>Sign in</h1>
        <p class="lead">Welcome back — sign in to continue.</p>
        <?php if (!empty($errors['general'])): ?><div class="error"><?= htmlspecialchars($errors['general']) ?></div><?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <div class="form-row">
                <label class="input">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
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
                <button class="btn-primary" type="submit">Sign in</button>
            </div>
        </form>
        <div class="auth-footer muted">No account? <a href="register.php">Create one</a></div>
    </div>
</body>
<script src="../assets/js/auth.js"></script>
</html>