<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$userName   = $_SESSION['user_name'] ?? ($_SESSION['username'] ?? '');
$userRole   = $_SESSION['user_role'] ?? ($_SESSION['role'] ?? '');

// Build a stable base URL regardless of nesting.
// Example: /confusion-tracker-system/frontend/student/dashboard.php -> /confusion-tracker-system/
$script  = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '');
$pos     = strpos($script, '/frontend/');
$baseUrl = $pos !== false ? substr($script, 0, $pos) . '/' : '/';
$front   = $baseUrl . 'frontend/';
$back    = $baseUrl . 'backend/';
?>
<header class="navbar">
  <div class="navbar-inner">
    <a href="<?= $front ?>index.php" class="navbar-logo">
      <div class="logo-icon">📊</div>
      <span>Lecture Confusion Tracker</span>
    </a>

    <ul class="navbar-links">
      <li><a href="<?= $front ?>index.php">Home</a></li>

      <?php if ($isLoggedIn): ?>
        <?php if ($userRole === 'student'): ?>
          <li><a href="<?= $front ?>student/dashboard.php">Dashboard</a></li>
        <?php elseif ($userRole === 'lecturer'): ?>
          <li><a href="<?= $front ?>lecturer/dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li><a href="<?= $front ?>profile.php">Profile</a></li>
        <li>
          <a href="<?= $back ?>auth/logout.php" class="btn-nav-logout">
            Sign Out
          </a>
        </li>
        <li>
          <a href="<?= $base ?>profile.php" class="nav-user">
            <span class="nav-user-avatar"><?= strtoupper(substr($userName, 0, 1)) ?></span>
            <?= htmlspecialchars($userName) ?>
          </a>
        </li>
      <?php else: ?>
        <li><a href="<?= $front ?>login.php">Login</a></li>
        <li>
          <a href="<?= $front ?>register.php" class="btn btn-primary nav-cta">
            Get Started
          </a>
        </li>
      <?php endif; ?>
    </ul>

    <button class="hamburger" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>
  </div>

  <nav class="mobile-nav">
    <a href="<?= $front ?>index.php">Home</a>
    <?php if ($isLoggedIn): ?>
      <?php if ($userRole === 'student'): ?>
        <a href="<?= $front ?>student/dashboard.php">Dashboard</a>
      <?php elseif ($userRole === 'lecturer'): ?>
        <a href="<?= $front ?>lecturer/dashboard.php">Dashboard</a>
      <?php endif; ?>
      <a href="<?= $front ?>profile.php">Profile</a>
      <a href="<?= $back ?>auth/logout.php">Sign Out</a>
    <?php else: ?>
      <a href="<?= $front ?>login.php">Login</a>
      <a href="<?= $front ?>register.php" class="btn btn-primary">Get Started</a>
    <?php endif; ?>
  </nav>
</header>
