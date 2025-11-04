<?php
session_start();
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Dashboard</title>
<link rel="stylesheet" href="../../assets/css/admin_dashboard.css">

</head>
<body>
<h1>Admin Dashboard</h1>
<ul>
    <li><a href="manage_users.php">Manage Users</a></li>
    <li><a href="manage_quizzes.php">Manage Quizzes</a></li>
    <li><a href="reports.php">Reports</a></li>
    <li><a href="../dashboard.php">Back to main dashboard</a></li>
</ul>
</body>
</html>