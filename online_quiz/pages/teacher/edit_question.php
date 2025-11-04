<?php
session_start();
if (
    empty($_SESSION['user_id']) ||
    !in_array($_SESSION['user_role'] ?? '', ['teacher', 'admin'])
) {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../classes/Question.php';
require_once __DIR__ . '/../../classes/Quiz.php';
$questionModel = new Question();
$quizModel = new Quiz();

$csrfPath = __DIR__ . '/../../includes/csrf.php';
if (file_exists($csrfPath)) require_once $csrfPath;

if (!isset($_GET['id'])) {
    header('Location: manage_quizzes.php');
    exit;
}
$id = (int)$_GET['id'];
$question = $questionModel->getQuestionById($id);
if (!$question) exit('Question not found');
$quiz = $quizModel->getQuizById($question['quiz_id']);
if (!$quiz || $quiz['teacher_id'] != $_SESSION['user_id']) exit('Access denied');

$errors = [];
$question_text = $question['question_text'];
$question_type = $question['question_type'];
$choices = $questionModel->getChoices($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !csrf_check($_POST['csrf_token'])) die('Invalid CSRF token');
    $question_text = trim(htmlspecialchars($_POST['question_text'] ?? ''));
    $question_type = $_POST['question_type'] ?? 'mcq';
    if ($question_text === '') $errors['question_text'] = 'Question text is required';

    if ($question_type === 'mcq') {
        $posted_choices = $_POST['choices'] ?? [];
        $correct_index = isset($_POST['correct_index']) ? (int)$_POST['correct_index'] : 0;
        // update existing choices or add new ones
    }

    if (empty($errors)) {
        if ($questionModel->updateQuestion($id, $question_text, $question_type)) {
            // simplistic choice update: delete existing and re-add
            if ($question_type === 'mcq') {
                $existing = $questionModel->getChoices($id);
                foreach ($existing as $c) { $questionModel->deleteChoice($c['id']); }
                foreach ($posted_choices as $idx => $text) {
                    $t = trim($text);
                    if ($t === '') continue;
                    $is_correct = ($idx === $correct_index) ? 1 : 0;
                    $questionModel->addChoice($id, $t, $is_correct);
                }
            }
            header('Location: manage_questions.php?quiz_id=' . $question['quiz_id']);
            exit;
        } else {
            $errors['general'] = 'Failed to update';
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Question</title>
<link rel="stylesheet" href="../../assets/css/edit_question.css">
</head>
<body>
<h1>Edit Question</h1>
<?php if (!empty($errors['general'])): ?><p style="color:red"><?= htmlspecialchars($errors['general']) ?></p><?php endif; ?>
<form method="post" action="">
    <?= csrf_field() ?>
    <label>Question text:<br>
        <textarea name="question_text" rows="4" cols="60"><?= htmlspecialchars($question_text) ?></textarea>
    </label>
    <p style="color:red"><?= $errors['question_text'] ?? '' ?></p>
    <label>Type:
        <select name="question_type">
            <option value="mcq" <?= $question_type==='mcq'?'selected':'' ?>>Multiple Choice</option>
            <option value="tf" <?= $question_type==='tf'?'selected':'' ?>>True/False</option>
            <option value="open" <?= $question_type==='open'?'selected':'' ?>>Open</option>
        </select>
    </label>

    <div id="mcq_section">
        <h4>Choices</h4>
        <?php foreach ($choices as $idx => $c): ?>
            <label>Choice <?= $idx+1 ?>: <input type="text" name="choices[]" value="<?= htmlspecialchars($c['choice_text']) ?>"></label>
            <label> Correct <input type="radio" name="correct_index" value="<?= $idx ?>" <?= ($c['is_correct']? 'checked' : '') ?>></label>
            <br>
        <?php endforeach; ?>
        <p style="color:red"><?= $errors['choices'] ?? '' ?></p>
    </div>
    <button type="submit">Save</button>
</form>
<p><a href="manage_questions.php?quiz_id=<?= (int)$question['quiz_id'] ?>">Back to questions</a></p>
</body>
</html>