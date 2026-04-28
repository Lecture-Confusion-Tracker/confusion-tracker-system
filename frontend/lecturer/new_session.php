<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>New Session â€¢ Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
<?php
$page = 'dashboard';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../../backend/auth/includes/db.php';

$courses = $pdo->query("SELECT id, name FROM courses ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$success = isset($_GET['created']);
?>

<a href="dashboard.php" class="back-link">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
  Back to Dashboard
</a>

<div class="add-confusion-wrap" style="max-width:520px;">
  <div class="add-confusion-header">
    <div class="feature-icon" style="margin:0 auto 16px;">ðŸŽ“</div>
    <h2 style="margin-bottom:6px;">Start New Session</h2>
    <p>Share the session link with your students so they can submit confusion pings.</p>
  </div>

  <?php if ($success): ?>
    <div class="toast-success">âœ… Session created! Share the link below with your students.</div>
  <?php endif; ?>

  <form method="POST" action="new_session.php" id="session-form" novalidate>
    <div class="form-group">
      <label class="form-label" for="sess-course">Course <span class="required-star">*</span></label>
      <select class="form-select" id="sess-course" name="course_id">
        <option value="">Select a courseâ€¦</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label" for="sess-title">Session Title <span class="required-star">*</span></label>
      <input type="text" class="form-input" id="sess-title" name="title"
        placeholder="e.g. Week 7 â€” Recursion & Sorting" />
    </div>

    <div style="display:flex;gap:12px;margin-top:24px;">
      <a href="dashboard.php" class="btn btn-outline btn-lg" style="flex:1;text-align:center;">Cancel</a>
      <button type="submit" class="btn btn-primary btn-lg" style="flex:2;">Create Session</button>
    </div>
  </form>

  <?php
  // Handle POST â€” just show a shareable link (sessions table is optional for MVP)
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $courseId = (int)($_POST['course_id'] ?? 0);
      $title    = htmlspecialchars(trim($_POST['title'] ?? ''));
      if ($courseId && $title) {
          $code = strtoupper(substr(md5($title . time()), 0, 6));
          echo '<div class="panel" style="margin-top:24px;padding:20px;">
            <div class="panel-title" style="margin-bottom:8px;">Session Created âœ…</div>
            <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:12px;">Share this link with your students:</p>
            <div style="background:var(--bg);border:1px solid var(--border-purple);border-radius:var(--radius-md);padding:12px 16px;font-family:monospace;font-size:0.875rem;word-break:break-all;color:var(--primary);">
              ' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../student/dashboard.php?session=' . $code . '
            </div>
            <p style="font-size:0.8125rem;color:var(--text-light);margin-top:8px;">Session code: <strong>' . $code . '</strong></p>
          </div>';
      }
  }
  ?>
</div>

</div></main></div>
<script src="../assets/js/main.js"></script></body>
</html>

