# Confusion Tracker System (Lecture Confusion Tracker)

A web-based full-stack application that helps **students** submit and upvote confusing lecture concepts and helps **lecturers** monitor confusion trends, review student feedback, and analyze learning pain points through dashboards and analytics.

Repository: [Lecture-Confusion-Tracker/confusion-tracker-system](https://github.com/Lecture-Confusion-Tracker/confusion-tracker-system)

---

## Features

### Authentication & Roles
- Student / Lecturer registration and login
- Session-based authentication
- Role-based access control (protected pages)

### Student Module
- Submit confusion topics by course (topic, description, optional tag)
- View all confusions with course filtering and sorting (recent / most voted)
- Upvote (toggle) confusions via AJAX
- View lecturer feedback when provided

### Lecturer Module
- Lecturer dashboard KPIs (total confusions, hardest topic, active students)
- Trending topics and charts
- Analytics report (timeline + topic breakdown) with CSV export and print option
- Review all student confusions and save lecturer feedback via AJAX

### Support & Account
- Support page contact form stored in database
- Profile page with user stats and password change
- Privacy & Terms pages

---

## Tech Stack

- **Frontend**
  - PHP-rendered pages (HTML/CSS)
  - Vanilla JavaScript for UI interactions and AJAX
  - Chart.js (CDN) for lecturer charts

- **Backend**
  - PHP (custom handlers + JSON endpoints)

- **Database**
  - MySQL (primary, recommended)
  - SQLite fallback for local runs (auto-created if MySQL is unavailable)

---

## System Architecture Overview

- **UI layer (`frontend/`)**: server-rendered PHP pages for students/lecturers.
- **Backend layer (`backend/`)**:
  - Auth handlers (`backend/auth/`) for login/register/logout.
  - Feature endpoints (`backend/confusions/`, `backend/support/`) for AJAX and form processing.
- **Data layer**:
  - Centralized PDO connection in `backend/auth/includes/db.php`
  - Tries MySQL first; if connection fails, falls back to SQLite and initializes schema.

---

## Project Structure

```bash
confusion-tracker-system/
├── backend/
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── logout.php
│   │   └── includes/
│   │       ├── auth.php
│   │       └── db.php
│   ├── confusions/
│   │   ├── add_confusion.php
│   │   ├── vote.php
│   │   └── save_feedback.php
│   └── support/
│       └── send_message.php
│
├── frontend/
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── profile.php
│   ├── support.php
│   ├── privacy.php
│   ├── terms.php
│   ├── includes/
│   │   ├── header.php
│   │   ├── footer.php
│   │   └── auth_guard.php
│   ├── assets/
│   │   ├── css/style.css
│   │   └── js/main.js
│   ├── student/
│   │   ├── dashboard.php
│   │   └── add_confusion.php
│   └── lecturer/
│       ├── layout.php
│       ├── dashboard.php
│       ├── analytics.php
│       ├── feedback.php
│       ├── insights.php
│       └── new_session.php
│
└── README.md
```

---

## Installation and Setup

### Requirements
- PHP 7.4+ (PHP 8+ recommended)
- Apache (XAMPP recommended on Windows) or any PHP-capable server
- MySQL 5.7+ / MariaDB (optional; SQLite fallback is supported)
- A modern browser

### Option A: Run with XAMPP (Windows)
1. Clone/copy this repo into:

   ```text
   C:\xampp\htdocs\confusion-tracker-system
   ```

2. Start **Apache** from the XAMPP Control Panel.
3. Open the app:

   ```text
   http://localhost/confusion-tracker-system/frontend/index.php
   ```

### Option B: Run with PHP Built-in Server
From the `frontend/` directory:

```bash
cd frontend
php -S localhost:8000
```

Open:

```text
http://localhost:8000/index.php
```

---

## Environment Variables

This project does **not** use an `.env` file by default.

Database configuration is in:

- `backend/auth/includes/db.php`

You can adjust:
- `$host`, `$dbname`, `$dbuser` (and optionally the password logic)

---

## Database Notes

### MySQL (recommended)
If you use MySQL, create a database named:

- `confusion_tracker`

> If MySQL connection fails, the app automatically uses SQLite and creates required tables for local testing.

### SQLite fallback (local dev)
A local SQLite database file may be created automatically during runtime:

- `backend/auth/includes/confusion_tracker.sqlite`

This file contains local data (users/confusions/votes) and is typically **not** committed.

---

## API Endpoints (Summary)

These endpoints are used by forms and AJAX (`fetch`) calls.

| Method | Endpoint | Description | Auth |
|------:|----------|-------------|------|
| POST | `/backend/auth/register.php` | Register user (student/lecturer) | Public |
| POST | `/backend/auth/login.php` | Login and start session | Public |
| GET  | `/backend/auth/logout.php` | Logout and destroy session | Logged-in |
| POST | `/backend/confusions/add_confusion.php` | Submit a confusion topic | Student |
| POST | `/backend/confusions/vote.php` | Toggle upvote on a confusion | Student |
| POST | `/backend/confusions/save_feedback.php` | Save lecturer feedback on a confusion | Lecturer |
| POST | `/backend/support/send_message.php` | Submit support/contact message | Public |

---

## Usage Guide

### Student
1. Register as **Student**
2. Login → redirected to Student dashboard:
   - `/frontend/student/dashboard.php`
3. Add a confusion:
   - `/frontend/student/add_confusion.php`
4. Upvote confusing topics on the dashboard

### Lecturer
1. Register as **Lecturer**
2. Login → redirected to Lecturer dashboard:
   - `/frontend/lecturer/dashboard.php`
3. View analytics:
   - `/frontend/lecturer/analytics.php`
4. Review student submissions + add feedback:
   - `/frontend/lecturer/feedback.php`

---

## Screenshots

Not included (deployment/demo link will be provided separately).

---

## Future Improvements

- Implement a full **lecture session** model (persist sessions, join codes, per-session analytics)
- Provide a dedicated **MySQL schema/migration script** for clean DB setup
- Add password reset flow
- Admin tools (course management, moderation, exports)
- Pagination and performance improvements for large datasets

---

## Contributors

- Tursina Yishak
- Foziya Jemal
- Sumeya Abdi
- Elham Seid

---

## License

No license file is currently specified in this repository.

If this project will be shared publicly or reused, add a `LICENSE` file (commonly MIT) and update this section accordingly.
