<?php
session_start();
if (
    empty($_SESSION['user_id']) ||
    !in_array($_SESSION['user_role'] ?? '', ['teacher', 'admin'])
) {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../classes/Session.php';
require_once __DIR__ . '/../../classes/Quiz.php';
require_once __DIR__ . '/../../classes/Question.php';

$sessionModel = new Session();
$quizModel = new Quiz();
$questionModel = new Question();

if (!isset($_GET['id'])) { header('Location: start_session.php'); exit; }
$sid = (int)$_GET['id'];
$session = $sessionModel->getSessionById($sid);
if (!$session || $session['host_teacher_id'] != $_SESSION['user_id']) exit('Session not found or access denied');
$quiz = $quizModel->getQuizById($session['quiz_id']);
$questions = $questionModel->getQuestionsByQuiz($quiz['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'start') {
            $sessionModel->startSession($sid);
        } elseif ($_POST['action'] === 'end') {
            $sessionModel->endSession($sid);
        } elseif ($_POST['action'] === 'next' && isset($_POST['question_id'])) {
            $qid = (int)$_POST['question_id'];
            $sessionModel->setCurrentQuestion($sid, $qid);
        }
        header('Location: manage_session.php?id=' . $sid);
        exit;
    }
}

$leaderboard = $sessionModel->getLeaderboard($sid);
$csrfPath = __DIR__ . '/../../includes/csrf.php';
if (file_exists($csrfPath)) require_once $csrfPath;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Session</title>
<link rel="stylesheet" href="../../assets/css/manage_session.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="page-header">
    <h1><i class="fa-solid fa-chalkboard-user"></i> Manage Session for <?= htmlspecialchars($quiz['title']) ?></h1>
    <p>Session Code: <span class="badge"><?= htmlspecialchars($session['session_code']) ?></span></p>
    <p class="status">
        Started: <?= $session['started_at'] ?? 'Not started' ?> | 
        <span class="<?= $session['is_live'] ? 'live' : 'inactive' ?>">
            <?= $session['is_live'] ? '🟢 Live' : '🔴 Not Live' ?>
        </span>
    </p>
</div>

<div class="container">

    <div class="card">
        <form method="post" action="">
            <?= csrf_field() ?>
            <?php if (!$session['is_live']): ?>
                <button name="action" value="start" class="btn start"><i class="fa-solid fa-play"></i> Start Session</button>
            <?php else: ?>
                <button name="action" value="end" class="btn end"><i class="fa-solid fa-stop"></i> End Session</button>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <h2><i class="fa-solid fa-circle-question"></i> Questions</h2>
        <?php if (!$questions): ?>
            <div class="empty-state">
                <img src="../../assets/images/empty_question.png" alt="No questions">
                <p>No questions yet. Add some to get started!</p>
            </div>
        <?php else: ?>
            <form method="post" action="">
                <?= csrf_field() ?>
                <label>Select question to show:</label>
                <select name="question_id">
                    <?php foreach ($questions as $q): ?>
                        <option value="<?= (int)$q['id'] ?>" <?= ($session['current_question_id'] == $q['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($q['question_text']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button name="action" value="next" class="btn next"><i class="fa-solid fa-forward"></i> Show Question</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2><i class="fa-solid fa-ranking-star"></i> Leaderboard</h2>
        <?php if (!$leaderboard): ?>
            <div class="empty-state">
                <img src="../../assets/images/empty_leaderboard.png" alt="No participants">
                <p>No participants yet.</p>
            </div>
        <?php else: ?>
            <table>
                <tr><th>Name</th><th>Score</th></tr>
                <?php foreach ($leaderboard as $row): ?>
                    <tr><td><?= htmlspecialchars($row['name']) ?></td><td><?= (int)$row['score'] ?></td></tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <div class="back-link">
        <a href="../teacher/manage_quizzes.php"><i class="fa-solid fa-arrow-left"></i> Back to quizzes</a>
    </div>
</div>

</body>
</html>
