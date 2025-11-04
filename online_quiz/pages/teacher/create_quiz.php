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
$csrfPath = __DIR__ . '/../../includes/csrf.php';
if (file_exists($csrfPath)) require_once $csrfPath;
$quizModel = new Quiz();

$errors = [];
$title = '';
$published = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !csrf_check($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }
    $title = trim(htmlspecialchars($_POST['title'] ?? ''));
    $published = isset($_POST['is_published']) ? 1 : 0;

    if ($title === '') $errors['title'] = 'Title is required';

    if (empty($errors)) {
        $id = $quizModel->createQuiz($title, $_SESSION['user_id'], $published, null);
        if ($id) {
            header('Location: manage_quizzes.php');
            exit;
        } else {
            $errors['general'] = 'Failed to create quiz';
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Create Quiz</title>
<link rel="stylesheet" href="../../assets/css/create_quiz.css">

</head>
<body>
<h1>Create Quiz</h1>
<?php if (!empty($errors['general'])): ?><p style="color:red"><?= htmlspecialchars($errors['general']) ?></p><?php endif; ?>
<form method="post" action="">
    <?= csrf_field() ?>
    <label>Title: <input type="text" name="title" value="<?= htmlspecialchars($title) ?>"></label>
    <p style="color:red"><?= $errors['title'] ?? '' ?></p>
    <label><input type="checkbox" name="is_published" <?= $published ? 'checked' : '' ?>> Publish</label>
    <button type="submit">Create</button>
</form>
<p><a href="manage_quizzes.php">Back to quizzes</a></p>
</body>
</html>