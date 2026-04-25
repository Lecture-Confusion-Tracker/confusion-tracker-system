<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Confusion — Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>

<?php
/* ── AUTH GUARD ──────────────────────────────────────────────────────────────
   Uncomment once backend is ready.
   ─────────────────────────────────────────────────────────────────────────── */
// require_once '../includes/auth_guard.php';
// guardRole('student');

if (session_status() === PHP_SESSION_NONE) session_start();

/* ── FORM SUBMISSION ─────────────────────────────────────────────────────────
   BACKEND DEV: Replace this block with real DB insert, e.g.:
   
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $stmt = $pdo->prepare("
           INSERT INTO confusions (course_id, topic, description, tag, student_id, session_id)
           VALUES (:course_id, :topic, :description, :tag, :student_id, :session_id)
       ");
       $stmt->execute([
           ':course_id'  => (int)$_POST['course'],
           ':topic'      => trim($_POST['topic']),
           ':description'=> trim($_POST['description']),
           ':tag'        => trim($_POST['tag'] ?? ''),
           ':student_id' => $_SESSION['user_id'],
           ':session_id' => $_SESSION['active_session_id'] ?? null,
       ]);
       header('Location: dashboard.php?submitted=1');
       exit;
   }
   ─────────────────────────────────────────────────────────────────────────── */

/* ── SUCCESS REDIRECT ────────────────────────────────────────────────────────
   BACKEND DEV: After real insert, redirect to dashboard with ?submitted=1
   Frontend reads this flag and shows the toast notification.
   ─────────────────────────────────────────────────────────────────────────── */
$submitted = isset($_GET['submitted']);

/* ── COURSES ─────────────────────────────────────────────────────────────────
   BACKEND DEV: Replace with real DB query.
   $courses = $pdo->query("SELECT id, name FROM courses ORDER BY name")->fetchAll();
   ─────────────────────────────────────────────────────────────────────────── */
$courses = []; // BACKEND: replace with real courses

include '../includes/header.php';
?>

<main>
  <div class="container" style="padding: 48px 0 80px;">

    <a href="dashboard.php" class="back-link">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Back to Dashboard
    </a>

    <div class="add-confusion-wrap">

      <div class="add-confusion-header">
        <div class="feature-icon" style="margin:0 auto 16px;">💬</div>
        <h2 style="margin-bottom:6px;">Add Confusion</h2>
        <p>Your submission is <strong>anonymous</strong> to your lecturer — just describe what wasn't clear.</p>
      </div>

      <?php if ($submitted): ?>
        <!-- Success state — shown after real backend redirect -->
        <div class="confusion-submitted">
          <div class="success-icon">✅</div>
          <h3>Confusion Submitted!</h3>
          <p>Your confusion has been added anonymously. Your lecturer will review it after the session.</p>
          <div style="display:flex; gap:12px; justify-content:center; margin-top:24px; flex-wrap:wrap;">
            <a href="add_confusion.php" class="btn btn-outline">Add Another</a>
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
          </div>
        </div>

      <?php else: ?>

        <!-- BACKEND DEV: action points to your PHP handler, or keep as add_confusion.php -->
        <form id="add-confusion-form" method="POST" action="add_confusion.php" novalidate>

          <div class="form-group">
            <label class="form-label" for="course">Course <span class="required-star">*</span></label>
            <!-- BACKEND DEV: loop $courses here -->
            <select class="form-select" id="course" name="course">
              <option value="">Select a course…</option>
              <?php foreach ($courses as $c): ?>
                <option value="<?= (int)$c['id'] ?>"
                  <?= (($_POST['course'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['name']) ?>
                </option>
              <?php endforeach; ?>
              <?php if (empty($courses)): ?>
                <option value="1">Web Development</option>
                <option value="2">Database Systems</option>
                <option value="3">Algorithms</option>
              <?php endif; ?>
            </select>
            <span class="form-error" id="course-error"></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="topic">Lecture Topic <span class="required-star">*</span></label>
            <input type="text" class="form-input" id="topic" name="topic"
              placeholder="e.g. Recursion, SQL Joins, CSS Flexbox"
              value="<?= htmlspecialchars($_POST['topic'] ?? '') ?>" />
            <span class="form-error" id="topic-error"></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="description">What confused you? <span class="required-star">*</span></label>
            <textarea class="form-input" id="description" name="description"
              rows="5" maxlength="500"
              placeholder="Describe the concept, equation, or explanation that wasn't clear…"
              style="resize:vertical;"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            <div style="display:flex; justify-content:space-between; margin-top:5px;">
              <span class="form-error" id="desc-error"></span>
              <span class="form-hint" id="char-count" style="margin-left:auto;">0 / 500</span>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              Tag
              <span style="font-weight:400; color:var(--text-light); font-size:0.8125rem;">(optional)</span>
            </label>
            <div class="tag-options">
              <?php
              $tags = ['Concept', 'Equation', 'Logic', 'Query', 'Other'];
              $selectedTag = $_POST['tag'] ?? '';
              foreach ($tags as $tag):
              ?>
                <label class="tag-option">
                  <input type="radio" name="tag" value="<?= $tag ?>"
                    <?= $selectedTag === $tag ? 'checked' : '' ?> />
                  <span><?= $tag ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div style="display:flex; gap:12px; margin-top:24px;">
            <a href="dashboard.php" class="btn btn-outline btn-lg" style="flex:1; text-align:center;">Cancel</a>
            <button type="submit" id="submit-btn" class="btn btn-primary btn-lg" style="flex:2;">
              Submit Confusion
            </button>
          </div>

        </form>

      <?php endif; ?>

    </div>

  </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
</body>
</html>
