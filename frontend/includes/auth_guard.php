<?php
/**
 * Auth Guard — include at the top of any protected page.
 * 
 * BACKEND DEV: Replace the session check below with your real
 * session/token validation once auth is implemented.
 * 
 * Usage:
 *   require_once '../includes/auth_guard.php';
 *   guardRole('student');   // or 'lecturer'
 */

if (session_status() === PHP_SESSION_NONE) session_start();

function guardRole(string $requiredRole = ''): void {
    // BACKEND: swap $_SESSION['user_id'] with your real auth check
    $loggedIn = isset($_SESSION['user_id']);
    $role     = $_SESSION['user_role'] ?? ($_SESSION['role'] ?? '');

    if (!$loggedIn) {
        header('Location: ../login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }

    if ($requiredRole && $role !== $requiredRole) {
        // Wrong role — redirect to their own dashboard
        if ($role === 'student') {
            header('Location: ../student/dashboard.php');
        } elseif ($role === 'lecturer') {
            header('Location: ../lecturer/dashboard.php');
        } else {
            header('Location: ../index.php');
        }
        exit;
    }
}
