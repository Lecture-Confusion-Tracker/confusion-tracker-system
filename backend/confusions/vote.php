<?php
require_once __DIR__ . '/../auth/includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$confusion_id = (int)($_POST['confusion_id'] ?? 0);
$user_id      = getCurrentUserId();

if (!$confusion_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid confusion ID']);
    exit;
}

// Check if already voted
$stmt = $pdo->prepare("SELECT id FROM votes WHERE user_id = ? AND confusion_id = ?");
$stmt->execute([$user_id, $confusion_id]);
$existing = $stmt->fetch();

if ($existing) {
    // Remove vote (toggle off)
    $pdo->prepare("DELETE FROM votes WHERE user_id = ? AND confusion_id = ?")
        ->execute([$user_id, $confusion_id]);
    $voted = false;
} else {
    // Add vote
    $pdo->prepare("INSERT INTO votes (user_id, confusion_id) VALUES (?, ?)")
        ->execute([$user_id, $confusion_id]);
    $voted = true;
}

// Get updated vote count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE confusion_id = ?");
$stmt->execute([$confusion_id]);
$count = (int)$stmt->fetchColumn();

echo json_encode(['success' => true, 'voted' => $voted, 'count' => $count]);
exit;
