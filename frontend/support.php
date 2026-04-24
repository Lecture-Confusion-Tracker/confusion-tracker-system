<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Support — Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>

  <!-- Page Hero -->
  <div class="page-hero">
    <div class="container page-hero-inner">
      <div class="page-hero-badge">🛟 Help Centre</div>
      <h1>How can we help?</h1>
      <p>Find answers, contact our team, or browse common questions below.</p>
    </div>
  </div>

  <!-- Support Cards -->
  <section class="page-content" style="padding-bottom:0;">
    <div class="container">
      <div class="support-grid">
        <div class="support-card fade-up">
          <div class="support-card-icon">📧</div>
          <h3>Email Support</h3>
          <p>Send us a message and we'll get back to you within 24 hours on business days.</p>
          <a href="mailto:support@lct.edu" class="btn btn-primary">Send Email</a>
        </div>
        <div class="support-card fade-up" style="transition-delay:0.1s">
          <div class="support-card-icon">💬</div>
          <h3>Live Chat</h3>
          <p>Chat with our support team in real time during business hours (Mon–Fri, 9am–5pm).</p>
          <a href="#contact" class="btn btn-outline">Start Chat</a>
        </div>
        <div class="support-card fade-up" style="transition-delay:0.2s">
          <div class="support-card-icon">📖</div>
          <h3>Documentation</h3>
          <p>Step-by-step guides for students, lecturers, and administrators.</p>
          <a href="#faq" class="btn btn-outline">Browse Docs</a>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="page-content" id="faq">
    <div class="container">
      <div class="page-layout">

        <!-- TOC -->
        <aside class="page-toc">
          <h4>On this page</h4>
          <ul>
            <li><a href="#faq-general">General</a></li>
            <li><a href="#faq-students">For Students</a></li>
            <li><a href="#faq-lecturers">For Lecturers</a></li>
            <li><a href="#faq-account">Account & Billing</a></li>
            <li><a href="#contact">Contact Us</a></li>
          </ul>
        </aside>

        <div class="page-article">

          <div class="page-last-updated">📅 Last updated: April 2026</div>

          <!-- General -->
          <section id="faq-general">
            <h2>General</h2>
            <div class="faq-list">
              <div class="faq-item">
                <button class="faq-question" type="button">
                  What is Lecture Confusion Tracker?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>Lecture Confusion Tracker is a real-time feedback platform that lets students anonymously signal confusion during lectures. Lecturers receive live heatmaps and post-session reports to identify exactly where students struggled.</p></div>
              </div>
              <div class="faq-item">
                <button class="faq-question" type="button">
                  Is it free to use?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>Yes — the core platform is completely free for students and lecturers. We offer an optional institutional plan for universities that need advanced analytics, SSO integration, and priority support.</p></div>
              </div>
              <div class="faq-item">
                <button class="faq-question" type="button">
                  Do students need to create an account?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>Students need a free account to join sessions. Registration takes under a minute and only requires a name, institutional email, and password.</p></div>
              </div>
            </div>
          </section>

          <!-- Students -->
          <section id="faq-students">
            <h2>For Students</h2>
            <div class="faq-list">
              <div class="faq-item">
                <button class="faq-question" type="button">
                  How do I join a live session?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>Your lecturer will share a session link or a short code at the start of class. Open the link in any browser — no app download required. Log in with your student account and you're in.</p></div>
              </div>
              <div class="faq-item">
                <button class="faq-question" type="button">
                  Is my feedback really anonymous?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>Yes. Confusion pings are aggregated before being shown to lecturers. Individual responses are never attributed to a specific student. Lecturers only see totals and heatmaps.</p></div>
              </div>
              <div class="faq-item">
                <button class="faq-question" type="button">
                  Can I submit confusion after the lecture ends?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>Live pings are only available during an active session. However, lecturers can optionally enable a post-session feedback window of up to 30 minutes after class ends.</p></div>
              </div>
            </div>
          </section>

          <!-- Lecturers -->
          <section id="faq-lecturers">
            <h2>For Lecturers</h2>
            <div class="faq-list">
              <div class="faq-item">
                <button class="faq-question" type="button">
                  How do I start a session?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>Log in with your lecturer account, go to your dashboard, and click "Start New Session". Give it a title, set the duration, and share the generated link or code with your students.</p></div>
              </div>
              <div class="faq-item">
                <button class="faq-question" type="button">
                  Where do I see the post-session report?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>After a session ends, the full report is available in your dashboard under "Past Sessions". It includes a confusion heatmap, peak confusion timestamps, and a topic breakdown.</p></div>
              </div>
            </div>
          </section>

          <!-- Account -->
          <section id="faq-account">
            <h2>Account &amp; Billing</h2>
            <div class="faq-list">
              <div class="faq-item">
                <button class="faq-question" type="button">
                  How do I reset my password?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>On the login page, click "Forgot password?" and enter your institutional email. You'll receive a reset link within a few minutes. Check your spam folder if it doesn't arrive.</p></div>
              </div>
              <div class="faq-item">
                <button class="faq-question" type="button">
                  How do I delete my account?
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-answer"><p>Go to Settings → Account → Delete Account. This action is permanent and will remove all your data. If you're a lecturer, your session history will also be deleted.</p></div>
              </div>
            </div>
          </section>

          <!-- Contact Form -->
          <section id="contact">
            <h2>Contact Us</h2>
            <div class="contact-form-wrap">
              <h3>Send us a message</h3>
              <p>We typically respond within one business day.</p>
              <form id="support-form" novalidate>
                <div class="form-group">
                  <label class="form-label" for="s-name">Your Name</label>
                  <input type="text" id="s-name" class="form-input" placeholder="Jane Doe" />
                  <span class="form-error" id="s-name-error"></span>
                </div>
                <div class="form-group">
                  <label class="form-label" for="s-email">Email Address</label>
                  <input type="email" id="s-email" class="form-input" placeholder="name@university.edu" />
                  <span class="form-error" id="s-email-error"></span>
                </div>
                <div class="form-group">
                  <label class="form-label" for="s-subject">Subject</label>
                  <select id="s-subject" class="form-select">
                    <option value="">Select a topic…</option>
                    <option>Account issue</option>
                    <option>Session problem</option>
                    <option>Feature request</option>
                    <option>Other</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label" for="s-message">Message</label>
                  <textarea id="s-message" class="form-input" rows="4" placeholder="Describe your issue…" style="resize:vertical;"></textarea>
                  <span class="form-error" id="s-message-error"></span>
                </div>
                <button type="submit" id="support-btn" class="btn btn-primary btn-lg" style="width:100%;">Send Message</button>
              </form>
            </div>
          </section>

        </div>
      </div>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>
