<?php
session_start();

//if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'teacher') {
    //header('Location: ../login.php');
    //exit;
//}
if (
    empty($_SESSION['user_id']) ||
    !in_array($_SESSION['user_role'] ?? '', ['teacher', 'admin'])
) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../classes/Quiz.php';
$quizModel = new Quiz();

if (!isset($_GET['id'])) {
    header('Location: manage_quizzes.php');
    exit;
}
$id = (int)$_GET['id'];
$quiz = $quizModel->getQuizById($id);
if (!$quiz || $quiz['teacher_id'] != $_SESSION['user_id']) {
    exit('Quiz not found or access denied');
}

if ($quizModel->deleteQuiz($id)) {
    header('Location: manage_quizzes.php');
    exit;
} else {
    exit('Failed to delete');
}
