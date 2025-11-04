<?php
session_start();
// if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'teacher') {
//     header('Location: ../login.php');
//     exit;
// }

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

if (!isset($_GET['quiz_id'])) {
    header('Location: manage_quizzes.php');
    exit;
}
$quiz_id = (int)$_GET['quiz_id'];
$quiz = $quizModel->getQuizById($quiz_id);
if (!$quiz || $quiz['teacher_id'] != $_SESSION['user_id']) exit('Quiz not found or access denied');

$errors = [];
$question_text = '';
$question_type = 'mcq';
$choices = ['','','',''];
$correct_index = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !csrf_check($_POST['csrf_token'])) die('Invalid CSRF token');
    $question_text = trim(htmlspecialchars($_POST['question_text'] ?? ''));
    $question_type = $_POST['question_type'] ?? 'mcq';
    if ($question_text === '') $errors['question_text'] = 'Question text is required';

    if ($question_type === 'mcq') {
        // collect choices
        $choices = $_POST['choices'] ?? [];
        $correct_index = isset($_POST['correct_index']) ? (int)$_POST['correct_index'] : 0;
        $valid = true;
        $filled = 0;
        foreach ($choices as $c) { if (trim($c) !== '') $filled++; }
        if ($filled < 2) { $errors['choices'] = 'At least two choices required'; $valid = false; }
    }

    if (empty($errors)) {
        $qid = $questionModel->addQuestion($quiz_id, $question_text, $question_type);
        if ($qid) {
            if ($question_type === 'mcq') {
                // save choices
                foreach ($choices as $idx => $c) {
                    $text = trim($c);
                    if ($text === '') continue;
                    $is_correct = ($idx === $correct_index) ? 1 : 0;
                    $questionModel->addChoice($qid, $text, $is_correct);
                }
            }
            header('Location: manage_questions.php?quiz_id=' . $quiz_id);
            exit;
        } else {
            $errors['general'] = 'Failed to save question';
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Add Question</title>
<link rel="stylesheet" href="../../assets/css/add_question.css">
</head>
<body>
<h1>Add Question to <?= htmlspecialchars($quiz['title']) ?></h1>
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
        <?php for ($i=0;$i<4;$i++): ?>
            <label>Choice <?= $i+1 ?>: <input type="text" name="choices[]" value="<?= htmlspecialchars($choices[$i] ?? '') ?>"></label>
            <label> Correct <input type="radio" name="correct_index" value="<?= $i ?>" <?= ($correct_index===$i)?'checked':'' ?>></label>
            <br>
        <?php endfor; ?>
        <p style="color:red"><?= $errors['choices'] ?? '' ?></p>
    </div>
    <button type="submit">Add Question</button>
</form>
<p><a href="manage_questions.php?quiz_id=<?= (int)$quiz_id ?>">Back to questions</a></p>
</body>
</html>