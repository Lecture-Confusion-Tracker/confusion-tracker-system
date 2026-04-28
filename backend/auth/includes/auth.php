<?php
// Start session at the top
session_start();

require_once __DIR__ . '/db.php';

// ==================== HELPER FUNCTIONS ====================

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check user role
function isStudent() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

function isLecturer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'lecturer';
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit();
}

// Get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// ==================== SECURITY & VALIDATION ====================

// Basic input sanitization
function cleanInput($data) {
    return trim(htmlspecialchars(stripslashes($data)));
}

// ==================== AUTH FUNCTIONS ====================

// Register new user
function registerUser($username, $email, $password, $role) {
    global $pdo;

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        return "Username or Email already exists!";
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $hashedPassword, $role])) {
        return true;
    }
    return "Registration failed. Please try again.";
}

// Login user
function loginUser($usernameOrEmail, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Regenerate session ID for security
        session_regenerate_id(true);

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['email']     = $user['email'];
        $_SESSION['role']      = $user['role'];
        // Aliases used by frontend header & auth_guard
        $_SESSION['user_name'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];

        return true;
    }
    return "Invalid username/email or password!";
}

// Logout
function logoutUser() {
    session_unset();
    session_destroy();
}
?>