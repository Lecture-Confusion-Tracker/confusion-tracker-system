<?php
require_once __DIR__ . '/../auth/includes/auth.php';

function getBase(): string {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $pos = strpos($script, '/backend/');
    return $pos !== false ? substr($script, 0, $pos) . '/' : '/';
}

$base = getBase();

if (!isLoggedIn()) {
    header('Location: ' . $base . 'frontend/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id   = (int)($_POST['course'] ?? 0);
    $topic       = cleanInput($_POST['topic'] ?? '');
    $description = cleanInput($_POST['description'] ?? '');
    $tag         = cleanInput($_POST['tag'] ?? '');
    $user_id     = getCurrentUserId();

    if (!$course_id || empty($topic) || empty($description)) {
        $_SESSION['form_error'] = 'Please fill in all required fields.';
        header('Location: ' . $base . 'frontend/student/add_confusion.php');
        exit;
    }

    if (strlen($description) > 500) {
        $_SESSION['form_error'] = 'Description must be under 500 characters.';
        header('Location: ' . $base . 'frontend/student/add_confusion.php');
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO confusions (user_id, course_id, topic, description, tag)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $course_id, $topic, $description, $tag ?: null]);

    header('Location: ' . $base . 'frontend/student/dashboard.php?submitted=1');
    exit;
}

header('Location: ' . $base . 'frontend/student/add_confusion.php');
exit;
