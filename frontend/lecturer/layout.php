<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Preview mode (UI-only): add ?preview=1 to bypass auth during frontend work.
// Backend auth can remove this later.
if (!isset($_SESSION['user_id']) && (($_GET['preview'] ?? '') === '1')) {
  $_SESSION['user_id'] = -1;
  $_SESSION['user_role'] = 'lecturer';
  $_SESSION['user_name'] = $_SESSION['user_name'] ?? 'Lecturer Preview';
}

require_once __DIR__ . '/../includes/auth_guard.php';
guardRole('lecturer');

$page = $page ?? 'dashboard'; // dashboard | analytics | insights | feedback
$userName = $_SESSION['user_name'] ?? 'Lecturer';
$userInitial = strtoupper(substr(trim($userName), 0, 1));

function portalNavLink(string $href, string $label, string $icon, bool $active): string {
  $cls = $active ? 'active' : '';
  return '<li><a class="' . $cls . '" href="' . htmlspecialchars($href) . '">' . $icon . '<span>' . htmlspecialchars($label) . '</span></a></li>';
}

$icoDashboard = '<svg class="nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13h8V3H3v10zM13 21h8V11h-8v10zM13 3h8v6h-8V3zM3 17h8v4H3v-4z"/></svg>';
$icoInsights  = '<svg class="nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 6-6"/></svg>';
$icoFeedback  = '<svg class="nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>';
$icoAnalytics = '<svg class="nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-7"/></svg>';

$topSearchIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>';
$bellIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 10-12 0c0 7-3 7-3 7h18s-3 0-3-7"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>';
$gearIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/><path d="M19.4 15a7.7 7.7 0 0 0 .1-1 7.7 7.7 0 0 0-.1-1l2-1.5-2-3.5-2.4.7a7.2 7.2 0 0 0-1.7-1l-.4-2.4h-4l-.4 2.4a7.2 7.2 0 0 0-1.7 1l-2.4-.7-2 3.5 2 1.5a7.7 7.7 0 0 0-.1 1 7.7 7.7 0 0 0 .1 1l-2 1.5 2 3.5 2.4-.7a7.2 7.2 0 0 0 1.7 1l.4 2.4h4l.4-2.4a7.2 7.2 0 0 0 1.7-1l2.4.7 2-3.5-2-1.5z"/></svg>';
?>

<div class="portal">
  <aside class="portal-sidebar">
    <div class="portal-brand">
      <div class="portal-brand-icon">🎓</div>
      <div>
        <div class="portal-brand-title">Faculty Portal</div>
        <div class="portal-brand-sub">Spring Semester 2026</div>
      </div>
    </div>

    <ul class="portal-nav">
      <?= portalNavLink('dashboard.php', 'Dashboard', $icoDashboard, $page === 'dashboard') ?>
      <?= portalNavLink('dashboard.php', 'Lecture Insights', $icoInsights, $page === 'insights') ?>
      <?= portalNavLink('dashboard.php', 'Student Feedback', $icoFeedback, $page === 'feedback') ?>
      <?= portalNavLink('analytics.php', 'Analytics', $icoAnalytics, $page === 'analytics') ?>
    </ul>

    <div class="portal-sidebar-spacer"></div>

    <div class="portal-sidebar-footer">
      <a class="portal-pill-btn primary" href="#" onclick="return false;">
        <span>＋</span>
        <span>New Lecture Session</span>
      </a>
      <a class="portal-pill-btn" href="../support.php">Support</a>
      <a class="portal-pill-btn" href="../auth/logout.php">Account</a>
    </div>
  </aside>

  <main class="portal-main">
    <div class="portal-topbar">
      <ul class="portal-top-links">
        <li><a class="<?= $page === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">Lecture Confusion Tracker</a></li>
        <li><a href="#" onclick="return false;">My Courses</a></li>
        <li><a class="<?= $page === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">Lecture Hall</a></li>
        <li><a href="#" onclick="return false;">Resources</a></li>
      </ul>

      <div class="portal-top-actions">
        <div class="portal-search" aria-label="Search">
          <?= $topSearchIco ?>
          <input type="text" placeholder="Search analytics..." />
        </div>

        <button class="portal-ico-btn" type="button" aria-label="Notifications" onclick="return false;">
          <?= $bellIco ?>
        </button>
        <button class="portal-ico-btn" type="button" aria-label="Settings" onclick="return false;">
          <?= $gearIco ?>
        </button>
        <div class="portal-avatar" title="<?= htmlspecialchars($userName) ?>"><?= htmlspecialchars($userInitial) ?></div>
      </div>
    </div>

    <div class="portal-page">

      <!-- Mobile bottom navigation -->
      <nav class="portal-mobile-nav" aria-label="Portal navigation">
        <ul>
          <li><a class="<?= $page === 'dashboard' ? 'active' : '' ?>" href="dashboard.php"><?= $icoDashboard ?><span>Dashboard</span></a></li>
          <li><a class="<?= $page === 'insights' ? 'active' : '' ?>" href="dashboard.php"><?= $icoInsights ?><span>Insights</span></a></li>
          <li><a class="<?= $page === 'feedback' ? 'active' : '' ?>" href="dashboard.php"><?= $icoFeedback ?><span>Feedback</span></a></li>
          <li><a class="<?= $page === 'analytics' ? 'active' : '' ?>" href="analytics.php"><?= $icoAnalytics ?><span>Analytics</span></a></li>
        </ul>
      </nav>
