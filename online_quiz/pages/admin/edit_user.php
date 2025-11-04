<?php
session_start();
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../classes/User.php';
$userModel = new User();

if (!isset($_GET['id'])) { header('Location: manage_users.php'); exit; }
$id = (int)$_GET['id'];
$user = $userModel->findById($id);
if (!$user) exit('Not found');

$errors = [];
$name = $user['name']; $email = $user['email']; $role = $user['role'];

$csrfPath = __DIR__ . '/../../includes/csrf.php';
if (file_exists($csrfPath)) require_once $csrfPath;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !csrf_check($_POST['csrf_token'])) die('Invalid CSRF token');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = in_array($_POST['role'] ?? 'student',['admin','teacher','student'])?$_POST['role']:'student';
    if ($name==='') $errors['name']='Required';
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors['email']='Valid email required';
    if (empty($errors)) {
        if ($userModel->updateUser($id,$name,$email,$role)) { header('Location: manage_users.php'); exit; } else $errors['general']='Failed';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit User</title>
<link rel="stylesheet" href="../../assets/css/edit_user.css">
</head>
<body>
<h1>Edit User</h1>
<?php if (!empty($errors['general'])): ?><p style="color:red"><?= htmlspecialchars($errors['general']) ?></p><?php endif; ?>
<form method="post" action="">
    <?= csrf_field() ?>
    <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"></label><br>
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"></label><br>
    <label>Role:
        <select name="role">
            <option value="student" <?= $role==='student'?'selected':'' ?>>Student</option>
            <option value="teacher" <?= $role==='teacher'?'selected':'' ?>>Teacher</option>
            <option value="admin" <?= $role==='admin'?'selected':'' ?>>Admin</option>
        </select>
    </label><br>
    <button type="submit">Save</button>
</form>
<p><a href="manage_users.php">Back</a></p>
</body>
</html>