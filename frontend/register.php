<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account — Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
  <div class="auth-page">

    <!-- Left panel -->
    <div class="auth-left">
      <div>
        <div class="auth-left-logo">
          <div class="logo-icon">📊</div>
          <span>Lecture Confusion Tracker</span>
        </div>
        <h2>Start improving your lectures today.</h2>
        <p class="auth-left-desc">Join thousands of students and educators using real-time feedback to close the comprehension gap.</p>

        <div class="auth-feature-list">
          <div class="auth-feature-item">
            <span class="feat-icon">🔥</span>
            <div>
              <h4>Submit Confusion</h4>
              <p>Signal confusion instantly during a lecture — no disruption, no embarrassment.</p>
            </div>
          </div>
          <div class="auth-feature-item">
            <span class="feat-icon">🗳</span>
            <div>
              <h4>Vote on Topics</h4>
              <p>Upvote confusing topics so lecturers know exactly what to revisit.</p>
            </div>
          </div>
          <div class="auth-feature-item">
            <span class="feat-icon">📊</span>
            <div>
              <h4>View Insights</h4>
              <p>Lecturers get a clear post-session breakdown of comprehension hotspots.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="auth-trust">
        <div class="trust-avatars">
          <span>JD</span>
          <span>AM</span>
          <span>KL</span>
        </div>
        <span class="trust-text">Joined by 1,200+ Educators worldwide</span>
      </div>
    </div>

    <!-- Right panel -->
    <div class="auth-right">
      <div class="auth-form-wrap">

        <h2>Create your account</h2>
        <p>Free forever. No credit card required.</p>

        <?php
        if (!empty($_SESSION['register_error'])) {
          echo '<div class="form-error visible" style="margin-bottom:16px;">' . htmlspecialchars($_SESSION['register_error']) . '</div>';
          unset($_SESSION['register_error']);
        }
        ?>

        <form id="register-form" method="POST" action="../backend/auth/register.php" novalidate style="margin-top:24px;">

          <div class="form-group">
            <label class="form-label" for="reg-name">Full Name</label>
            <div class="input-wrap">
              <span class="input-icon">👤</span>
              <input
                type="text"
                id="reg-name"
                name="name"
                class="form-input has-icon"
                placeholder="Jane Doe"
                autocomplete="name"
                value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
              />
            </div>
            <span class="form-error" id="reg-name-error"></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="reg-email">Institutional Email</label>
            <div class="input-wrap">
              <span class="input-icon">✉</span>
              <input
                type="email"
                id="reg-email"
                name="email"
                class="form-input has-icon"
                placeholder="name@university.edu"
                autocomplete="email"
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
              />
            </div>
            <span class="form-error" id="reg-email-error"></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="reg-password">Password</label>
            <div class="input-wrap">
              <span class="input-icon">🔒</span>
              <input
                type="password"
                id="reg-password"
                name="password"
                class="form-input has-icon"
                placeholder="Min. 6 characters"
                autocomplete="new-password"
                style="padding-right:42px;"
              />
              <button type="button" class="toggle-password" aria-label="Toggle password visibility" data-target="reg-password">
                <svg id="eye-icon-reg" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <span class="form-error" id="reg-password-error"></span>
            <div class="password-strength">
              <div class="strength-bar"><div class="strength-fill"></div></div>
              <span class="strength-label"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="reg-role">I am a</label>
            <select id="reg-role" name="role" class="form-select">
              <option value="">Select your role…</option>
              <option value="student" <?php echo (($_POST['role'] ?? '') === 'student') ? 'selected' : ''; ?>>Student</option>
              <option value="lecturer" <?php echo (($_POST['role'] ?? '') === 'lecturer') ? 'selected' : ''; ?>>Lecturer</option>
            </select>
            <span class="form-error" id="reg-role-error"></span>
          </div>

          <div class="checkbox-row">
            <input type="checkbox" id="terms" name="terms" value="1" required />
            <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
          </div>

          <button type="submit" id="register-btn" class="btn btn-primary btn-full btn-lg">
            Create Account
          </button>
        </form>

        <p class="form-footer">
          Already have an account? <a href="login.php">Sign in</a>
        </p>

        <div class="auth-page-footer">
          &copy; 2026 Lecture Confusion Tracker &nbsp;
          <a href="support.php">Support</a>
          <a href="privacy.php">Privacy</a>
        </div>

      </div>
    </div>

  </div>
</main>

<script src="assets/js/main.js"></script>
</body>
</html>
