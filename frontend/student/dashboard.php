<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard — Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>

<?php
require_once '../includes/auth_guard.php';
guardRole('student');
require_once '../../backend/auth/includes/db.php';

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? $_SESSION['username'] ?? 'Student';

// Get courses
$courses = $pdo->query("SELECT id, name FROM courses ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Selected course filter
$selected_course = isset($_GET['course']) ? (int)$_GET['course'] : 0;
$filter          = $_GET['filter'] ?? 'recent';

// Build query
$where  = $selected_course ? "WHERE c.course_id = $selected_course" : '';
$order  = $filter === 'votes' ? 'vote_count DESC, c.created_at DESC' : 'c.created_at DESC';

$confusions = $pdo->query("
    SELECT c.id, c.topic, c.description, c.tag, c.created_at, c.lecturer_feedback,
           co.name AS course_name, co.id AS course_id,
           COUNT(v.id) AS vote_count,
           MAX(CASE WHEN v.user_id = $user_id THEN 1 ELSE 0 END) AS user_voted
    FROM confusions c
    JOIN courses co ON c.course_id = co.id
    LEFT JOIN votes v ON v.confusion_id = c.id
    $where
    GROUP BY c.id
    ORDER BY $order
")->fetchAll(PDO::FETCH_ASSOC);

$submitted = isset($_GET['submitted']);

include '../includes/header.php';
?>

<main>
  <div class="container" style="padding:40px 0 80px;">

    <?php if ($submitted): ?>
      <div class="toast-success fade-up">
        ✅ Your confusion was submitted anonymously.
      </div>
    <?php endif; ?>

    <!-- Page header -->
    <div class="dash-page-header">
      <div>
        <h2 style="margin-bottom:4px;">Welcome back, <?= htmlspecialchars($user_name) ?> 👋</h2>
        <p style="margin:0;">Browse confusion topics, upvote what resonates, or add your own.</p>
      </div>
      <a href="add_confusion.php" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Add Confusion
      </a>
    </div>

    <!-- Controls -->
    <form method="GET" action="dashboard.php">
      <div class="dash-controls">
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
          <select class="form-select" name="course" style="max-width:220px;" onchange="this.form.submit()">
            <option value="">All Courses</option>
            <?php foreach ($courses as $c): ?>
              <option value="<?= $c['id'] ?>" <?= $selected_course == $c['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <div class="dash-filter-btns">
            <a href="?course=<?= $selected_course ?>&filter=recent"
               class="btn btn-outline filter-btn <?= $filter === 'recent' ? 'active' : '' ?>">
              Most Recent
            </a>
            <a href="?course=<?= $selected_course ?>&filter=votes"
               class="btn btn-outline filter-btn <?= $filter === 'votes' ? 'active' : '' ?>">
              Most Voted
            </a>
          </div>
        </div>
        <span class="dash-count"><?= count($confusions) ?> result<?= count($confusions) !== 1 ? 's' : '' ?></span>
      </div>
    </form>

    <!-- Confusion grid -->
    <div class="confusion-grid">

      <?php if (empty($confusions)): ?>
        <div class="confusion-empty">
          <div class="confusion-empty-icon">💬</div>
          <h3>No confusions yet</h3>
          <p>Be the first to submit a confusion topic for this course.</p>
          <a href="add_confusion.php" class="btn btn-primary" style="margin-top:16px;">Add First Confusion</a>
        </div>

      <?php else: ?>
        <?php foreach ($confusions as $i => $item): ?>
          <div class="confusion-card fade-up"
               style="transition-delay:<?= min($i * 0.05, 0.3) ?>s"
               data-votes="<?= (int)$item['vote_count'] ?>"
               data-course="<?= (int)$item['course_id'] ?>">

            <div class="confusion-card-top">
              <?php if ($item['tag']): ?>
                <span class="badge"><?= htmlspecialchars($item['tag']) ?></span>
              <?php endif; ?>
              <span class="confusion-course"><?= htmlspecialchars($item['course_name']) ?></span>
            </div>

            <h3><?= htmlspecialchars($item['topic']) ?></h3>
            <p><?= htmlspecialchars($item['description']) ?></p>

            <?php if (!empty($item['lecturer_feedback'])): ?>
              <div style="margin-top:8px;padding:10px 12px;border-radius:12px;border:1px solid var(--border);background:rgba(237,233,254,0.35);">
                <div style="font-weight:800;font-size:0.78rem;color:var(--primary);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:4px;">
                  Lecturer feedback
                </div>
                <div style="font-size:0.875rem;color:var(--text-muted);line-height:1.6;">
                  <?= nl2br(htmlspecialchars($item['lecturer_feedback'])) ?>
                </div>
              </div>
            <?php endif; ?>

            <div class="confusion-card-footer">
              <span class="confusion-meta">
                ⏱ <?= date('M j, g:ia', strtotime($item['created_at'])) ?>
              </span>
              <button class="btn btn-outline upvote-btn <?= $item['user_voted'] ? 'voted' : '' ?>"
                      data-id="<?= (int)$item['id'] ?>"
                      data-count="<?= (int)$item['vote_count'] ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                <span class="vote-count"><?= (int)$item['vote_count'] ?></span>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>

  </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
<script>
// Upvote via AJAX to backend/confusions/vote.php
document.querySelectorAll('.upvote-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    var id = btn.dataset.id;
    var formData = new FormData();
    formData.append('confusion_id', id);

    fetch('../../backend/confusions/vote.php', { method: 'POST', body: formData })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (data.success) {
          btn.querySelector('.vote-count').textContent = data.count;
          btn.classList.toggle('voted', data.voted);
        }
      })
      .catch(function() {
        // Fallback: optimistic UI if fetch fails
        var count = parseInt(btn.dataset.count);
        if (btn.classList.contains('voted')) {
          count--; btn.classList.remove('voted');
        } else {
          count++; btn.classList.add('voted');
        }
        btn.dataset.count = count;
        btn.querySelector('.vote-count').textContent = count;
      });
  });
});
</script>
</body>
</html>
