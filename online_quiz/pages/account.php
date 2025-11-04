<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Sample user info (replace with DB data in real setup)
$name = htmlspecialchars($_SESSION['user_name']);
$email = htmlspecialchars($_SESSION['user_email'] ?? 'Not Verified');
$role = htmlspecialchars($_SESSION['user_role']);
$user_id = htmlspecialchars($_SESSION['user_id']);
$join_date = htmlspecialchars($_SESSION['join_date'] ?? date('Y-m-d'));
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Account Settings</title>
  <link rel="stylesheet" href="../assets/css/account.css">
  <link rel="stylesheet" href="../assets/css/dashboard.css">
  <link rel="stylesheet" href="../assets/css/header.css">
  
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <div class="account-container">
    <div class="back-container">
      <a href="dashboard.php">← Back to Dashboard</a>
    </div>

    <div class="profile-card">
      <div class="profile-header">
        <img src="../assets/img/avatar_default.png" alt="User Avatar" class="profile-avatar">
        <div>
          <h1 id="displayName"><?= $name ?></h1>
          <p class="role"><?= ucfirst($role) ?></p>
        </div>
      </div>

      <div class="profile-details">
        <h2>Account Information</h2>

        <form id="accountForm" method="post" action="update_account.php">
          <!-- <div class="info-grid">
            <div class="info-item">
              <label>User ID</label>
              <p><?= $user_id ?></p>
            </div> -->

            <div class="info-item">
              <label>Full Name</label>
              <p><?= $name ?></p>
              <input type="text" name="name" value="<?= $name ?>" hidden>
            </div>

            <div class="info-item">
              <label>Email Address</label>
              <p><?= $email ?></p>
              <input type="email" name="email" value="<?= $email ?>" hidden>
            </div>

            <div class="info-item">
              <label>Account Role</label>
              <p><?= ucfirst($role) ?></p>
            </div>

            <div class="info-item">
              <label>Join Date</label>
              <p><?= $join_date ?></p>
            </div>
          </div>

          <div class="actions">
            <button type="button" class="edit-btn" id="editBtn"> Edit Info</button>
            <button type="submit" class="btn" id="saveBtn" style="display:none;"> Save Changes</button>
            <a href="account.php" class="btn logout">Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const form = document.getElementById('accountForm');

    editBtn.addEventListener('click', () => {
      const inputs = form.querySelectorAll('input');
      const paragraphs = form.querySelectorAll('p');

      inputs.forEach(input => input.hidden = false);
      paragraphs.forEach(p => p.style.display = 'none');

      editBtn.style.display = 'none';
      saveBtn.style.display = 'inline-block';
    });
  </script>
</body>
</html>
