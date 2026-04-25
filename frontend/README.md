# 📊 Lecture Confusion Tracker

> Turn student confusion into actionable teaching insights. Monitor real-time comprehension and optimise your curriculum with data that actually matters.

---

## 🧭 Overview

**Lecture Confusion Tracker (LCT)** is a web-based platform that bridges the gap between teaching and understanding. Students anonymously signal confusion during live lectures with a single tap. Lecturers receive real-time heatmaps and post-session reports that pinpoint exactly where students struggled — no guesswork, no raised hands, no disruption.

Built with **PHP**, **HTML**, **CSS**, **JavaScript**, and **SQL** — no frameworks, no dependencies, no AI features. Just clean, fast, production-ready web code.

---

## ✨ Features

### For Students
- 🔴 **One-tap confusion pings** — signal confusion instantly without disrupting the class
- 👤 **Fully anonymous** — pings are aggregated; lecturers never see individual responses
- 🔗 **No app required** — join any session via a link or code in any browser

### For Lecturers
- 📊 **Live session dashboard** — see confusion levels update in real time
- 🔥 **Confusion heatmaps** — visualise exactly which moments in a lecture caused the most confusion
- 📋 **Post-session reports** — timestamped breakdown of confusion hotspots after every class
- 🎓 **Session management** — create, start, and archive sessions from a clean dashboard

### Platform
- 🔐 **Role-based access** — separate flows for Students and Lecturers
- 📱 **Fully responsive** — works on desktop, tablet, and mobile
- ⚡ **Scroll animations** — fade-in sections, animated counters, floating UI cards
- 🛟 **Support, Privacy & Terms pages** — production-ready legal and help pages

---

## 🗂️ Project Structure

```
project-root/
│
├── index.php               # Landing page (hero, stats, features, how it works, CTA)
├── login.php               # Login page with role selector (Student / Lecturer)
├── register.php            # Registration page
├── support.php             # Help centre with FAQ accordion & contact form
├── privacy.php             # Privacy policy with sticky TOC & scroll-spy
├── terms.php               # Terms of service
│
├── student/
│   ├── dashboard.php       # Student dashboard — confusion cards, filter, upvote
│   └── add_confusion.php   # Submit new confusion — course, topic, tag, description
│
├── assets/
│   ├── css/
│   │   └── style.css       # All styles — layout, components, animations, responsive
│   └── js/
│       └── main.js         # Form validation, hamburger menu, FAQ accordion,
│                           # scroll animations, counters, upvote, filter, char counter
│
└── includes/
    ├── header.php          # Sticky navbar with logo, nav links, mobile hamburger
    └── footer.php          # Footer with logo, links (Support / Privacy / Terms), © 2026
```

---

## 🚀 Getting Started

### Requirements

| Tool | Version |
|------|---------|
| PHP  | 7.4+    |
| Apache / Nginx | Any |
| MySQL | 5.7+ *(for backend, Phase 2)* |
| Browser | Any modern browser |

### Run with XAMPP (recommended for Windows)

