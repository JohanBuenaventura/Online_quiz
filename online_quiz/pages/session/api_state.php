<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../classes/Session.php';
require_once __DIR__ . '/../../classes/Question.php';
require_once __DIR__ . '/../../classes/Quiz.php';

$code = $_GET['code'] ?? '';
if (!$code) { echo json_encode(['error'=>'missing code']); exit; }
$sessionModel = new Session();
$session = $sessionModel->getSessionByCode($code);
if (!$session) { echo json_encode(['error'=>'session not found']); exit; }
$quizModel = new Quiz();
$q = $quizModel->getQuizById($session['quiz_id']);
$response = ['session_code' => $session['session_code'], 'quiz_title' => $q['title'] ?? null, 'is_live' => (bool)$session['is_live']];

if (!empty($session['current_question_id'])){
    $questionModel = new Question();
    $question = $questionModel->getQuestionById($session['current_question_id']);
    if ($question){
        $response['current_question'] = ['id' => (int)$question['id'], 'question_text' => $question['question_text'], 'question_type' => $question['question_type']];
        if ($question['question_type'] === 'mcq'){
            $response['current_question']['choices'] = $questionModel->getChoices($question['id']);
        }
    }
}

// include leaderboard snapshot
$response['leaderboard'] = $sessionModel->getLeaderboard($session['id']);

echo json_encode($response);
