<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Resolve current page — works from subdirectories too
$current = basename($_SERVER['PHP_SELF']);
$isLoggedIn = isset($_SESSION['user_id']);
$userName   = $_SESSION['user_name'] ?? '';
$userRole   = $_SESSION['user_role'] ?? '';

// Build correct root path for links (handles /student/ subfolder)
$depth = substr_count(dirname($_SERVER['PHP_SELF']), '/') - substr_count(dirname('/'), '/');
$root  = str_repeat('../', max(0, $depth - 1));
if ($root === '') $root = '';
// Simpler: detect if we're in a subfolder
$inSub = strpos($_SERVER['PHP_SELF'], '/student/') !== false
      || strpos($_SERVER['PHP_SELF'], '/lecturer/') !== false;
$base  = $inSub ? '../' : '';
?>
<header class="navbar">
  <div class="navbar-inner">
    <a href="<?= $base ?>index.php" class="navbar-logo">
      <div class="logo-icon">📊</div>
      <span>Lecture Confusion Tracker</span>
    </a>

    <ul class="navbar-links">
      <li><a href="<?= $base ?>index.php">Home</a></li>

      <?php if ($isLoggedIn): ?>
        <?php if ($userRole === 'student'): ?>
          <li><a href="<?= $base ?>student/dashboard.php">Dashboard</a></li>
        <?php elseif ($userRole === 'lecturer'): ?>
          <li><a href="<?= $base ?>lecturer/dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li>
          <a href="<?= $base ?>auth/logout.php" class="btn-nav-logout">
            Sign Out
          </a>
        </li>
        <li>
          <span class="nav-user">
            <span class="nav-user-avatar"><?= strtoupper(substr($userName, 0, 1)) ?></span>
            <?= htmlspecialchars($userName) ?>
          </span>
        </li>
      <?php else: ?>
        <li><a href="<?= $base ?>login.php">Login</a></li>
        <li>
          <a href="<?= $base ?>register.php" class="btn btn-primary nav-cta">
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
    <a href="<?= $base ?>index.php">Home</a>
    <?php if ($isLoggedIn): ?>
      <?php if ($userRole === 'student'): ?>
        <a href="<?= $base ?>student/dashboard.php">Dashboard</a>
      <?php endif; ?>
      <a href="<?= $base ?>auth/logout.php">Sign Out</a>
    <?php else: ?>
      <a href="<?= $base ?>login.php">Login</a>
      <a href="<?= $base ?>register.php" class="btn btn-primary">Get Started</a>
    <?php endif; ?>
  </nav>
</header>
