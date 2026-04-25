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
/* ── AUTH GUARD ──────────────────────────────────────────────────────────────
   Uncomment the two lines below once the backend is ready.
   The guard will redirect unauthenticated users to login.php automatically.
   ─────────────────────────────────────────────────────────────────────────── */
// require_once '../includes/auth_guard.php';
// guardRole('student');

/* ── SESSION / USER DATA ─────────────────────────────────────────────────────
   BACKEND DEV: Replace these placeholders with real session values.
   e.g. $userName = $_SESSION['user_name'];
   ─────────────────────────────────────────────────────────────────────────── */
$userName   = $_SESSION['user_name']  ?? 'Student';
$userAvatar = strtoupper(substr($userName, 0, 1));

/* ── CONFUSION POSTS ─────────────────────────────────────────────────────────
   BACKEND DEV: Replace $confusions with a real DB query, e.g.:
   
   $stmt = $pdo->prepare("
       SELECT c.id, c.topic, c.description, c.tag, c.created_at,
              co.name AS course_name, co.slug AS course_slug,
              COUNT(v.id) AS vote_count
       FROM confusions c
       JOIN courses co ON c.course_id = co.id
       LEFT JOIN votes v ON v.confusion_id = c.id
       WHERE c.session_id = :session_id
       GROUP BY c.id
       ORDER BY c.created_at DESC
   ");
   $stmt->execute([':session_id' => $_SESSION['active_session_id']]);
   $confusions = $stmt->fetchAll(PDO::FETCH_ASSOC);
   ─────────────────────────────────────────────────────────────────────────── */
$confusions = []; // BACKEND: replace with real query result

/* ── COURSES ─────────────────────────────────────────────────────────────────
   BACKEND DEV: Replace with real courses from DB.
   $courses = $pdo->query("SELECT id, name, slug FROM courses ORDER BY name")->fetchAll();
   ─────────────────────────────────────────────────────────────────────────── */
$courses = []; // BACKEND: replace with real courses

include '../includes/header.php';
?>

