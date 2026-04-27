<?php
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = cleanInput($_POST['username'] ?? '');
    $password        = $_POST['password'] ?? '';

    if (empty($usernameOrEmail) || empty($password)) {
        $error = "Username/Email and password are required!";
    } else {
        $result = loginUser($usernameOrEmail, $password);
        
        if ($result === true) {
            // Redirect based on role
            if (isLecturer()) {
                redirect('../../frontend/lecturer/dashboard.php');
            } else {
                redirect('../../frontend/student/dashboard.php');
            }
        } else {
            $error = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Username or Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>