<?php
session_start();
if (
    empty($_SESSION['user_id']) ||
    !in_array($_SESSION['user_role'] ?? '', ['teacher', 'admin'])
) {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../classes/Quiz.php';
require_once __DIR__ . '/../../classes/Session.php';

$quizModel = new Quiz();
$sessionModel = new Session();
$quizzes = $quizModel->getQuizzesByTeacher($_SESSION['user_id']);

$errors = [];
$created = null;

$csrfPath = __DIR__ . '/../../includes/csrf.php';
if (file_exists($csrfPath)) require_once $csrfPath;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !csrf_check($_POST['csrf_token'])) die('Invalid CSRF token');
    $quiz_id = isset($_POST['quiz_id']) ? (int)$_POST['quiz_id'] : 0;
    if ($quiz_id <= 0) $errors['quiz'] = 'Select a quiz';
    // verify teacher owns quiz
    $quiz = $quizModel->getQuizById($quiz_id);
    if (!$quiz || $quiz['teacher_id'] != $_SESSION['user_id']) $errors['quiz'] = 'Invalid quiz selected';

    if (empty($errors)) {
        $created = $sessionModel->createSession($quiz_id, $_SESSION['user_id']);
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Start Session</title>
  <link rel="stylesheet" href="../../assets/css/start_session.css">
</head>
<body>
  <div class="container">
    <h1>Start a Live Session</h1>

    <?php if ($created): ?>
      <div class="success">
        <p>Session created successfully!<br>
        Code: <strong><?= htmlspecialchars($created['code']) ?></strong></p>
        <p><a href="manage_session.php?id=<?= (int)$created['id'] ?>">→ Manage Session</a></p>
      </div>
    <?php else: ?>
      <form method="post" action="">
        <?= csrf_field() ?>
        <label>
          Select Quiz:
          <select name="quiz_id">
            <option value="">--Select--</option>
            <?php foreach ($quizzes as $q): ?>
              <option value="<?= (int)$q['id'] ?>"><?= htmlspecialchars($q['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <p class="error"><?= $errors['quiz'] ?? '' ?></p>
        <button type="submit">Create Session</button>
      </form>
    <?php endif; ?>
    <p class="back-link"><a href="../dashboard.php">← Back</a></p> <br>
    <p class="back-link"><a href="manage_quizzes.php">← Back to quizzes</a></p>
    
  </div>
</body>
</html>
