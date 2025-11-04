<?php
session_start();
// allow both logged-in students and guests; if you want only logged-in students, keep the check
// if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
//     header('Location: ../login.php');
//     exit;
// }

require_once __DIR__ . '/../../classes/Session.php';
require_once __DIR__ . '/../../classes/Question.php';
require_once __DIR__ . '/../../classes/Quiz.php';

$sessionModel = new Session();
$questionModel = new Question();
$quizModel = new Quiz();

$errors = [];
$code = '';

// Accept either POST (form submit) or GET (dashboard quick-join)
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' || ($method === 'GET' && !empty($_GET['code']))) {
    $code = trim(htmlspecialchars(($method === 'POST' ? ($_POST['code'] ?? '') : ($_GET['code'] ?? ''))));
    if ($code === '') {
        $errors['code'] = 'Session code is required';
    } else {
        $s = $sessionModel->getSessionByCode($code);
        if (!$s || !$s['is_live']) {
            $errors['code'] = 'Session not found or not live';
        } else {
            // determine or create a student identity
            if (!empty($_SESSION['user_id'])) {
                $uid = $_SESSION['user_id'];
            } else {
                require_once __DIR__ . '/../../classes/User.php';
                $userModel = new User();
                $guest_email = 'guest_' . bin2hex(random_bytes(4)) . '@local';
                $uid = $userModel->register('Guest', $guest_email, bin2hex(random_bytes(6)), 'student');
                $_SESSION['user_id'] = $uid;
                $_SESSION['user_name'] = 'Guest';
                $_SESSION['user_role'] = 'student';
            }

            // register participant if not exists
            $sessionModel->addParticipantIfNotExists($s['id'], $uid);
            header('Location: ../session/play.php?code=' . urlencode($code));
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Join Quiz</title>
<link rel="stylesheet" href="../../assets/css/join.css">
</head>
<body>
  <div class="join-card">
    <h1>Join a Session</h1>
    <p class="lead">Enter the session code provided by your teacher.</p>

    <form method="post" action="">
      <input 
        type="text" 
        name="code" 
        placeholder="Enter session code" 
        value="<?= htmlspecialchars($code) ?>"
      >
      <?php if (!empty($errors['code'])): ?>
        <div class="error"><?= htmlspecialchars($errors['code']) ?></div>
      <?php endif; ?>
      <button type="submit">Join Session</button>
    </form>
  </div>
</body>

</html>