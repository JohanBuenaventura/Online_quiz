<?php
session_start();
require_once __DIR__ . '/../../classes/Session.php';
require_once __DIR__ . '/../../classes/Question.php';
require_once __DIR__ . '/../../classes/Quiz.php';

$sessionModel = new Session();
$questionModel = new Question();
$quizModel = new Quiz();

if (empty($_GET['code'])) exit('Missing code');
$code = trim($_GET['code']);
$s = $sessionModel->getSessionByCode($code);
if (!$s || !$s['is_live']) exit('Session not available');

$current_q = null;
if (!empty($s['current_question_id'])) {
    $current_q = $questionModel->getQuestionById($s['current_question_id']);
}


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"><title>Play Quiz</title>
<link rel="stylesheet" href="../../assets/css/quiz.css">
  <link rel="stylesheet" href="assets/css/leaderboard.css">
</head>
<body>
<div class="container">
    <div class="header">
        <div class="brand"><span id="quiz_title"><?= htmlspecialchars($quizModel->getQuizById($s['quiz_id'])['title']) ?></span></div>
        <div class="session-code">Code: <strong id="session_code"><?= htmlspecialchars($s['session_code']) ?></strong></div>
    </div>
    <div id="card" class="card">
        <input type="hidden" id="session_id" value="<?= (int)$s['id'] ?>">
        <div class="question" id="question_text">Waiting for the teacher to show the next question...</div>
        <div class="choices" id="choices"></div>
        <div class="metadata">
            <div class="timer">&nbsp;</div>
            <div class="hint">Answers will be submitted instantly</div>
        </div>
        <div class="leaderboard" id="leaderboard">
            <h4>Leaderboard</h4>
            <div id="leaderboard_list"></div>
        </div>
    </div>
</div>
<script src="../../assets/js/session.js"></script>
</body>
</html>