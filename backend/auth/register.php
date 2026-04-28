<?php
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = cleanInput($_POST['name'] ?? $_POST['username'] ?? '');
    $email    = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'student';
    $base     = getBase();

    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['register_error'] = 'All fields are required.';
        header('Location: ' . $base . 'frontend/register.php');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['register_error'] = 'Password must be at least 6 characters.';
        header('Location: ' . $base . 'frontend/register.php');
        exit;
    }

    $result = registerUser($username, $email, $password, $role);

    if ($result === true) {
        loginUser($email, $password);
        if (isLecturer()) {
            header('Location: ' . $base . 'frontend/lecturer/dashboard.php');
        } else {
            header('Location: ' . $base . 'frontend/student/dashboard.php');
        }
        exit;
    } else {
        $_SESSION['register_error'] = $result;
        header('Location: ' . $base . 'frontend/register.php');
        exit;
    }
}

header('Location: ' . getBase() . 'frontend/register.php');
exit;

function getBase(): string {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $pos = strpos($script, '/backend/');
    if ($pos !== false) {
        return substr($script, 0, $pos) . '/';
    }
    return '/';
}
