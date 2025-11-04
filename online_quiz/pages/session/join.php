<?php
session_start();
require_once __DIR__ . '/../../classes/Session.php';
require_once __DIR__ . '/../../classes/Question.php';
require_once __DIR__ . '/../../classes/Quiz.php';

$sessionModel = new Session();
$questionModel = new Question();
$quizModel = new Quiz();

$errors = [];
$code = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim(htmlspecialchars($_POST['code'] ?? ''));
    if ($code === '') $errors['code'] = 'Enter session code';
    else {
        $s = $sessionModel->getSessionByCode($code);
        if (!$s || !$s['is_live']) {
            $errors['code'] = 'Session not found or not live';
        } else {
            // if user is logged in, use their id, else create temporary guest user
            if (!empty($_SESSION['user_id'])) {
                $uid = $_SESSION['user_id'];
            } else {
                // create a guest student account
                require_once __DIR__ . '/../../classes/User.php';
                $userModel = new User();
                $guest_email = 'guest_' . bin2hex(random_bytes(4)) . '@local';
                $uid = $userModel->register('Guest', $guest_email, bin2hex(random_bytes(6)), 'student');
                $_SESSION['user_id'] = $uid;
                $_SESSION['user_name'] = 'Guest';
                $_SESSION['user_role'] = 'student';
            }
            // register participant
            $sessionModel->addParticipantIfNotExists($s['id'], $uid);
            header('Location: play.php?code=' . urlencode($code));
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Join Session</title></head>
<body>
<h1>Join Session</h1>
<form method="post" action="">
    <label>Session Code: <input type="text" name="code" value="<?= htmlspecialchars($code) ?>"></label>
    <p style="color:red"><?= $errors['code'] ?? '' ?></p>
    <button type="submit">Join</button>
</form>
</body>
</html>