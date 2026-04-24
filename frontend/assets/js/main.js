/* ===========================
   Lecture Confusion Tracker
   main.js
=========================== */

document.addEventListener('DOMContentLoaded', function () {

  // ── Scroll fade-in ───────────────────────────────
  var fadeEls = document.querySelectorAll('.fade-up');
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    fadeEls.forEach(function (el) { observer.observe(el); });
  } else {
    fadeEls.forEach(function (el) { el.classList.add('visible'); });
  }

  // ── Animated counters ────────────────────────────
  function animateCounter(el, target, suffix, duration) {
    if (!el) return;
    var start = 0;
    var step = target / (duration / 16);
    var timer = setInterval(function () {
      start += step;
      if (start >= target) { start = target; clearInterval(timer); }
      el.textContent = Math.floor(start) + suffix;
    }, 16);
  }

  var counterObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        animateCounter(document.getElementById('counter-students'), 47, '', 1200);
        animateCounter(document.getElementById('counter-pings'), 12, '', 1200);
        animateCounter(document.getElementById('counter-clarity'), 78, '%', 1400);
        counterObserver.disconnect();
      }
    });
  }, { threshold: 0.5 });

  var dashStats = document.querySelector('.dash-stats');
  if (dashStats) counterObserver.observe(dashStats);

  // ── Live ping simulation on dashboard ───────────
  var pingText = document.querySelector('.hero-ping-text');
  if (pingText) {
    var pingCounts = [2, 5, 1, 3, 7, 4];
    var pi = 0;
    setInterval(function () {
      pi = (pi + 1) % pingCounts.length;
      pingText.textContent = pingCounts[pi] + ' student' + (pingCounts[pi] !== 1 ? 's' : '') + ' confused right now';
    }, 3000);
  }



  // ── FAQ Accordion ───────────────────────────────
  document.querySelectorAll('.faq-question').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = btn.closest('.faq-item');
      var isOpen = item.classList.contains('open');
      // close all
      document.querySelectorAll('.faq-item.open').forEach(function (el) { el.classList.remove('open'); });
      if (!isOpen) item.classList.add('open');
    });
  });

  // ── TOC scroll-spy ───────────────────────────────
  var tocLinks = document.querySelectorAll('.page-toc a');
  if (tocLinks.length) {
    var sections = [];
    tocLinks.forEach(function (link) {
      var id = link.getAttribute('href').replace('#', '');
      var el = document.getElementById(id);
      if (el) sections.push({ id: id, el: el, link: link });
    });
    window.addEventListener('scroll', function () {
      var scrollY = window.scrollY + 120;
      var current = sections[0];
      sections.forEach(function (s) {
        if (s.el.offsetTop <= scrollY) current = s;
      });
      tocLinks.forEach(function (l) { l.classList.remove('active'); });
      if (current) current.link.classList.add('active');
    }, { passive: true });
  }

  // ── Support form validation ──────────────────────
  var supportForm = document.getElementById('support-form');
  if (supportForm) {
    supportForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = true;
      var name = document.getElementById('s-name');
      var email = document.getElementById('s-email');
      var msg = document.getElementById('s-message');
      if (!name.value.trim()) { showError(name, 's-name-error', 'Name is required'); ok = false; } else clearError(name, 's-name-error');
      if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) { showError(email, 's-email-error', 'Valid email required'); ok = false; } else clearError(email, 's-email-error');
      if (!msg.value.trim()) { showError(msg, 's-message-error', 'Message is required'); ok = false; } else clearError(msg, 's-message-error');
      if (ok) {
        var btn = document.getElementById('support-btn');
        btn.classList.add('btn-loading'); btn.disabled = true;
        setTimeout(function () {
          btn.classList.remove('btn-loading'); btn.disabled = false;
          btn.textContent = '✓ Message Sent';
          supportForm.reset();
        }, 1200);
      }
    });
  }

  // ── Password toggle ─────────────────────────────
  document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var targetId = btn.dataset.target;
      var input = document.getElementById(targetId);
      var svg = btn.querySelector('svg');
      if (!input) return;

      if (input.type === 'password') {
        input.type = 'text';
        // Switch to "eye-off" icon
        svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
      } else {
        input.type = 'password';
        // Switch back to "eye" icon
        svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
      }
    });
  });

  // ── Hamburger menu ──────────────────────────────
  const hamburger = document.querySelector('.hamburger');
  const mobileNav = document.querySelector('.mobile-nav');

  if (hamburger && mobileNav) {
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('active');
      mobileNav.classList.toggle('open');
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!hamburger.contains(e.target) && !mobileNav.contains(e.target)) {
        hamburger.classList.remove('active');
        mobileNav.classList.remove('open');
      }
    });
  }

  // ── Role selector (login page) ──────────────────
  const roleBtns = document.querySelectorAll('.role-btn');
  const roleInput = document.getElementById('role-input');

  roleBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      roleBtns.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      var val = btn.dataset.role;
      if (roleInput) roleInput.value = val;
      // also sync the form hidden field
      var roleField = document.getElementById('role-field');
      if (roleField) roleField.value = val;
    });
  });

  // ── Login form validation ───────────────────────
  const loginForm = document.getElementById('login-form');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();
      if (validateLoginForm()) {
        submitForm(loginForm, document.getElementById('login-btn'));
      }
    });

    // Real-time
    document.getElementById('login-email').addEventListener('blur', function () {
      validateEmail(this, 'email-error');
    });
    document.getElementById('login-password').addEventListener('blur', function () {
      validateRequired(this, 'password-error', 'Password is required');
    });
  }

  // ── Register form validation ────────────────────
  const registerForm = document.getElementById('register-form');
  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      e.preventDefault();
      if (validateRegisterForm()) {
        submitForm(registerForm, document.getElementById('register-btn'));
      }
    });

    // Real-time
    var nameField = document.getElementById('reg-name');
    var emailField = document.getElementById('reg-email');
    var passwordField = document.getElementById('reg-password');

    if (nameField) {
      nameField.addEventListener('blur', function () {
        validateRequired(this, 'reg-name-error', 'Full name is required');
      });
    }
    if (emailField) {
      emailField.addEventListener('blur', function () {
        validateEmail(this, 'reg-email-error');
      });
    }
    if (passwordField) {
      passwordField.addEventListener('input', function () {
        updatePasswordStrength(this.value);
        if (this.value.length > 0) {
          document.querySelector('.password-strength').classList.add('visible');
        }
      });
      passwordField.addEventListener('blur', function () {
        validatePassword(this, 'reg-password-error');
      });
    }
  }

  // ── Helpers ─────────────────────────────────────

  function validateLoginForm() {
    var ok = true;
    var email = document.getElementById('login-email');
    var password = document.getElementById('login-password');
    if (!validateEmail(email, 'email-error')) ok = false;
    if (!validateRequired(password, 'password-error', 'Password is required')) ok = false;
    return ok;
  }

  function validateRegisterForm() {
    var ok = true;
    var name = document.getElementById('reg-name');
    var email = document.getElementById('reg-email');
    var password = document.getElementById('reg-password');
    var role = document.getElementById('reg-role');
    if (!validateRequired(name, 'reg-name-error', 'Full name is required')) ok = false;
    if (!validateEmail(email, 'reg-email-error')) ok = false;
    if (!validatePassword(password, 'reg-password-error')) ok = false;
    if (role && role.value === '') {
      showError(role, 'reg-role-error', 'Please select a role');
      ok = false;
    }
    return ok;
  }

  function validateEmail(input, errorId) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!input.value.trim()) {
      showError(input, errorId, 'Email is required');
      return false;
    }
    if (!re.test(input.value.trim())) {
      showError(input, errorId, 'Enter a valid email address');
      return false;
    }
    clearError(input, errorId);
    return true;
  }

  function validateRequired(input, errorId, message) {
    if (!input.value.trim()) {
      showError(input, errorId, message);
      return false;
    }
    clearError(input, errorId);
    return true;
  }

  function validatePassword(input, errorId) {
    if (!input.value) {
      showError(input, errorId, 'Password is required');
      return false;
    }
    if (input.value.length < 6) {
      showError(input, errorId, 'Password must be at least 6 characters');
      return false;
    }
    clearError(input, errorId);
    return true;
  }

  function showError(input, errorId, message) {
    input.classList.add('error');
    input.classList.remove('valid');
    var el = document.getElementById(errorId);
    if (el) { el.textContent = message; el.classList.add('visible'); }
  }

  function clearError(input, errorId) {
    input.classList.remove('error');
    input.classList.add('valid');
    var el = document.getElementById(errorId);
    if (el) { el.textContent = ''; el.classList.remove('visible'); }
  }

  function updatePasswordStrength(value) {
    var fill = document.querySelector('.strength-fill');
    var label = document.querySelector('.strength-label');
    if (!fill || !label) return;
    fill.className = 'strength-fill';
    label.className = 'strength-label';
    if (value.length === 0) { fill.style.width = '0'; label.textContent = ''; return; }
    var score = 0;
    if (value.length >= 6) score++;
    if (value.length >= 10) score++;
    if (/[A-Z]/.test(value) && /[0-9]/.test(value)) score++;
    if (score <= 1) {
      fill.classList.add('weak'); label.classList.add('weak'); label.textContent = 'Weak';
    } else if (score === 2) {
      fill.classList.add('fair'); label.classList.add('fair'); label.textContent = 'Fair';
    } else {
      fill.classList.add('strong'); label.classList.add('strong'); label.textContent = 'Strong';
    }
  }

  function submitForm(form, btn) {
    if (!btn) return;
    btn.classList.add('btn-loading');
    btn.disabled = true;
    // Submit after brief loading state — backend will handle redirect
    setTimeout(function () { form.submit(); }, 600);
  }

});
