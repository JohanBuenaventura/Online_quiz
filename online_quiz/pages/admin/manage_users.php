<?php
session_start();
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../classes/User.php';
$userModel = new User();
$users = $userModel->getAllUsers();
// CSRF helper
$csrfPath = __DIR__ . '/../../includes/csrf.php';
if (file_exists($csrfPath)) require_once $csrfPath;
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manage Users</title>
<link rel="stylesheet" href="../../assets/css/manage_users.css">

</head>
<body>
<h1>All Users</h1>
<p><a href="create_user.php">Create User</a></p>
<table border="1">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= (int)$u['id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td><?= htmlspecialchars($u['created_at']) ?></td>
            <td>
                <a href="edit_user.php?id=<?= (int)$u['id'] ?>">Edit</a> |
                <form method="post" action="delete_user.php" style="display:inline" onsubmit="return confirm('Delete user?')">
                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                    <?= csrf_field() ?>
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<p><a href="dashboard.php">Back</a></p>
</body>
</html>