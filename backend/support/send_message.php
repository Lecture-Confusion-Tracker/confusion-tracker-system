<?php
require_once __DIR__ . '/../auth/includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$name    = trim(htmlspecialchars($_POST['name'] ?? ''));
$email   = trim($_POST['email'] ?? '');
$subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
$message = trim(htmlspecialchars($_POST['message'] ?? ''));

if (!$name || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO support_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $subject, $message]);

echo json_encode(['success' => true, 'message' => 'Message received. We will get back to you within 24 hours.']);
exit;
