<?php
require_once __DIR__ . '/includes/auth.php';
logoutUser();

$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$pos    = strpos($script, '/backend/');
$base   = $pos !== false ? substr($script, 0, $pos) . '/' : '/';

header('Location: ' . $base . 'frontend/index.php');
exit;
