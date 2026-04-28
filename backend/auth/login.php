<?php
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = cleanInput($_POST['email'] ?? $_POST['username'] ?? '');
    $password        = $_POST['password'] ?? '';

    if (empty($usernameOrEmail) || empty($password)) {
        $_SESSION['login_error'] = 'Email and password are required.';
        header('Location: ' . getBase() . 'frontend/login.php');
        exit;
    }

    $result = loginUser($usernameOrEmail, $password);

    if ($result === true) {
        $base = getBase();
        if (isLecturer()) {
            header('Location: ' . $base . 'frontend/lecturer/dashboard.php');
        } else {
            header('Location: ' . $base . 'frontend/student/dashboard.php');
        }
        exit;
    } else {
        $_SESSION['login_error'] = $result;
        header('Location: ' . getBase() . 'frontend/login.php');
        exit;
    }
}

// Redirect bare GET to login page
header('Location: ' . getBase() . 'frontend/login.php');
exit;

// ── Resolve base URL dynamically ─────────────────
function getBase(): string {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    // Find the project root (folder containing both frontend/ and backend/)
    $pos = strpos($script, '/backend/');
    if ($pos !== false) {
        return substr($script, 0, $pos) . '/';
    }
    return '/';
}
