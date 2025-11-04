<?php
session_start();
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../classes/Quiz.php';
$quizModel = new Quiz();
$quizzes = $quizModel->getAllQuizzes();
// CSRF helper
$csrfPath = __DIR__ . '/../../includes/csrf.php';
if (file_exists($csrfPath)) require_once $csrfPath;
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manage Quizzes (Admin)</title>
<link rel="stylesheet" href="../../assets/css/manage_quizzes.css">

</head>
<body>
<h1>All Quizzes</h1>
<table border="1">
    <tr><th>ID</th><th>Title</th><th>Teacher</th><th>Published</th><th>Actions</th></tr>
    <?php foreach ($quizzes as $q): ?>
        <tr>
            <td><?= (int)$q['id'] ?></td>
            <td><?= htmlspecialchars($q['title']) ?></td>
            <td><?= htmlspecialchars($q['teacher_name']) ?></td>
            <td><?= $q['is_published'] ? 'Yes' : 'No' ?></td>
            <td>
                <a href="../teacher/manage_questions.php?quiz_id=<?= (int)$q['id'] ?>">Questions</a> |
                <form method="post" action="delete_quiz.php" style="display:inline" onsubmit="return confirm('Delete this quiz?')">
                    <input type="hidden" name="id" value="<?= (int)$q['id'] ?>">
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