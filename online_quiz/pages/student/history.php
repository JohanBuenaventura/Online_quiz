<?php
session_start();
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'student') {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../database.php';

$db = (new Database())->connect();
$studentId = (int)$_SESSION['user_id'];

$stmt = $db->prepare(
    "SELECT sc.score, sc.created_at AS scored_at, se.session_code AS session_code, q.title AS quiz_title, se.id AS session_id
     FROM scores sc
     JOIN sessions se ON se.id = sc.session_id
     LEFT JOIN quizzes q ON q.id = se.quiz_id
     WHERE sc.student_id = :uid
     ORDER BY sc.created_at DESC"
);
$stmt->execute([':uid' => $studentId]);
$rows = $stmt->fetchAll();

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Quiz History</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <style>
        table{width:100%;border-collapse:collapse;background:#fff;color:#111;border-radius:8px;overflow:hidden}
        th,td{padding:10px;border-bottom:1px solid #eee;text-align:left}
        thead{background:#f4f6f8}
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <div class="container">
        <h2>My Quiz History</h2>
        <?php if (empty($rows)): ?>
            <div class="card empty">You have no quiz attempts yet.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>Quiz</th><th>Session Code</th><th>Score</th><th>When</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['quiz_title'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($r['session_code']) ?></td>
                        <td><?= (int)$r['score'] ?></td>
                        <td><?= htmlspecialchars($r['scored_at']) ?></td>
                        <td> <a href="../session/leaderboard.php?code=<?= urlencode($r['session_code']) ?>">View Leaderboard</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
