<?php include '../includes/header.php'; ?>

<main class="container" style="padding:40px 0;">

  <h2 style="margin-bottom:20px;">Add Confusion</h2>

  <form class="feature-card" style="max-width:500px; margin:auto;" method="POST">

    <div class="form-group">
      <label class="form-label">Course</label>
      <select class="form-select" name="course">
        <option>Web Development</option>
        <option>Database Systems</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label">Lecture Topic</label>
      <input type="text" class="form-input" name="topic" placeholder="e.g. Recursion" />
    </div>

    <div class="form-group">
      <label class="form-label">What confused you?</label>
      <textarea class="form-input" name="description" rows="4"></textarea>
    </div>

    <div class="form-group">
      <label class="form-label">Tag (optional)</label>
      <input type="text" class="form-input" name="tag" placeholder="Concept / Equation" />
    </div>

    <button class="btn btn-primary btn-full">Submit</button>

  </form>

</main>