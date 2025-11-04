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
$quizModel = new Quiz();
$quizzes = $quizModel->getQuizzesByTeacher($_SESSION['user_id']);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage Quizzes</title>
  <link rel="stylesheet" href="../../assets/css/manage_quizzes.css">
</head>
<body>
  <div class="container">
    <h1>Your Quizzes</h1>
    <p><a href="create_quiz.php" class="create-btn">+ Create New Quiz</a></p>

    <?php if (!$quizzes): ?>
      <p class="empty">No quizzes yet.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Published</th>
          <th>Actions</th>
        </tr>
        <?php foreach ($quizzes as $q): ?>
          <tr>
            <td><?= (int)$q['id'] ?></td>
            <td><?= htmlspecialchars($q['title']) ?></td>
            <td><?= $q['is_published'] ? 'Yes' : 'No' ?></td>
            <td>
              <a href="edit_quiz.php?id=<?= (int)$q['id'] ?>">Edit</a> |
              <a href="manage_questions.php?quiz_id=<?= (int)$q['id'] ?>">Questions</a> |
              <a href="delete_quiz.php?id=<?= (int)$q['id'] ?>" onclick="return confirm('Delete this quiz?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>

    <p><a href="../dashboard.php" class="back-link">← Back to dashboard</a></p>
  </div>
</body>
</html>
