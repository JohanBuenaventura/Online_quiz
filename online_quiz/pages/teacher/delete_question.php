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

if (!isset($_GET['id']) || !isset($_GET['quiz_id'])) {
    header('Location: manage_quizzes.php');
    exit;
}
$id = (int)$_GET['id'];
$quiz_id = (int)$_GET['quiz_id'];
$quiz = $quizModel->getQuizById($quiz_id);
if (!$quiz || $quiz['teacher_id'] != $_SESSION['user_id']) exit('Access denied');

$question = $questionModel->getQuestionById($id);
if (!$question) exit('Question not found');

if ($questionModel->deleteQuestion($id)) {
    header('Location: manage_questions.php?quiz_id=' . $quiz_id);
    exit;
} else {
    exit('Failed to delete');
}
