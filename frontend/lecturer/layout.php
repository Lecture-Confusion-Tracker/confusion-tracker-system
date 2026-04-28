<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Preview mode — bypass auth with ?preview=1
if (!isset($_SESSION['user_id']) && (($_GET['preview'] ?? '') === '1')) {
    $_SESSION['user_id']   = -1;
    $_SESSION['user_role'] = 'lecturer';
    $_SESSION['role']      = 'lecturer';
    $_SESSION['user_name'] = 'Lecturer Preview';
}

require_once __DIR__ . '/../includes/auth_guard.php';
guardRole('lecturer');

$page        = $page ?? 'dashboard';
$userName    = $_SESSION['user_name'] ?? ($_SESSION['username'] ?? 'Lecturer');
$userInitial = strtoupper(substr(trim($userName), 0, 1));
$role        = $_SESSION['user_role'] ?? ($_SESSION['role'] ?? 'lecturer');

// Consistent gradient from username
$avatarColors  = ['#6366f1,#8b5cf6','#ec4899,#f43f5e','#14b8a6,#06b6d4','#f59e0b,#ef4444','#10b981,#3b82f6'];
$avatarGradient = $avatarColors[abs(crc32($userName)) % count($avatarColors)];

// ── Notification count from DB ────────────────────
$notifCount = 0;
$recentConfusions = [];
try {
    require_once __DIR__ . '/../../backend/auth/includes/db.php';
    // Recent notifications: latest 5 confusions (works for MySQL/SQLite)
    $recentConfusions = $pdo->query("
        SELECT id, topic, tag, created_at
        FROM confusions
        ORDER BY created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
    $notifCount = count($recentConfusions);
} catch (Throwable $e) { $notifCount = 0; }

function portalNavLink(string $href, string $label, string $icon, bool $active): string {
    $cls = $active ? 'active' : '';
    return '<li><a class="'.$cls.'" href="'.htmlspecialchars($href).'">'.$icon.'<span>'.htmlspecialchars($label).'</span></a></li>';
}

$icoDashboard = '<svg class="nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13h8V3H3v10zM13 21h8V11h-8v10zM13 3h8v6h-8V3zM3 17h8v4H3v-4z"/></svg>';
$icoInsights  = '<svg class="nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 6-6"/></svg>';
$icoFeedback  = '<svg class="nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>';
$icoAnalytics = '<svg class="nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-7"/></svg>';
$icoProfile   = '<svg class="nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';

$bellIco   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 10-12 0c0 7-3 7-3 7h18s-3 0-3-7"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>';
$searchIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>';
?>

<!-- NO global header.php here — portal has its own nav -->
<div class="portal">

  <!-- Sidebar -->
  <aside class="portal-sidebar">
    <div class="portal-brand">
      <div class="portal-brand-icon">🎓</div>
      <div>
        <div class="portal-brand-title">Faculty Portal</div>
        <div class="portal-brand-sub">Spring Semester 2026</div>
      </div>
    </div>

    <ul class="portal-nav">
      <?= portalNavLink('dashboard.php',   'Dashboard',       $icoDashboard, $page === 'dashboard') ?>
      <?= portalNavLink('insights.php',    'Lecture Insights', $icoInsights,  $page === 'insights') ?>
      <?= portalNavLink('feedback.php',    'Student Feedback', $icoFeedback,  $page === 'feedback') ?>
      <?= portalNavLink('analytics.php',   'Analytics',        $icoAnalytics, $page === 'analytics') ?>
      <?= portalNavLink('../profile.php',  'Profile',          $icoProfile,   $page === 'profile') ?>
    </ul>

    <div class="portal-sidebar-spacer"></div>

    <div class="portal-sidebar-footer">
      <a class="portal-pill-btn primary" href="new_session.php">
        <span>＋</span><span>New Session</span>
      </a>
      <a class="portal-pill-btn" href="../support.php">Support</a>
      <a class="portal-pill-btn" href="../../backend/auth/logout.php">Sign Out</a>
    </div>
  </aside>

  <!-- Main -->
  <main class="portal-main">
    <div class="portal-topbar">
      <ul class="portal-top-links">
        <li><a class="<?= $page==='dashboard'?'active':'' ?>" href="dashboard.php">Dashboard</a></li>
        <li><a class="<?= $page==='insights'?'active':'' ?>"  href="insights.php">Insights</a></li>
        <li><a class="<?= $page==='analytics'?'active':'' ?>" href="analytics.php">Analytics</a></li>
        <li><a href="../index.php">← Home</a></li>
      </ul>

      <div class="portal-top-actions">
        <div class="portal-search">
          <?= $searchIco ?>
          <input type="text" id="portal-search-input" placeholder="Search topics…" />
        </div>

        <!-- Notification bell -->
        <div class="notif-wrap">
          <button class="portal-ico-btn" id="notif-btn" type="button" aria-label="Notifications">
            <?= $bellIco ?>
            <?php if ($notifCount > 0): ?>
              <span class="notif-badge"><?= $notifCount > 9 ? '9+' : $notifCount ?></span>
            <?php endif; ?>
          </button>
          <div class="notif-dropdown" id="notif-dropdown">
            <div class="notif-header">
              <span>Notifications</span>
              <?php if ($notifCount > 0): ?>
                <span class="badge"><?= $notifCount ?> new</span>
              <?php endif; ?>
            </div>
            <?php if (!empty($recentConfusions)): ?>
              <?php foreach ($recentConfusions as $n): ?>
                <div class="notif-item">
                  <span class="notif-dot"></span>
                  <div>
                    <div class="notif-text">
                      <strong><?= htmlspecialchars($n['topic']) ?></strong>
                      <?php if (!empty($n['tag'])): ?>
                        <span class="badge" style="margin-left:6px;"><?= htmlspecialchars($n['tag']) ?></span>
                      <?php endif; ?>
                    </div>
                    <div class="notif-time"><?= date('M j, g:ia', strtotime($n['created_at'])) ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
              <a href="feedback.php" class="notif-footer">View all feedback →</a>
            <?php else: ?>
              <div class="notif-empty">No notifications yet</div>
            <?php endif; ?>
          </div>
        </div>

        <a href="../profile.php" class="portal-avatar" title="<?= htmlspecialchars($userName) ?>"
           style="background:linear-gradient(135deg,<?= $avatarGradient ?>);">
          <?= htmlspecialchars($userInitial) ?>
        </a>
      </div>
    </div>

    <div class="portal-page">
      <nav class="portal-mobile-nav">
        <ul>
          <li><a class="<?= $page==='dashboard'?'active':'' ?>"  href="dashboard.php"><?= $icoDashboard ?><span>Home</span></a></li>
          <li><a class="<?= $page==='insights'?'active':'' ?>"   href="insights.php"><?= $icoInsights ?><span>Insights</span></a></li>
          <li><a class="<?= $page==='feedback'?'active':'' ?>"   href="feedback.php"><?= $icoFeedback ?><span>Feedback</span></a></li>
          <li><a class="<?= $page==='analytics'?'active':'' ?>"  href="analytics.php"><?= $icoAnalytics ?><span>Analytics</span></a></li>
          <li><a class="<?= $page==='profile'?'active':'' ?>"    href="../profile.php"><?= $icoProfile ?><span>Profile</span></a></li>
        </ul>
      </nav>