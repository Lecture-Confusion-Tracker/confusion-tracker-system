<?php
/**
 * Database Configuration
 * ─────────────────────────────────────────────────────────────
 * AUTO-DETECTS connection. Tries passwords in order, then falls
 * back to SQLite so the app always runs without manual setup.
 * ─────────────────────────────────────────────────────────────
 */

$host   = 'localhost';
$dbname = 'confusion_tracker';
$dbuser = 'root';

// ── Try MySQL with common passwords ──────────────────────────
$passwords = ['', 'root', 'mysql', 'admin', '1234', 'password', 'lct2026'];
$pdo = null;

foreach ($passwords as $pwd) {
    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $dbuser, $pwd,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
        // Save working password to a local config file so we don't retry next time
        file_put_contents(__DIR__ . '/db_password.txt', $pwd);
        break;
    } catch (PDOException $e) {
        $pdo = null;
    }
}

// ── If MySQL failed, use SQLite ───────────────────────────────
if (!$pdo) {
    $sqliteFile = __DIR__ . '/confusion_tracker.sqlite';
    try {
        $pdo = new PDO(
            "sqlite:$sqliteFile",
            null, null,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
        $pdo->exec("PRAGMA journal_mode=WAL;");
        initSQLite($pdo);
    } catch (PDOException $e) {
        die("❌ Could not connect to MySQL or SQLite: " . $e->getMessage());
    }
}

// ── Lightweight schema safety (MySQL/SQLite) ──────────────────
ensureSchema($pdo);

// ── SQLite schema bootstrap ───────────────────────────────────
function initSQLite(PDO $db): void {
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            username   TEXT NOT NULL,
            email      TEXT NOT NULL UNIQUE,
            password   TEXT NOT NULL,
            role       TEXT NOT NULL DEFAULT 'student',
            created_at TEXT DEFAULT (datetime('now'))
        );
        CREATE TABLE IF NOT EXISTS courses (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            name       TEXT NOT NULL,
            created_at TEXT DEFAULT (datetime('now'))
        );
        CREATE TABLE IF NOT EXISTS confusions (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id     INTEGER NOT NULL,
            course_id   INTEGER NOT NULL,
            topic       TEXT NOT NULL,
            description TEXT NOT NULL,
            tag         TEXT,
            lecturer_feedback TEXT,
            created_at  TEXT DEFAULT (datetime('now'))
        );
        CREATE TABLE IF NOT EXISTS votes (
            id           INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id      INTEGER NOT NULL,
            confusion_id INTEGER NOT NULL,
            created_at   TEXT DEFAULT (datetime('now')),
            UNIQUE(user_id, confusion_id)
        );
        CREATE TABLE IF NOT EXISTS support_messages (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            name       TEXT NOT NULL,
            email      TEXT NOT NULL,
            subject    TEXT,
            message    TEXT NOT NULL,
            created_at TEXT DEFAULT (datetime('now'))
        );
    ");

    // Seed default courses if empty
    $count = $db->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    if ((int)$count === 0) {
        $db->exec("
            INSERT INTO courses (name) VALUES
            ('Web Development'),
            ('Database Systems'),
            ('Algorithms');
        ");
    }
}

function ensureSchema(PDO $db): void {
    // lecturer_feedback column (requested feature)
    try {
        $db->exec("ALTER TABLE confusions ADD COLUMN lecturer_feedback TEXT");
    } catch (Throwable $e) {
        // Ignore if column already exists or ALTER not supported
    }
}
