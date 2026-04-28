<?php
require_once __DIR__ . '/includes/auth_guard.php';
guardRole('');
require_once __DIR__ . '/../backend/auth/includes/db.php';

$userId   = (int)($_SESSION['user_id'] ?? 0);
$userRole = $_SESSION['user_role'] ?? ($_SESSION['role'] ?? '');

$user = null;
try {
    $stmt = $pdo->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $e) {}

$displayName = $user['username'] ?? ($_SESSION['user_name'] ?? ($_SESSION['username'] ?? 'User'));
$email       = $user['email']    ?? ($_SESSION['email'] ?? '');
$role        = $user['role']     ?? $userRole;
$joinedAt    = $user['created_at'] ?? null;
$initial     = strtoupper(substr(trim((string)$displayName), 0, 1));

// Generate a consistent gradient color from username
$colors = ['#6366f1,#8b5cf6','#ec4899,#f43f5e','#14b8a6,#06b6d4','#f59e0b,#ef4444','#10b981,#3b82f6'];
$colorIdx = abs(crc32($displayName)) % count($colors);
$avatarGradient = $colors[$colorIdx];

// Stats
$stats = [];
try {
    if ($role === 'student') {
        $submitted = (int)$pdo->query("SELECT COUNT(*) FROM confusions WHERE user_id = $userId")->fetchColumn();
        $votesGiven = (int)$pdo->query("SELECT COUNT(*) FROM votes WHERE user_id = $userId")->fetchColumn();
        $votesRecv  = (int)$pdo->query("SELECT COUNT(*) FROM votes v JOIN confusions c ON c.id=v.confusion_id WHERE c.user_id=$userId")->fetchColumn();
        $stats = [
            ['label'=>'Confusions submitted','value'=>$submitted,'icon'=>'💬'],
            ['label'=>'Votes received',       'value'=>$votesRecv, 'icon'=>'👍'],
            ['label'=>'Votes given',           'value'=>$votesGiven,'icon'=>'🗳'],
        ];
    } elseif ($role === 'lecturer') {
        $engaged  = (int)$pdo->query("SELECT COUNT(DISTINCT user_id) FROM confusions")->fetchColumn();
        $analyzed = (int)$pdo->query("SELECT COUNT(*) FROM confusions")->fetchColumn();
        $topics   = (int)$pdo->query("SELECT COUNT(DISTINCT topic) FROM confusions")->fetchColumn();
        $stats = [
            ['label'=>'Students engaged',    'value'=>$engaged, 'icon'=>'🎓'],
            ['label'=>'Confusions analyzed', 'value'=>$analyzed,'icon'=>'📊'],
            ['label'=>'Unique topics',        'value'=>$topics,  'icon'=>'🔖'],
        ];
    }
} catch (Throwable $e) {}

