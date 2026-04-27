<?php
// Database Configuration
$host     = 'localhost';
$dbname   = 'confusion_tracker';   
$username = 'root';
$password = '';                    // XAMPP default is empty password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>