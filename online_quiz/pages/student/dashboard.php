<?php
session_start();
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    header('Location: ../login.php');
    exit;
}
$name = htmlspecialchars($_SESSION['user_name']);
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Dashboard</title>
    
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <div class="container">
        <div class="top-row">
            <div class="welcome">Hi, <?= $name ?></div>
            <div class="links"><a class="btn" href="../dashboard.php">Main Dashboard</a></div>
        </div>
        <div class="grid">
            <div class="card">
                <h3>Join a Live Quiz</h3>
                <p>Enter a session code to join a live quiz.</p>
                <form action="join.php" method="get" class="inline-join">
                    <input type="text" name="code" placeholder="Session code" required />
                    <button class="btn" type="submit">Join</button>
                </form>
            </div>
            <div class="card">
                <h3>Quiz History</h3>
                <p>See your past quizzes and scores.</p>
                <a class="btn" href="history.php">View</a>
            </div>
            <div class="card">
                <h3>Profile</h3>
                <p>Manage your account details.</p>
                <a class="btn" href="../account.php">Profile</a>
            </div>
        </div>
    </div>
</body>
</html>