<main>
  <div class="container" style="padding: 40px 0 80px;">

    <!-- Page header -->
    <div class="dash-page-header">
      <div>
        <h2 style="margin-bottom:4px;">
          Welcome back, <?= htmlspecialchars($userName) ?> 👋
        </h2>
        <p style="margin:0;">Browse confusion topics, upvote what resonates, or add your own.</p>
      </div>
      <a href="add_confusion.php" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Add Confusion
      </a>
    </div>

    <!-- Controls bar -->
    <div class="dash-controls">
      <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <!-- BACKEND DEV: populate <option> tags from $courses array -->
        <select class="form-select" id="course-filter" style="max-width:220px;">
          <option value="">All Courses</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= htmlspecialchars($c['slug']) ?>">
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
          <!-- Fallback options shown until backend is wired -->
          <?php if (empty($courses)): ?>
            <option value="web">Web Development</option>
            <option value="db">Database Systems</option>
            <option value="algo">Algorithms</option>
          <?php endif; ?>
        </select>

        <div class="dash-filter-btns">
          <button class="btn btn-outline filter-btn active" data-filter="recent">Most Recent</button>
          <button class="btn btn-outline filter-btn" data-filter="votes">Most Voted</button>
        </div>
      </div>

      <span class="dash-count" id="result-count"></span>
    </div>

    <!-- Confusion grid -->
    <div id="confusion-list" class="confusion-grid">

      <?php if (!empty($confusions)): ?>
        <?php foreach ($confusions as $item): ?>
          <!-- BACKEND: each $item has: id, topic, description, tag, course_name, course_slug, vote_count, created_at -->
          <div class="confusion-card fade-up"
               data-votes="<?= (int)$item['vote_count'] ?>"
               data-time="<?= strtotime($item['created_at']) ?>"
               data-course="<?= htmlspecialchars($item['course_slug']) ?>">
            <div class="confusion-card-top">
              <?php if (!empty($item['tag'])): ?>
                <span class="badge"><?= htmlspecialchars($item['tag']) ?></span>
              <?php endif; ?>
              <span class="confusion-course"><?= htmlspecialchars($item['course_name']) ?></span>
            </div>
            <h3><?= htmlspecialchars($item['topic']) ?></h3>
            <p><?= htmlspecialchars($item['description']) ?></p>
            <div class="confusion-card-footer">
              <span class="confusion-meta">
                ⏱ <?= htmlspecialchars(date('M j, g:ia', strtotime($item['created_at']))) ?>
              </span>
              <!-- BACKEND: wire upvote to POST /student/upvote.php with confusion_id -->
              <button class="btn btn-outline upvote-btn"
                      data-id="<?= (int)$item['id'] ?>"
                      data-count="<?= (int)$item['vote_count'] ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                <span class="vote-count"><?= (int)$item['vote_count'] ?></span>
              </button>
            </div>
          </div>
        <?php endforeach; ?>

      <?php else: ?>
        <!-- Empty state — shown until backend returns real data -->
        <div class="confusion-empty" id="empty-state">
          <div class="confusion-empty-icon">💬</div>
          <h3>No confusions yet</h3>
          <p>Be the first to submit a confusion topic for your course.</p>
          <a href="add_confusion.php" class="btn btn-primary" style="margin-top:16px;">Add First Confusion</a>
        </div>

        <!-- ── DEMO CARDS (remove once backend returns real data) ── -->
        <div class="confusion-card fade-up demo-card" data-votes="12" data-time="1" data-course="web">
          <div class="confusion-card-top">
            <span class="badge">Concept</span>
            <span class="confusion-course">Web Development</span>
          </div>
          <h3>Recursion Base Case</h3>
          <p>I don't understand how recursion knows when to stop at the base case.</p>
          <div class="confusion-card-footer">
            <span class="confusion-meta">⏱ 2 hours ago</span>
            <button class="btn btn-outline upvote-btn" data-id="1" data-count="12">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
              <span class="vote-count">12</span>
            </button>
          </div>
        </div>

        <div class="confusion-card fade-up demo-card" style="transition-delay:.05s" data-votes="20" data-time="2" data-course="db">
          <div class="confusion-card-top">
            <span class="badge">Logic</span>
            <span class="confusion-course">Database Systems</span>
          </div>
          <h3>While vs Do-While Loops</h3>
          <p>The difference between while and do-while is still confusing, especially when the condition is checked.</p>
          <div class="confusion-card-footer">
            <span class="confusion-meta">⏱ 4 hours ago</span>
            <button class="btn btn-outline upvote-btn" data-id="2" data-count="20">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
              <span class="vote-count">20</span>
            </button>
          </div>
        </div>

        <div class="confusion-card fade-up demo-card" style="transition-delay:.1s" data-votes="7" data-time="3" data-course="web">
          <div class="confusion-card-top">
            <span class="badge">Equation</span>
            <span class="confusion-course">Web Development</span>
          </div>
          <h3>CSS Specificity Rules</h3>
          <p>How does the browser decide which CSS rule wins when multiple selectors target the same element?</p>
          <div class="confusion-card-footer">
            <span class="confusion-meta">⏱ 6 hours ago</span>
            <button class="btn btn-outline upvote-btn" data-id="3" data-count="7">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
              <span class="vote-count">7</span>
            </button>
          </div>
        </div>

        <div class="confusion-card fade-up demo-card" style="transition-delay:.15s" data-votes="15" data-time="4" data-course="algo">
          <div class="confusion-card-top">
            <span class="badge">Concept</span>
            <span class="confusion-course">Algorithms</span>
          </div>
          <h3>Big O Notation</h3>
          <p>O(n log n) for merge sort doesn't make intuitive sense to me yet.</p>
          <div class="confusion-card-footer">
            <span class="confusion-meta">⏱ 1 day ago</span>
            <button class="btn btn-outline upvote-btn" data-id="4" data-count="15">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
              <span class="vote-count">15</span>
            </button>
          </div>
        </div>

        <div class="confusion-card fade-up demo-card" style="transition-delay:.2s" data-votes="3" data-time="5" data-course="db">
          <div class="confusion-card-top">
            <span class="badge">Query</span>
            <span class="confusion-course">Database Systems</span>
          </div>
          <h3>SQL JOIN Types</h3>
          <p>When should I use LEFT JOIN vs INNER JOIN? The Venn diagram didn't click for me.</p>
          <div class="confusion-card-footer">
            <span class="confusion-meta">⏱ 2 days ago</span>
            <button class="btn btn-outline upvote-btn" data-id="5" data-count="3">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
              <span class="vote-count">3</span>
            </button>
          </div>
        </div>

        <div class="confusion-card fade-up demo-card" style="transition-delay:.25s" data-votes="9" data-time="6" data-course="algo">
          <div class="confusion-card-top">
            <span class="badge">Logic</span>
            <span class="confusion-course">Algorithms</span>
          </div>
          <h3>Dynamic Programming</h3>
          <p>I can follow memoisation examples but can't identify when to use DP on a new problem.</p>
          <div class="confusion-card-footer">
            <span class="confusion-meta">⏱ 3 days ago</span>
            <button class="btn btn-outline upvote-btn" data-id="6" data-count="9">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
              <span class="vote-count">9</span>
            </button>
          </div>
        </div>
        <!-- ── END DEMO CARDS ── -->

      <?php endif; ?>

    </div>

  </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
</body>
</html>
