<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
$name = htmlspecialchars($_SESSION['user_name'] ?? 'Guest');
$role = htmlspecialchars($_SESSION['user_role'] ?? 'guest');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<header class="site-header">
  <div class="brand">
    <i class="ri-graduation-cap-line"></i> Online <span>Quiz</span>
  </div>

  <div class="user-info">
    <span class="role-badge"><?= ucfirst($role) ?></span>
    <span class="user-name">Hello, <strong><?= $name ?></strong></span>
    
    <div class="user-actions">
      <a href="/online_quiz/pages/account.php" class="link">Account Settings</a>
      <a href="/online_quiz/pages/logout.php" class="btn-logout">Logout</a>
    </div>
  </div>
</header>
</html>


