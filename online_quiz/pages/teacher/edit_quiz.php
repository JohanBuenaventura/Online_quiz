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

$csrfPath = __DIR__ . '/../../includes/csrf.php';
if (file_exists($csrfPath)) require_once $csrfPath;

$errors = [];

if (!isset($_GET['id'])) {
    header('Location: manage_quizzes.php');
    exit;
}
$id = (int)$_GET['id'];
$quiz = $quizModel->getQuizById($id);
if (!$quiz || $quiz['teacher_id'] != $_SESSION['user_id']) {
    exit('Quiz not found or access denied');
}

$title = $quiz['title'];
$published = $quiz['is_published'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !csrf_check($_POST['csrf_token'])) die('Invalid CSRF token');
    $title = trim(htmlspecialchars($_POST['title'] ?? ''));
    $published = isset($_POST['is_published']) ? 1 : 0;
    if ($title === '') $errors['title'] = 'Title is required';

    if (empty($errors)) {
        if ($quizModel->updateQuiz($id, $title, $published, null)) {
            header('Location: manage_quizzes.php');
            exit;
        } else {
            $errors['general'] = 'Failed to update';
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Quiz</title>
<link rel="stylesheet" href="../../assets/css/edit_quiz.css">
</head>
<body>
<h1>Edit Quiz</h1>
<?php if (!empty($errors['general'])): ?><p style="color:red"><?= htmlspecialchars($errors['general']) ?></p><?php endif; ?>
<form method="post" action="">
    <?= csrf_field() ?>
    <label>Title: <input type="text" name="title" value="<?= htmlspecialchars($title) ?>"></label>
    <p style="color:red"><?= $errors['title'] ?? '' ?></p>
    <label><input type="checkbox" name="is_published" <?= $published ? 'checked' : '' ?>> Publish</label>
    <button type="submit">Save</button>
</form>
<p><a href="manage_quizzes.php">Back</a></p>
</body>
</html>