1. Download and install [XAMPP](https://www.apachefriends.org)
2. Copy the `project-root/` folder into `C:\xampp\htdocs\lct\`
3. Start **Apache** from the XAMPP Control Panel
4. Open your browser and go to:

```
http://localhost/lct
```

### Run with PHP built-in server

```bash
cd project-root
php -S localhost:8000
```

Then visit `http://localhost:8000`

### Run with Laragon

1. Drop `project-root/` into `C:\laragon\www\lct\`
2. Start Laragon
3. Visit `http://lct.test`

---

## 📄 Pages

| Page | URL | Description |
|------|-----|-------------|
| Landing | `/index.php` | Hero, stats bar, features, how it works, CTA |
| Login | `/login.php` | Role selector, email/password, remember device |
| Register | `/register.php` | Name, email, password with strength meter, role |
| Support | `/support.php` | Help cards, FAQ accordion, contact form |
| Privacy | `/privacy.php` | Full privacy policy with sticky TOC |
| Terms | `/terms.php` | Full terms of service with sticky TOC |
| Student Dashboard | `/student/dashboard.php` | Confusion cards, course filter, upvote, sort |
| Add Confusion | `/student/add_confusion.php` | Submit confusion with course, topic, tag |

---

## 🎨 Design System

| Token | Value |
|-------|-------|
| Primary | `#5b21b6` |
| Primary Light | `#7c3aed` |
| Gradient | `135deg, #5b21b6 → #7c3aed` |
| Background | `#f5f3ff` |
| Surface | `#ffffff` |
| Text | `#1e1b4b` |
| Text Muted | `#4b5563` |
| Error | `#ef4444` |
| Success | `#10b981` |
| Font | Inter, system-ui fallback |
| Border Radius | 8 / 12 / 16 / 20px scale |
| Spacing | 8px base grid |

---

## 🧩 JavaScript Features (`main.js`)

| Feature | Description |
|---------|-------------|
| Scroll fade-in | `IntersectionObserver` triggers `.fade-up` animations on scroll |
| Animated counters | Dashboard stat numbers count up when scrolled into view |
| Live ping simulation | Hero dashboard cycles through realistic confusion counts |
| Password toggle | Eye icon shows/hides password on login and register |
| Password strength meter | Real-time weak / fair / strong indicator on register |
| Form validation | Inline error messages with real-time feedback on all forms |
| FAQ accordion | Smooth open/close with chevron rotation |
| TOC scroll-spy | Active link highlights as you scroll through policy pages |
| Hamburger menu | Animated mobile nav toggle |
| Role selector | Student / Lecturer toggle syncs with form hidden field |

---

## 🔒 Security Considerations

- Passwords are **never stored in plain text** — hash with `password_hash()` (bcrypt) before inserting into the database
- All user input must be sanitised with `htmlspecialchars()` before output
- Use **prepared statements** (PDO or MySQLi) for all database queries — never concatenate user input into SQL
- Session tokens are regenerated on login (`session_regenerate_id(true)`)
- "Remember device" cookie expires in **7 days** (not 30) to reduce risk on shared university machines
- HTTPS should be enforced in production via `.htaccess` or server config

---

## 🗄️ Database Schema (Phase 2)

The frontend is ready for backend integration. Suggested schema:

```sql
CREATE TABLE users (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  email       VARCHAR(150) NOT NULL UNIQUE,
  password    VARCHAR(255) NOT NULL,       -- bcrypt hash
  role        ENUM('student','lecturer') NOT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sessions (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  lecturer_id INT NOT NULL,
  title       VARCHAR(200) NOT NULL,
  code        VARCHAR(8) NOT NULL UNIQUE,  -- join code
  started_at  TIMESTAMP NULL,
  ended_at    TIMESTAMP NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lecturer_id) REFERENCES users(id)
);

CREATE TABLE confusion_pings (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  session_id  INT NOT NULL,
  pinged_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  -- no user_id: pings are anonymous by design
  FOREIGN KEY (session_id) REFERENCES sessions(id)
);
```

---

## 🗺️ Roadmap

- [x] Landing page — hero, stats, features, how it works, CTA
- [x] Login page — role selector, validation, password toggle
- [x] Register page — full validation, password strength meter
- [x] Shared header & footer components
- [x] Support, Privacy, Terms pages
- [x] **Phase 3** — Student dashboard: confusion cards, upvote, course filter, sort
- [x] **Phase 3** — Add confusion form: course, topic, tag, char counter, validation
- [ ] **Phase 2** — Backend: user auth, session management, database (PHP + MySQL)
- [ ] **Phase 4** — Lecturer dashboard: live heatmap, session controls, reports
- [ ] **Phase 5** — Admin panel, institutional analytics, export to PDF

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit your changes: `git commit -m "Add your feature"`
4. Push to the branch: `git push origin feature/your-feature`
5. Open a Pull Request

Please follow the existing code style — semantic HTML, no inline styles, consistent class naming.

---

## 📬 Contact

| Channel | Details |
|---------|---------|
| Support | [support.php](support.php) |
| Email | support@lct.edu |
| Privacy | privacy@lct.edu |
| Legal | legal@lct.edu |

---

## 📝 License

This project is licensed under the **MIT License** — free to use, modify, and distribute with attribution.

---

<p align="center">
  Built with 💜 for better lectures · © 2026 Lecture Confusion Tracker
</p>
