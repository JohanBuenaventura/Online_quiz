<?php
session_start();
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../classes/User.php';
require_once __DIR__ . '/../../classes/Quiz.php';
$userModel = new User();
$quizModel = new Quiz();

$users = $userModel->getAllUsers();
$quizzes = $quizModel->getAllQuizzes();

// simple reports
$total_users = count($users);
$teachers = array_filter($users, function($u){ return $u['role']==='teacher'; });
$students = array_filter($users, function($u){ return $u['role']==='student'; });
$admins = array_filter($users, function($u){ return $u['role']==='admin'; });

?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Reports</title>
<link rel="stylesheet" href="../../assets/css/reports.css">

</head>
<body>
<h1>Reports</h1>
<h2>Overview</h2>
<ul>
    <li>Total users: <?= $total_users ?></li>
    <li>Teachers: <?= count($teachers) ?></li>
    <li>Students: <?= count($students) ?></li>
    <li>Admins: <?= count($admins) ?></li>
    <li>Total quizzes: <?= count($quizzes) ?></li>
</ul>

<h2>Quizzes by teacher</h2>
<table border="1">
    <tr><th>Teacher</th><th># Quizzes</th></tr>
    <?php
    $group = [];
    foreach ($quizzes as $q) { $group[$q['teacher_name']] = ($group[$q['teacher_name']] ?? 0) + 1; }
    foreach ($group as $t => $c): ?>
        <tr><td><?= htmlspecialchars($t) ?></td><td><?= (int)$c ?></td></tr>
    <?php endforeach; ?>
</table>

<p>
    <a href="reports_export.php?type=users">Export Users CSV</a> |
    <a href="reports_export.php?type=quizzes">Export Quizzes CSV</a>
</p>

<p><a href="dashboard.php">Back</a></p>
</body>
</html>