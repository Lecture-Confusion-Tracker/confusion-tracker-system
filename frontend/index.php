<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lecture Confusion Tracker — Real-time Student Insights</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>

  <!-- ══════════════════════════════
       HERO
  ══════════════════════════════ -->
  <section class="hero">
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>

    <div class="container">
      <div class="hero-inner">

        <!-- Left: Copy -->
        <div class="hero-content">
          <div class="hero-badge">
            <span class="badge-dot"></span>
            Live in lecture halls worldwide
          </div>

          <h1>Track <span>Confusion</span>,<br>Improve Every Lecture</h1>

          <p class="hero-tagline">
            Turn student confusion into actionable teaching insights.
            Monitor real-time comprehension and optimise your curriculum
            with data that actually matters.
          </p>

          <div class="hero-actions">
            <a href="register.php" class="btn btn-primary btn-lg">
              Get Started Free →
            </a>
            <a href="login.php" class="btn btn-outline btn-lg">
              Sign In
            </a>
          </div>

          <div class="hero-trust">
            <div class="trust-avatars">
              <span>JD</span>
              <span>AM</span>
              <span>KL</span>
              <span>+</span>
            </div>
            <span class="trust-text">Trusted by <strong>500+</strong> Faculty Members</span>
          </div>
        </div>

        <!-- Right: Dashboard mockup -->
        <div class="hero-visual">
          <div class="hero-ping">
            <span class="hero-ping-dot"></span>
            <span class="hero-ping-text">3 students confused right now</span>
          </div>

          <div class="hero-dashboard">
            <div class="dash-topbar">
              <span class="dash-topbar-title">📊 Live Session Dashboard</span>
              <div class="dash-topbar-dots">
                <span></span><span></span><span></span>
              </div>
            </div>

            <div class="dash-body">
              <div class="dash-lecture-card">
                <div class="dash-lecture-label">Now Playing</div>
                <div class="dash-lecture-title">Intro to Macroeconomics — Week 7</div>
                <div class="dash-progress-bar">
                  <div class="dash-progress-fill"></div>
                </div>
                <div class="dash-progress-times">
                  <span>22:14</span>
                  <span>60:00</span>
                </div>
              </div>

              <div class="dash-stats">
                <div class="dash-stat">
                  <div class="dash-stat-num" id="counter-students">0</div>
                  <div class="dash-stat-label">Students</div>
                </div>
                <div class="dash-stat">
                  <div class="dash-stat-num" id="counter-pings">0</div>
                  <div class="dash-stat-label">Confusion Pings</div>
                </div>
                <div class="dash-stat">
                  <div class="dash-stat-num" id="counter-clarity">0%</div>
                  <div class="dash-stat-label">Clarity Score</div>
                </div>
              </div>

              <div class="dash-heatmap-label">Confusion Heatmap — Last 10 min</div>
              <div class="dash-heatmap">
                <div class="dash-heatmap-bar lv1"></div>
                <div class="dash-heatmap-bar lv2"></div>
                <div class="dash-heatmap-bar lv3"></div>
                <div class="dash-heatmap-bar lv4"></div>
                <div class="dash-heatmap-bar lv5"></div>
                <div class="dash-heatmap-bar lv3"></div>
                <div class="dash-heatmap-bar lv2"></div>
                <div class="dash-heatmap-bar lv4"></div>
                <div class="dash-heatmap-bar lv1"></div>
                <div class="dash-heatmap-bar lv3"></div>
              </div>
            </div>
          </div>

          <div class="hero-insight">
            <span class="hero-insight-icon">📈</span>
            <span class="hero-insight-text">Clarity up 12% this week</span>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ══════════════════════════════
       STATS BAR
  ══════════════════════════════ -->
  <section class="stats-bar">
    <div class="container">
      <div class="stats-grid">
        <div class="stat-item fade-up">
          <div class="stat-num">500+</div>
          <div class="stat-label">Faculty Members</div>
        </div>
        <div class="stat-item fade-up" style="transition-delay:0.1s">
          <div class="stat-num">12k+</div>
          <div class="stat-label">Students Enrolled</div>
        </div>
        <div class="stat-item fade-up" style="transition-delay:0.2s">
          <div class="stat-num">98k+</div>
          <div class="stat-label">Confusion Pings Tracked</div>
        </div>
        <div class="stat-item fade-up" style="transition-delay:0.3s">
          <div class="stat-num">34%</div>
          <div class="stat-label">Avg. Clarity Improvement</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════
       FEATURES
  ══════════════════════════════ -->
  <section class="features">
    <div class="container">
      <div class="section-header fade-up">
        <h2>Everything you need to understand your students</h2>
        <p>Three tools that close the feedback loop between teaching and learning — in real time.</p>
      </div>

      <div class="features-grid">

        <div class="feature-card fade-up">
          <div class="feature-icon">🔥</div>
          <h3>Real-time Confusion Heatmaps</h3>
          <p>Visualise exactly where students lose the thread. Anonymous pings are mapped directly to your lecture timeline.</p>
          <div class="heatmap-bars">
            <div class="heatmap-bar h1"></div>
            <div class="heatmap-bar h2"></div>
            <div class="heatmap-bar h3"></div>
            <div class="heatmap-bar h4"></div>
            <div class="heatmap-bar h5"></div>
            <div class="heatmap-bar h6"></div>
            <div class="heatmap-bar h7"></div>
          </div>
        </div>

        <div class="feature-card fade-up" style="transition-delay:0.1s">
          <div class="feature-icon">💬</div>
          <h3>Anonymous Feedback</h3>
          <p>Low-friction student participation without the social anxiety of raising a hand. One tap to signal confusion.</p>
        </div>

        <div class="feature-card fade-up" style="transition-delay:0.2s">
          <div class="feature-icon">📊</div>
          <h3>Actionable Insights</h3>
          <p>Post-session reports highlight the exact moments and topics that need revisiting — no guesswork required.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- ══════════════════════════════
       HOW IT WORKS
  ══════════════════════════════ -->
  <section class="how">
    <div class="container">
      <div class="section-header fade-up">
        <h2>Up and running in minutes</h2>
        <p>No complex setup. No hardware. Just open a session and start teaching.</p>
      </div>

      <div class="steps">
        <div class="step fade-up">
          <div class="step-num">1</div>
          <h3>Create a Session</h3>
          <p>Lecturers open a live session before class. Students join with a simple link or code — no app download needed.</p>
        </div>
        <div class="step fade-up" style="transition-delay:0.15s">
          <div class="step-num">2</div>
          <h3>Students Ping Confusion</h3>
          <p>During the lecture, students tap once to signal confusion. Votes are anonymous and aggregated in real time.</p>
        </div>
        <div class="step fade-up" style="transition-delay:0.3s">
          <div class="step-num">3</div>
          <h3>Review & Improve</h3>
          <p>After class, lecturers get a full breakdown of confusion hotspots with timestamps and topic tags.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════
       CTA
  ══════════════════════════════ -->
  <section class="cta">
    <div class="container">
      <div class="cta-card fade-up">
        <h2>Ready to bridge the gap?</h2>
        <p>Join hundreds of educators already using Lecture Confusion Tracker to improve student outcomes.</p>
        <div class="cta-actions">
          <a href="register.php" class="btn btn-white btn-lg">Create Free Account</a>
          <a href="login.php" class="btn btn-white-outline btn-lg">Sign In</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>

<script src="assets/js/main.js"></script>
</body>
</html>
