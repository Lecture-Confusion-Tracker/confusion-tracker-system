# Backend — Phase 2

This folder will contain the PHP + MySQL backend for Lecture Confusion Tracker.

## Planned structure

\\\
backend/
+-- config/
¦   +-- db.php              # PDO database connection
+-- auth/
¦   +-- login.php           # Handle login POST, session creation
¦   +-- register.php        # Handle register POST, password hashing
¦   +-- logout.php          # Destroy session
+-- api/
¦   +-- session.php         # Create / end lecture sessions
¦   +-- ping.php            # Submit anonymous confusion ping
¦   +-- report.php          # Fetch post-session heatmap data
+-- dashboard/
¦   +-- student.php         # Student dashboard
¦   +-- lecturer.php        # Lecturer dashboard
+-- includes/
    +-- auth_check.php      # Session guard middleware
\\\

## Tech stack
- PHP 8+
- MySQL 8+ (PDO with prepared statements)
- PHP sessions for authentication
- bcrypt password hashing via password_hash()

See the main README for the full database schema.
