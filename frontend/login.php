<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Lecture Confusion Tracker</title>
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
        <h2>Bridge the gap between teaching and understanding.</h2>
        <p class="auth-left-desc">Real-time feedback loops for the modern lecture hall.</p>

        <div class="auth-feature-list">
          <div class="auth-feature-item">
            <span class="feat-icon">📈</span>
            <div>
              <h4>Actionable Insights</h4>
              <p>Visualise student confusion hotspots across your lecture timeline.</p>
            </div>
          </div>
          <div class="auth-feature-item">
            <span class="feat-icon">💬</span>
            <div>
              <h4>Silent Interaction</h4>
              <p>Empower students to signal confusion without disrupting the flow.</p>
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

        <h2>Welcome Back</h2>
        <p>Choose your role to continue to your dashboard.</p>

        <!-- Role selector -->
        <div class="role-selector" style="margin-top:20px;">
          <button type="button" class="role-btn active" data-role="student">
            <span class="role-icon">🎓</span>
            <span>Student</span>
          </button>
          <button type="button" class="role-btn" data-role="lecturer">
            <span class="role-icon">👤</span>
            <span>Lecturer</span>
          </button>
        </div>
        <input type="hidden" id="role-input" name="role" value="student" />

        <?php
        // Display server-side errors if any
        if (!empty($_SESSION['login_error'])) {
          echo '<div class="form-error visible" style="margin-bottom:16px;">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
          unset($_SESSION['login_error']);
        }
        ?>

        <form id="login-form" method="POST" action="login.php" novalidate>
          <input type="hidden" name="role" id="role-field" value="student" />

          <div class="form-group">
            <label class="form-label" for="login-email">Institutional Email</label>
            <div class="input-wrap">
              <span class="input-icon">✉</span>
              <input
                type="email"
                id="login-email"
                name="email"
                class="form-input has-icon"
                placeholder="name@university.edu"
                autocomplete="email"
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
              />
            </div>
            <span class="form-error" id="email-error"></span>
          </div>

          <div class="form-group">
            <div class="form-label-row">
              <label class="form-label" for="login-password">Password</label>
              <a href="#">Forgot password?</a>
            </div>
            <div class="input-wrap">
              <span class="input-icon">🔒</span>
              <input
                type="password"
                id="login-password"
                name="password"
                class="form-input has-icon"
                placeholder="••••••••"
                autocomplete="current-password"
                style="padding-right:42px;"
              />
              <button type="button" class="toggle-password" aria-label="Toggle password visibility" data-target="login-password">
                <svg id="eye-icon-login" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <span class="form-error" id="password-error"></span>
          </div>

          <div class="checkbox-row">
            <input type="checkbox" id="remember" name="remember" value="1" />
            <label for="remember">Remember this device for 7 days</label>
          </div>

          <button type="submit" id="login-btn" class="btn btn-primary btn-full btn-lg">
            Sign In
          </button>
        </form>

        <div class="form-divider">or</div>

        <p class="form-footer" style="margin-top:0;">
          New to the platform? <a href="register.php">Create an account</a>
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
