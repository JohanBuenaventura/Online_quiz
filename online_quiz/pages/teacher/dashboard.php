<?php
session_start();
if (
    empty($_SESSION['user_id']) ||
    !in_array($_SESSION['user_role'] ?? '', ['teacher', 'admin'])
) {
    header('Location: ../login.php');
    exit;
}
$name = htmlspecialchars($_SESSION['user_name']);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <div class="container">
        <div class="top-row">
            <div class="welcome">Hello, <?= $name ?></div>
            <div class="links"><a class="btn" href="../dashboard.php">Main Dashboard</a></div>
        </div>
        <div class="grid">
            <div class="card">
                <h3>Create Quiz</h3>
                <p>Create a new quiz with questions and settings.</p>
                <a class="btn" href="create_quiz.php">Create</a>
            </div>
            <div class="card">
                <h3>Manage Quizzes</h3>
                <p>Edit quizzes, add questions and start sessions.</p>
                <a class="btn" href="manage_quizzes.php">Manage</a>
            </div>
            <div class="card">
                <h3>Start Live Session</h3>
                <p>Create a live session and invite students with a code.</p>
                <a class="btn" href="start_session.php">Start</a>
            </div>
        </div>
    </div>
</body>
</html>