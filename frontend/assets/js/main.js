/* ===========================
   Lecture Confusion Tracker — main.js
=========================== */

document.addEventListener('DOMContentLoaded', function () {

  // ── Scroll fade-in ──────────────────────────────
  var fadeEls = document.querySelectorAll('.fade-up');
  if ('IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
      });
    }, { threshold: 0.12 });
    fadeEls.forEach(function (el) { obs.observe(el); });
  } else {
    fadeEls.forEach(function (el) { el.classList.add('visible'); });
  }

  // ── Animated counters (landing page) ────────────
  function animateCounter(el, target, suffix, duration) {
    if (!el) return;
    var start = 0, step = target / (duration / 16);
    var timer = setInterval(function () {
      start += step;
      if (start >= target) { start = target; clearInterval(timer); }
      el.textContent = Math.floor(start) + suffix;
    }, 16);
  }
  var cObs = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        var st = document.getElementById('counter-students');
        var pg = document.getElementById('counter-pings');
        var cl = document.getElementById('counter-clarity');
        animateCounter(st, parseInt((st && st.dataset && st.dataset.target) || '47', 10), '', 1200);
        animateCounter(pg, parseInt((pg && pg.dataset && pg.dataset.target) || '12', 10), '', 1200);
        animateCounter(cl, parseInt((cl && cl.dataset && cl.dataset.target) || '78', 10), '%', 1400);
        cObs.disconnect();
      }
    });
  }, { threshold: 0.5 });
  var ds = document.querySelector('.dash-stats');
  if (ds) cObs.observe(ds);

  // ── Live ping simulation (landing hero) ─────────
  var pingText = document.querySelector('.hero-ping-text');
  if (pingText) {
    var pings = [2, 5, 1, 3, 7, 4], pi = 0;
    setInterval(function () {
      pi = (pi + 1) % pings.length;
      pingText.textContent = pings[pi] + ' student' + (pings[pi] !== 1 ? 's' : '') + ' confused right now';
    }, 3000);
  }

  // ── Notification dropdown (lecturer portal) ─────
  var notifBtn      = document.getElementById('notif-btn');
  var notifDropdown = document.getElementById('notif-dropdown');
  if (notifBtn && notifDropdown) {
    notifBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      notifDropdown.classList.toggle('open');
    });
    document.addEventListener('click', function () {
      notifDropdown.classList.remove('open');
    });
  }

  // ── Portal search (lecturer) ─────────────────────
  var portalSearch = document.getElementById('portal-search-input');
  if (portalSearch) {
    portalSearch.addEventListener('input', function () {
      var q = portalSearch.value.toLowerCase();
      document.querySelectorAll('.trend-title, .kpi-small, .panel-title').forEach(function (el) {
        var row = el.closest('li, .kpi-card, tr');
        if (!row) return;
        row.style.display = (!q || el.textContent.toLowerCase().includes(q)) ? '' : 'none';
      });
    });
  }

  // ── Hamburger menu ──────────────────────────────
  var hamburger = document.querySelector('.hamburger');
  var mobileNav = document.querySelector('.mobile-nav');
  if (hamburger && mobileNav) {
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('active');
      mobileNav.classList.toggle('open');
    });
    document.addEventListener('click', function (e) {
      if (!hamburger.contains(e.target) && !mobileNav.contains(e.target)) {
        hamburger.classList.remove('active');
        mobileNav.classList.remove('open');
      }
    });
  }

  // ── Role selector (login page) ──────────────────
  document.querySelectorAll('.role-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.role-btn').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      var val = btn.dataset.role;
      var ri = document.getElementById('role-input');
      var rf = document.getElementById('role-field');
      if (ri) ri.value = val;
      if (rf) rf.value = val;
    });
  });

  // ── Login form ──────────────────────────────────
  var loginForm = document.getElementById('login-form');
  if (loginForm) {
    var loginEmail = document.getElementById('login-email');
    var loginPass  = document.getElementById('login-password');
    if (loginEmail) loginEmail.addEventListener('blur', function () { validateEmail(this, 'email-error'); });
    if (loginPass)  loginPass.addEventListener('blur',  function () { validateRequired(this, 'password-error', 'Password is required'); });

    loginForm.addEventListener('submit', function (e) {
      var ok = true;
      if (!validateEmail(loginEmail, 'email-error')) ok = false;
      if (!validateRequired(loginPass, 'password-error', 'Password is required')) ok = false;
      if (!ok) { e.preventDefault(); return; }
      var btn = document.getElementById('login-btn');
      if (btn) { btn.classList.add('btn-loading'); btn.disabled = true; }
      // Let form submit naturally to backend
    });
  }

  // ── Register form ───────────────────────────────
  var registerForm = document.getElementById('register-form');
  if (registerForm) {
    var regName  = document.getElementById('reg-name');
    var regEmail = document.getElementById('reg-email');
    var regPass  = document.getElementById('reg-password');
    var regRole  = document.getElementById('reg-role');
    var termsBox = document.getElementById('terms');

    if (regName)  regName.addEventListener('blur',  function () { validateRequired(this, 'reg-name-error', 'Full name is required'); });
    if (regEmail) regEmail.addEventListener('blur', function () { validateEmail(this, 'reg-email-error'); });
    if (regPass)  regPass.addEventListener('input', function () {
      updatePasswordStrength(this.value);
      var ps = document.querySelector('.password-strength');
      if (ps && this.value.length > 0) ps.classList.add('visible');
    });
    if (regPass) regPass.addEventListener('blur', function () { validatePassword(this, 'reg-password-error'); });

    registerForm.addEventListener('submit', function (e) {
      var ok = true;
      if (!validateRequired(regName,  'reg-name-error',  'Full name is required')) ok = false;
      if (!validateEmail(regEmail, 'reg-email-error')) ok = false;
      if (!validatePassword(regPass, 'reg-password-error')) ok = false;
      if (regRole && !regRole.value) { showError(regRole, 'reg-role-error', 'Please select a role'); ok = false; }
      if (termsBox && !termsBox.checked) {
        alert('Please accept the Terms of Service to continue.');
        ok = false;
      }
      if (!ok) { e.preventDefault(); return; }
      var btn = document.getElementById('register-btn');
      if (btn) { btn.classList.add('btn-loading'); btn.disabled = true; }
      // Let form submit naturally to backend
    });
  }

  // ── Password toggle ─────────────────────────────
  document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = document.getElementById(btn.dataset.target);
      var svg   = btn.querySelector('svg');
      if (!input) return;
      if (input.type === 'password') {
        input.type = 'text';
        svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
      } else {
        input.type = 'password';
        svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
      }
    });
  });

  // ── Password strength ───────────────────────────
  function updatePasswordStrength(value) {
    var fill  = document.querySelector('.strength-fill');
    var label = document.querySelector('.strength-label');
    if (!fill || !label) return;
    fill.className = 'strength-fill'; label.className = 'strength-label';
    if (!value.length) { fill.style.width = '0'; label.textContent = ''; return; }
    var score = 0;
    if (value.length >= 6)  score++;
    if (value.length >= 10) score++;
    if (/[A-Z]/.test(value) && /[0-9]/.test(value)) score++;
    var levels = ['weak','fair','strong'];
    var labels = ['Weak','Fair','Strong'];
    var lvl = Math.min(score, 2);
    fill.classList.add(levels[lvl]); label.classList.add(levels[lvl]); label.textContent = labels[lvl];
  }

  // ── Add Confusion form ───────────────────────────
  var descField = document.getElementById('description');
  var charCount = document.getElementById('char-count');
  if (descField && charCount) {
    function updateCharCount() {
      var len = descField.value.length;
      charCount.textContent = len + ' / 500';
      charCount.style.color = len > 480 ? 'var(--error)' : 'var(--text-light)';
    }
    descField.addEventListener('input', updateCharCount);
    updateCharCount();
  }

  var addForm = document.getElementById('add-confusion-form');
  if (addForm) {
    addForm.addEventListener('submit', function (e) {
      var ok = true;
      var course = document.getElementById('course');
      var topic  = document.getElementById('topic');
      var desc   = document.getElementById('description');
      if (!course || !course.value) { showError(course, 'course-error', 'Please select a course'); ok = false; } else clearError(course, 'course-error');
      if (!topic.value.trim())      { showError(topic,  'topic-error',  'Topic is required');       ok = false; } else clearError(topic,  'topic-error');
      if (!desc.value.trim())       { showError(desc,   'desc-error',   'Please describe your confusion'); ok = false; } else clearError(desc, 'desc-error');
      if (desc.value.length > 500)  { showError(desc,   'desc-error',   'Maximum 500 characters');  ok = false; }
      if (!ok) { e.preventDefault(); return; }
      var btn = document.getElementById('submit-btn');
      if (btn) { btn.classList.add('btn-loading'); btn.disabled = true; }
    });
  }

  // ── Support contact form → real backend ─────────
  var supportForm = document.getElementById('support-form');
  if (supportForm) {
    supportForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = true;
      var name  = document.getElementById('s-name');
      var email = document.getElementById('s-email');
      var msg   = document.getElementById('s-message');
      if (!name.value.trim())  { showError(name,  's-name-error',    'Name is required');         ok = false; } else clearError(name,  's-name-error');
      if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        showError(email, 's-email-error', 'Valid email required'); ok = false;
      } else clearError(email, 's-email-error');
      if (!msg.value.trim())   { showError(msg,   's-message-error', 'Message is required');      ok = false; } else clearError(msg,   's-message-error');
      if (!ok) return;

      var btn = document.getElementById('support-btn');
      btn.classList.add('btn-loading'); btn.disabled = true;

      var formData = new FormData(supportForm);
      // Detect depth: support.php is at root, so backend is at ../backend/
      var backendPath = window.location.pathname.includes('/student/') || window.location.pathname.includes('/lecturer/')
        ? '../../backend/support/send_message.php'
        : '../backend/support/send_message.php';

      fetch(backendPath, { method: 'POST', body: formData })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          btn.classList.remove('btn-loading'); btn.disabled = false;
          if (data.success) {
            btn.textContent = '✓ Message Sent';
            btn.style.background = 'var(--success)';
            supportForm.reset();
            setTimeout(function () {
              btn.textContent = 'Send Message';
              btn.style.background = '';
            }, 4000);
          } else {
            btn.textContent = 'Send Message';
            alert(data.message || 'Something went wrong. Please try again.');
          }
        })
        .catch(function () {
          btn.classList.remove('btn-loading'); btn.disabled = false;
          btn.textContent = 'Send Message';
          alert('Network error. Please try again.');
        });
    });
  }

  // ── FAQ Accordion ───────────────────────────────
  document.querySelectorAll('.faq-question').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item   = btn.closest('.faq-item');
      var isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(function (el) { el.classList.remove('open'); });
      if (!isOpen) item.classList.add('open');
    });
  });

  // ── TOC scroll-spy ───────────────────────────────
  var tocLinks = document.querySelectorAll('.page-toc a');
  if (tocLinks.length) {
    var sections = [];
    tocLinks.forEach(function (link) {
      var el = document.getElementById(link.getAttribute('href').replace('#', ''));
      if (el) sections.push({ el: el, link: link });
    });
    window.addEventListener('scroll', function () {
      var scrollY = window.scrollY + 120, current = sections[0];
      sections.forEach(function (s) { if (s.el.offsetTop <= scrollY) current = s; });
      tocLinks.forEach(function (l) { l.classList.remove('active'); });
      if (current) current.link.classList.add('active');
    }, { passive: true });
  }

  // ── Validation helpers ───────────────────────────
  function validateEmail(input, errorId) {
    if (!input) return false;
    if (!input.value.trim()) { showError(input, errorId, 'Email is required'); return false; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim())) { showError(input, errorId, 'Enter a valid email address'); return false; }
    clearError(input, errorId); return true;
  }
  function validateRequired(input, errorId, message) {
    if (!input) return false;
    if (!input.value.trim()) { showError(input, errorId, message); return false; }
    clearError(input, errorId); return true;
  }
  function validatePassword(input, errorId) {
    if (!input) return false;
    if (!input.value) { showError(input, errorId, 'Password is required'); return false; }
    if (input.value.length < 6) { showError(input, errorId, 'Password must be at least 6 characters'); return false; }
    clearError(input, errorId); return true;
  }
  function showError(input, errorId, message) {
    if (input) { input.classList.add('error'); input.classList.remove('valid'); }
    var el = document.getElementById(errorId);
    if (el) { el.textContent = message; el.classList.add('visible'); }
  }
  function clearError(input, errorId) {
    if (input) { input.classList.remove('error'); input.classList.add('valid'); }
    var el = document.getElementById(errorId);
    if (el) { el.textContent = ''; el.classList.remove('visible'); }
  }

});
