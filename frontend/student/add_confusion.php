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
require_once '../includes/auth_guard.php';
guardRole('student');
require_once '../../backend/auth/includes/db.php';

// Get courses for dropdown
$courses = $pdo->query("SELECT id, name FROM courses ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Show form error if any
$formError = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);

include '../includes/header.php';
?>

<main>
  <div class="container" style="padding:48px 0 80px;">

    <a href="dashboard.php" class="back-link">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Back to Dashboard
    </a>

    <div class="add-confusion-wrap">

      <div class="add-confusion-header">
        <div class="feature-icon" style="margin:0 auto 16px;">💬</div>
        <h2 style="margin-bottom:6px;">Add Confusion</h2>
        <p>Your submission is <strong>anonymous</strong> to your lecturer.</p>
      </div>

      <?php if ($formError): ?>
        <div class="form-error visible" style="margin-bottom:20px;font-size:0.9rem;">
          ⚠️ <?= htmlspecialchars($formError) ?>
        </div>
      <?php endif; ?>

      <form id="add-confusion-form" method="POST"
            action="../../backend/confusions/add_confusion.php" novalidate>

        <div class="form-group">
          <label class="form-label" for="course">
            Course <span class="required-star">*</span>
          </label>
          <select class="form-select" id="course" name="course">
            <option value="">Select a course…</option>
            <?php foreach ($courses as $c): ?>
              <option value="<?= (int)$c['id'] ?>"
                <?= (($_POST['course'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <span class="form-error" id="course-error"></span>
        </div>

        <div class="form-group">
          <label class="form-label" for="topic">
            Lecture Topic <span class="required-star">*</span>
          </label>
          <input type="text" class="form-input" id="topic" name="topic"
            placeholder="e.g. Recursion, SQL Joins, CSS Flexbox"
            value="<?= htmlspecialchars($_POST['topic'] ?? '') ?>" />
          <span class="form-error" id="topic-error"></span>
        </div>

        <div class="form-group">
          <label class="form-label" for="description">
            What confused you? <span class="required-star">*</span>
          </label>
          <textarea class="form-input" id="description" name="description"
            rows="5" maxlength="500"
            placeholder="Describe the concept, equation, or explanation that wasn't clear…"
            style="resize:vertical;"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
          <div style="display:flex;justify-content:space-between;margin-top:5px;">
            <span class="form-error" id="desc-error"></span>
            <span class="form-hint" id="char-count" style="margin-left:auto;">0 / 500</span>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">
            Tag
            <span style="font-weight:400;color:var(--text-light);font-size:0.8125rem;">(optional)</span>
          </label>
          <div class="tag-options">
            <?php foreach (['Concept','Equation','Logic','Query','Other'] as $tag): ?>
              <label class="tag-option">
                <input type="radio" name="tag" value="<?= $tag ?>"
                  <?= (($_POST['tag'] ?? '') === $tag) ? 'checked' : '' ?> />
                <span><?= $tag ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
          <a href="dashboard.php" class="btn btn-outline btn-lg" style="flex:1;text-align:center;">Cancel</a>
          <button type="submit" id="submit-btn" class="btn btn-primary btn-lg" style="flex:2;">
            Submit Confusion
          </button>
        </div>

      </form>
    </div>

  </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/main.js"></script>
</body>
</html>