// Handle password change
$pwSuccess = false;
$pwError   = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password']     ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($current) || empty($new) || empty($confirm)) {
        $pwError = 'All password fields are required.';
    } elseif (strlen($new) < 6) {
        $pwError = 'New password must be at least 6 characters.';
    } elseif ($new !== $confirm) {
        $pwError = 'New passwords do not match.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $row = $stmt->fetch();
            if ($row && password_verify($current, $row['password'])) {
                $hashed = password_hash($new, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hashed, $userId]);
                $pwSuccess = true;
            } else {
                $pwError = 'Current password is incorrect.';
            }
        } catch (Throwable $e) {
            $pwError = 'Could not update password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile — Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main>
  <div class="container" style="padding:48px 0 96px; max-width:860px;">

    <!-- Page title -->
    <div style="margin-bottom:32px;">
      <h2 style="margin-bottom:4px;">My Profile</h2>
      <p>Manage your account details and security settings.</p>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

      <!-- ── Left column ── -->
      <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Avatar card -->
        <div class="profile-card">
          <div class="profile-avatar-wrap">
            <div class="profile-avatar" style="background:linear-gradient(135deg,<?= $avatarGradient ?>);">
              <?= htmlspecialchars($initial) ?>
            </div>
            <div class="profile-avatar-ring" style="background:linear-gradient(135deg,<?= $avatarGradient ?>);"></div>
          </div>
          <div class="profile-name"><?= htmlspecialchars($displayName) ?></div>
          <div class="profile-email"><?= htmlspecialchars($email) ?></div>
          <span class="profile-role-badge profile-role-<?= $role ?>"><?= ucfirst($role) ?></span>

          <div class="profile-meta">
            <div class="profile-meta-row">
              <span>Member since</span>
              <strong><?= $joinedAt ? date('M j, Y', strtotime($joinedAt)) : '—' ?></strong>
            </div>
            <div class="profile-meta-row">
              <span>Account type</span>
              <strong><?= ucfirst($role) ?></strong>
            </div>
          </div>
        </div>

        <!-- Stats card -->
        <div class="panel" style="padding:20px;">
          <div class="panel-head" style="margin-bottom:16px;">
            <div class="panel-title">Activity Stats</div>
            <span class="chip">All time</span>
          </div>
          <?php if (empty($stats)): ?>
            <p class="muted" style="font-size:0.875rem;">No activity yet.</p>
          <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:14px;">
              <?php foreach ($stats as $s): ?>
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                  <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:1.1rem;"><?= $s['icon'] ?></span>
                    <span class="muted" style="font-size:0.9rem;"><?= htmlspecialchars($s['label']) ?></span>
                  </div>
                  <span style="font-weight:800;font-size:1.125rem;color:var(--primary);"><?= number_format((int)$s['value']) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

      </div>

      <!-- ── Right column: Change Password ── -->
      <div class="panel" style="padding:28px;">
        <div style="margin-bottom:24px;">
          <h3 style="margin-bottom:4px;font-size:1.125rem;">Change Password</h3>
          <p style="font-size:0.875rem;">Choose a strong password with at least 6 characters.</p>
        </div>

        <?php if ($pwSuccess): ?>
          <div class="toast-success" style="margin-bottom:20px;">
            ✅ Password updated successfully.
          </div>
        <?php endif; ?>

        <?php if ($pwError): ?>
          <div class="form-error visible" style="margin-bottom:20px;font-size:0.9rem;">
            ⚠️ <?= htmlspecialchars($pwError) ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="profile.php" id="pw-form" novalidate>
          <input type="hidden" name="change_password" value="1" />

          <div class="form-group">
            <label class="form-label" for="current_password">Current Password</label>
            <div class="input-wrap">
              <span class="input-icon">🔒</span>
              <input type="password" class="form-input has-icon" id="current_password"
                name="current_password" placeholder="Enter current password"
                style="padding-right:42px;" autocomplete="current-password" />
              <button type="button" class="toggle-password" data-target="current_password">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <span class="form-error" id="cur-pw-error"></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="new_password">New Password</label>
            <div class="input-wrap">
              <span class="input-icon">🔑</span>
              <input type="password" class="form-input has-icon" id="new_password"
                name="new_password" placeholder="Min. 6 characters"
                style="padding-right:42px;" autocomplete="new-password" />
              <button type="button" class="toggle-password" data-target="new_password">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <div class="password-strength">
              <div class="strength-bar"><div class="strength-fill"></div></div>
              <span class="strength-label"></span>
            </div>
            <span class="form-error" id="new-pw-error"></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="confirm_password">Confirm New Password</label>
            <div class="input-wrap">
              <span class="input-icon">🔑</span>
              <input type="password" class="form-input has-icon" id="confirm_password"
                name="confirm_password" placeholder="Repeat new password"
                style="padding-right:42px;" autocomplete="new-password" />
              <button type="button" class="toggle-password" data-target="confirm_password">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <span class="form-error" id="confirm-pw-error"></span>
          </div>

          <button type="submit" id="pw-btn" class="btn btn-primary btn-full btn-lg" style="margin-top:8px;">
            Update Password
          </button>
        </form>

        <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
          <p style="font-size:0.8125rem;color:var(--text-light);">
            Want to leave? <a href="../backend/auth/logout.php" style="color:var(--error);font-weight:600;">Sign out</a>
          </p>
        </div>
      </div>

    </div>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
<script>
// Profile password form validation
var pwForm = document.getElementById('pw-form');
if (pwForm) {
  var newPw = document.getElementById('new_password');
  if (newPw) {
    newPw.addEventListener('input', function () {
      var ps = document.querySelector('.password-strength');
      if (ps && this.value.length > 0) ps.classList.add('visible');
    });
  }

  pwForm.addEventListener('submit', function (e) {
    var ok = true;
    var cur     = document.getElementById('current_password');
    var newP    = document.getElementById('new_password');
    var confirm = document.getElementById('confirm_password');

    if (!cur.value.trim()) {
      cur.classList.add('error');
      document.getElementById('cur-pw-error').textContent = 'Current password is required';
      document.getElementById('cur-pw-error').classList.add('visible');
      ok = false;
    } else { cur.classList.remove('error'); document.getElementById('cur-pw-error').classList.remove('visible'); }

    if (newP.value.length < 6) {
      newP.classList.add('error');
      document.getElementById('new-pw-error').textContent = 'Password must be at least 6 characters';
      document.getElementById('new-pw-error').classList.add('visible');
      ok = false;
    } else { newP.classList.remove('error'); document.getElementById('new-pw-error').classList.remove('visible'); }

    if (confirm.value !== newP.value) {
      confirm.classList.add('error');
      document.getElementById('confirm-pw-error').textContent = 'Passwords do not match';
      document.getElementById('confirm-pw-error').classList.add('visible');
      ok = false;
    } else { confirm.classList.remove('error'); document.getElementById('confirm-pw-error').classList.remove('visible'); }

    if (!ok) { e.preventDefault(); return; }
    var btn = document.getElementById('pw-btn');
    if (btn) { btn.classList.add('btn-loading'); btn.disabled = true; }
  });
}
</script>
</body>
</html>
