<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$name = htmlspecialchars($_SESSION['user_name'] ?? '');
$role = $_SESSION['user_role'] ?? 'student';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/header.css">

</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <div class="container">
        <div class="top-row">
            <div class="welcome">Welcome, <?= $name ?></div>
            
            <div class="links">
                <?php if ($role === 'teacher' || $role === 'admin'): ?>
                    <a class="btn" href="teacher/manage_quizzes.php">My Quizzes</a>
                <?php endif; ?>
                <?php if ($role === 'student'): ?>
                    <a class="btn" href="student/join.php">Join Quiz</a>
                <?php endif; ?>
                <a class="btn" href="logout.php">Logout</a>
            </div>
        </div>
        
         <?php if ($role === 'admin'): ?>
                <br>
            <div class="card">
                <h3>Admin Panel</h3>
                <p>Manage users, quizzes and view reports.</p>
                <a class="btn" href="admin/dashboard.php">Open</a>
            </div>
            <?php endif; ?>
        
        <br>

        <div class="grid">
            <?php if ($role === 'teacher' || $role === 'admin'): ?>
            <div class="card">
                <h3>Create & Manage Quizzes</h3>
                <p>Create quizzes, add questions and run live sessions.</p>
                <a class="btn" href="teacher/manage_quizzes.php">Open</a>
            </div>
            <div class="card">
                <h3>Live Sessions</h3>
                <p>Start a session and get a join code for students.</p>
                <a class="btn" href="teacher/start_session.php">Start</a>
        </div>
        </div>
        
        <br>
        <div class="card">
            <h3>Recent Quizzes</h3>
        <ul>
            <li>Quiz on Algorithms</li>
            <li>Database Fundamentals</li>
            <li>Web Development Basics</li>
        </ul>
        </div>

        <div class="card">
        <h3>Student Activity</h3>
        <p>No student activity yet. Once quizzes are taken, you’ll see participation stats here.</p>
        </div>

        <div class="card">
            <h3>Feedback</h3>
            <p>You have no feedback from students yet.</p>

            <?php endif; ?>

                <?php if ($role === 'student'): ?>
            <div class="card">
                <h3>Recent Announcements</h3>
                <p>No announcements yet. Stay tuned for updates from your instructors or admins.</p>
                <div class="card">
                <h3>Student Area</h3>
                <p>Quick actions for students: join live quizzes or view your results.</p>
                <a class="btn" href="student/dashboard.php">Open</a>
                <br>
                <br>
            </div>

            <div class="card">
                <h3>Upcoming Quizzes</h3>
                <p>You don’t have any upcoming quizzes at the moment.</p>
            </div>

            <div class="card">
                <h3>Performance Overview</h3>
                <p>Your progress summary will appear here once you start taking or managing quizzes.</p>
                <br>
                </div>
            
            <br>

            </div>
            <div class="card">
            <div class="card">
                <h3>Quiz History</h3>
                <p>You haven’t completed any quizzes yet.</p>
                <a class="btn" href="student/history.php">View History</a>
            </div>

            <div class="card">
                <h3>Leaderboard</h3>
                <p>Top performers will be shown here once you participate in quizzes.</p>
            </div>

            <div class="card">
                <h3>Achievements</h3>
                <p>Earn badges by scoring high or participating in multiple quizzes.</p>
            </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>