<?php
session_start();
require_once __DIR__ . '/../../classes/Session.php';

$sessionModel = new Session();

$code = $_GET['code'] ?? '';
if ($code === '') exit('Missing code');
$s = $sessionModel->getSessionByCode($code);
if (!$s) exit('Session not found');
$board = $sessionModel->getLeaderboard($s['id']);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Leaderboard</title>
<link rel="stylesheet" href="../../assets/css/leaderboard.css">
</head>
<body>
<h1>Leaderboard for <?= htmlspecialchars($s['session_code']) ?></h1>
<?php if (!$board): ?>
    <p>No participants yet.</p>
<?php else: ?>
    <table border="1">
        <tr><th>Rank</th><th>Name</th><th>Score</th></tr>
        <?php $rank = 1; foreach ($board as $row): ?>
            <tr>
                <td><?= $rank++ ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= (int)$row['score'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<!-- <p><a href="play.php?code=<?= urlencode($code) ?>">Back to play</a></p> -->
<p><a href="../../pages/student/history.php"<?= urlencode($code) ?>>Back</a></p>
</body>
</html>