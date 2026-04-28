<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Feedback • Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
<?php
$page = 'feedback';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../../backend/auth/includes/db.php';

// All confusions with vote counts
$selected_course = isset($_GET['course']) ? (int)$_GET['course'] : 0;
$where = $selected_course ? "WHERE c.course_id = $selected_course" : '';

$confusions = $pdo->query("
    SELECT c.id, c.topic, c.description, c.tag, c.created_at, c.lecturer_feedback,
           co.name AS course_name,
           COUNT(v.id) AS vote_count
    FROM confusions c
    JOIN courses co ON c.course_id = co.id
    LEFT JOIN votes v ON v.confusion_id = c.id
    $where
    GROUP BY c.id
    ORDER BY vote_count DESC, c.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

$courses = $pdo->query("SELECT id, name FROM courses ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
  <div>
    <h1 class="portal-page-title" style="margin:0;">Student Feedback</h1>
    <p class="portal-subtitle" style="margin:0;">All confusion submissions from students</p>
  </div>
  <form method="GET">
    <select class="form-select" name="course" onchange="this.form.submit()" style="max-width:200px;">
      <option value="">All Courses</option>
      <?php foreach ($courses as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $selected_course == $c['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>
</div>

<?php if (empty($confusions)): ?>
  <div class="confusion-empty">
    <div class="confusion-empty-icon">💬</div>
    <h3>No feedback yet</h3>
    <p>Students haven't submitted any confusion topics yet.</p>
  </div>
<?php else: ?>
<div class="panel">
  <table class="table">
    <thead>
      <tr>
        <th style="width:30%;">Topic</th>
        <th>Course</th>
        <th>Description</th>
        <th>Tag</th>
        <th style="width:28%;">Lecturer feedback</th>
        <th style="text-align:right;">Votes</th>
        <th style="text-align:right;">Submitted</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($confusions as $row): ?>
      <tr>
        <td><strong><?= htmlspecialchars($row['topic']) ?></strong></td>
        <td class="muted"><?= htmlspecialchars($row['course_name']) ?></td>
        <td style="font-size:0.875rem;color:var(--text-muted);max-width:260px;">
          <?= htmlspecialchars(mb_strimwidth($row['description'], 0, 80, 'â€¦')) ?>
        </td>
        <td>
          <?php if ($row['tag']): ?>
            <span class="badge"><?= htmlspecialchars($row['tag']) ?></span>
          <?php else: ?>
            <span class="muted">â€”</span>
          <?php endif; ?>
        </td>
        <td>
          <form class="feedback-form" data-id="<?= (int)$row['id'] ?>">
            <textarea
              class="form-input"
              name="feedback"
              rows="2"
              style="resize:vertical;min-height:44px;font-size:0.875rem;"
              placeholder="Add feedback students will see…"
            ><?= htmlspecialchars($row['lecturer_feedback'] ?? '') ?></textarea>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;">
              <button type="submit" class="btn btn-outline" style="padding:7px 12px;">Save</button>
              <span class="muted feedback-status" style="align-self:center;display:none;">Saved</span>
            </div>
          </form>
        </td>
        <td style="text-align:right;font-weight:700;color:var(--primary);"><?= (int)$row['vote_count'] ?></td>
        <td style="text-align:right;" class="muted"><?= date('M j, g:ia', strtotime($row['created_at'])) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

</div></main></div>
<script src="../assets/js/main.js"></script>
<script>
document.querySelectorAll('.feedback-form').forEach(function (form) {
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var id = form.dataset.id;
    var textarea = form.querySelector('textarea[name="feedback"]');
    var status = form.querySelector('.feedback-status');
    var btn = form.querySelector('button[type="submit"]');
    if (!id || !textarea) return;

    var fd = new FormData();
    fd.append('confusion_id', id);
    fd.append('feedback', textarea.value || '');

    btn.disabled = true;
    fetch('../../backend/confusions/save_feedback.php', { method: 'POST', body: fd })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        btn.disabled = false;
        if (data && data.success) {
          if (status) {
            status.style.display = 'inline';
            status.textContent = 'Saved';
            setTimeout(function () { status.style.display = 'none'; }, 1600);
          }
        } else {
          alert((data && data.message) ? data.message : 'Could not save feedback.');
        }
      })
      .catch(function () {
        btn.disabled = false;
        alert('Network error while saving feedback.');
      });
  });
});
</script>
</body>
</html>

