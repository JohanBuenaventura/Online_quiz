<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Online Quiz System</title>
  <link rel="stylesheet" href="assets/css/landing.css">
</head>
<body>
  <header class="navbar">
    <div class="logo">Online<span>Quiz</span></div>
    <nav>
      <a href="#features">Features</a>
      <a href="#about">About</a>
      <a href="pages/login.php" class="btn-primary">Sign In</a>
      <a href="pages/register.php" class="btn-outline">Sign Up</a>
    </nav>
  </header>

  <section class="hero" id="hero">
    <div class="hero-content" id="hero-content">
      <h1>Empower Learning Through Live Quizzes.</h1>
      <p>Join our platform where teachers create engaging quizzes and students learn interactively in real-time.</p>
      <div class="buttons">
        <a href="pages/register.php" class="btn">Sign Up as Student</a>
        <a href="pages/register.php" class="btn">Sign Up as Teacher</a>
        <a href="pages/login.php" class="btn-outline">Already have an account? Sign In</a>
      </div>
    </div>
    <div class="hero-image">
      <img src="assets/images/quiz_illustration.png" alt="Online quiz illustration">
    </div>
  </section>

  <section id="features" class="features">
    <h2>System Features</h2>
    <div class="feature-grid">
      <div class="feature">
        <img src="assets/images/quiz_icon.png" alt="Quiz icon">
        <h3>Smart Quiz Creation</h3>
        <p>Teachers can create, edit, and publish quizzes in seconds.</p>
      </div>
      <div class="feature">
        <img src="assets/images/live.png" alt="Live icon">
        <h3>Live Sessions</h3>
        <p>Host live interactive quizzes with unique session codes.</p>
      </div>
      <div class="feature">
        <img src="assets/images/leaderboard.png" alt="Leaderboard icon">
        <h3>Leaderboard</h3>
        <p>Boost student engagement through competitive scoring.</p>
      </div>
      <div class="feature">
        <img src="assets/images/analytics.png" alt="Analytics icon">
        <h3>Progress Tracking</h3>
        <p>Monitor student results and quiz performance analytics.</p>
      </div>
    </div>
  </section>

  <section class="about" id="about">
  <div class="container about-content">
    <div class="about-text">
      <h2>About Online Quiz</h2>
      <p>
        <strong>Online Quiz System</strong> is an interactive online quiz platform built for students and teachers 
        to collaborate and compete in real time. Designed for engaging live sessions, it helps 
        teachers conduct quizzes effortlessly while motivating students through dynamic leaderboards, 
        instant feedback, and friendly competition.
      </p>
      <p>
        Whether you’re learning or teaching, Online Quiz creates a fun and educational environment 
        that makes every quiz feel like a challenge worth mastering.
      </p>
      <a href="#hero" class="btn btn-primary">Get Started</a>
    </div>
    <div class="about-image">
      <img src="assets/images/learning_collab.png" alt="Students collaborating during an online quiz">
    </div>
  </div>
</section>


  <footer id="about" class="footer">
    <p>© <?= date('Y') ?> Online Quiz System | Developed by Johan Buenaventura</p>
  </footer>
</body>
</html>
