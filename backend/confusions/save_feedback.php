<?php
require_once __DIR__ . '/../auth/includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !isLecturer()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$confusion_id = (int)($_POST['confusion_id'] ?? 0);
$feedback     = trim((string)($_POST['feedback'] ?? ''));

if (!$confusion_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid confusion ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE confusions SET lecturer_feedback = ? WHERE id = ?");
    $stmt->execute([$feedback === '' ? null : $feedback, $confusion_id]);
    echo json_encode(['success' => true]);
    exit;
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to save feedback']);
    exit;
}

