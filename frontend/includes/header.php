<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current = basename($_SERVER['PHP_SELF']);
?>
<header class="navbar">
  <div class="navbar-inner">
    <a href="index.php" class="navbar-logo">
      <div class="logo-icon">📊</div>
      <span>Lecture Confusion Tracker</span>
    </a>

    <ul class="navbar-links">
      <li><a href="index.php" <?php echo $current === 'index.php' ? 'style="color:var(--text)"' : ''; ?>>Home</a></li>
      <li><a href="login.php" <?php echo $current === 'login.php' ? 'style="color:var(--text)"' : ''; ?>>Login</a></li>
      <li>
        <a href="register.php" class="btn btn-primary" style="font-size:0.875rem; padding:8px 18px;">
          Get Started
        </a>
      </li>
    </ul>

    <button class="hamburger" aria-label="Toggle menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>

  <nav class="mobile-nav">
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
    <a href="register.php" class="btn btn-primary">Get Started</a>
  </nav>
</header>
