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
require_once __DIR__ . '/../../classes/Question.php';

$quizModel = new Quiz();
$questionModel = new Question();

if (!isset($_GET['quiz_id'])) {
    header('Location: manage_quizzes.php');
    exit;
}
$quiz_id = (int)$_GET['quiz_id'];
$quiz = $quizModel->getQuizById($quiz_id);
if (!$quiz || $quiz['teacher_id'] != $_SESSION['user_id']) exit('Quiz not found or access denied');

$questions = $questionModel->getQuestionsByQuiz($quiz_id);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Questions for <?= htmlspecialchars($quiz['title']) ?></title>
<link rel="stylesheet" href="../../assets/css/manage_questions.css">

</head>
<body>
<h1>Questions for <?= htmlspecialchars($quiz['title']) ?></h1>
<p><a href="add_question.php?quiz_id=<?= (int)$quiz_id ?>">Add Question</a> | <a href="manage_quizzes.php">Back to quizzes</a></p>
<?php if (!$questions): ?>
    <p>No questions yet.</p>
<?php else: ?>
    <table border="1">
        <tr><th>ID</th><th>Text</th><th>Type</th><th>Actions</th></tr>
        <?php foreach ($questions as $q): ?>
            <tr>
                <td><?= (int)$q['id'] ?></td>
                <td><?= htmlspecialchars($q['question_text']) ?></td>
                <td><?= htmlspecialchars($q['question_type']) ?></td>
                <td>
                    <a href="edit_question.php?id=<?= (int)$q['id'] ?>">Edit</a> |
                    <a href="delete_question.php?id=<?= (int)$q['id'] ?>&quiz_id=<?= (int)$quiz_id ?>" onclick="return confirm('Delete this question?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>