# Backend README

PHP backend for the **Confusion Tracker System**.  
This README is focused only on the backend setup, structure, and how each module works.

---

## What This Backend Handles

- Authentication (register, login, logout)
- Session and role helpers (student/lecturer checks)
- Confusion topic creation and voting
- Lecturer feedback saving
- Support/contact message submission
- Database connection and schema safety checks

---

## Backend Structure (Actual)

```text
backend/
├── auth/
│   ├── includes/
│   │   ├── auth.php                 # Session, role checks, auth helpers
│   │   └── db.php                   # PDO connection (MySQL first, SQLite fallback)
│   ├── login.php                    # Login handler
│   ├── register.php                 # Registration handler
│   └── logout.php                   # Logout handler
├── confusions/
│   ├── add_confusion.php            # Create confusion topic (student)
│   ├── vote.php                     # Toggle upvote on confusion
│   └── save_feedback.php            # Save lecturer feedback
├── support/
│   └── send_message.php             # Contact/support form handler
└── README.md
```

---

## Architecture Notes

### 1) `auth/includes/db.php`

- Creates the shared `$pdo` connection object.
- Tries **MySQL** first (`localhost`, DB: `confusion_tracker`, user: `root`).
- Cycles through common passwords automatically.
- Falls back to **SQLite** if MySQL connection fails.
- Auto-initializes SQLite tables and default courses.
- Runs a small schema safety step (`ensureSchema`) for `lecturer_feedback`.

### 2) `auth/includes/auth.php`

- Starts session.
- Loads `db.php` so every auth utility has DB access.
- Exposes helper functions such as:
  - `isLoggedIn()`
  - `isStudent()`
  - `isLecturer()`
  - `registerUser(...)`
  - `loginUser(...)`
  - `logoutUser()`

### 3) Feature Endpoints

- `confusions/add_confusion.php`: stores student confusion entries.
- `confusions/vote.php`: upvote toggle endpoint.
- `confusions/save_feedback.php`: lecturer feedback save endpoint.
- `support/send_message.php`: stores support messages.

---

## Database Behavior

### Primary: MySQL

Default values in `auth/includes/db.php`:

- Host: `localhost`
- Database: `confusion_tracker`
- User: `root`

### Fallback: SQLite

If MySQL is not available, backend uses SQLite file:

- `backend/auth/includes/confusion_tracker.sqlite`

This allows local development to run without manual DB setup.

---

## Quick Setup (Backend)

1. Ensure PHP 7.4+ (PHP 8+ recommended).
2. Place project in web server root (e.g., XAMPP `htdocs`) or run with PHP built-in server from project root.
3. If using MySQL, create database:

```sql
CREATE DATABASE confusion_tracker;
```

4. Open frontend entry (`frontend/index.php`), backend endpoints are called from frontend forms/AJAX.

---

## Backend Endpoint Summary

| Method | Endpoint | Purpose | Access |
|---|---|---|---|
| POST | `/backend/auth/register.php` | Register user | Public |
| POST | `/backend/auth/login.php` | Login + session start | Public |
| GET | `/backend/auth/logout.php` | Logout + session destroy | Logged-in |
| POST | `/backend/confusions/add_confusion.php` | Add confusion topic | Student |
| POST | `/backend/confusions/vote.php` | Toggle vote | Student |
| POST | `/backend/confusions/save_feedback.php` | Save lecturer feedback | Lecturer |
| POST | `/backend/support/send_message.php` | Submit contact/support message | Public |

---

## Notes for Contributors

- Keep business logic in backend handlers and shared auth utilities.
- Reuse `auth/includes/auth.php` helpers for role/session checks.
- Keep DB changes compatible with both MySQL and SQLite when possible.
- Do not commit local generated runtime files if not needed (for example local SQLite data files).

---

## Suggested Improvement Areas

- Add a proper migrations folder (versioned schema changes).
- Add request validation utilities and consistent JSON response format.
- Add centralized error handling and logging.
- Add lightweight endpoint tests for auth and confusion flows.
