<?php
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = cleanInput($_POST['username'] ?? '');
    $email    = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'student';   // default to student

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        $result = registerUser($username, $email, $password, $role);
        
        if ($result === true) {
            // Auto login after successful registration
            loginUser($username, $password);
            redirect('../../frontend/index.php');   // Change path if needed
        } else {
            $error = $result;
        }
    }
}
?>

<!-- You can include this HTML part or make it only backend and let frontend call it via form action -->
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        
        <label>Role:</label>
        <select name="role">
            <option value="student">Student</option>
            <option value="lecturer">Lecturer</option>
        </select><br>
        
        <button type="submit">Register</button>
    </form>
</body>
</html>