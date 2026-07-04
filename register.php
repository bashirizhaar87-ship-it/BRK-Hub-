<?php
// Path: BRK-Hub/register.php

require_once __DIR__ . '/api/config.php';

$message = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($email) || empty($password)) {
        $message = "All fields are required!";
        $status = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
        $status = "error";
    } else {
        // Check if user already exists
        $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ? OR password = ? LIMIT 1"); // fallback user check
        // Note: Aapke database table migration ke mutabik admin_users/users system structure hai.
        $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $message = "Username is already taken!";
            $status = "error";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
            $ins->bind_param("ss", $username, $hashed_password);
            
            if ($ins->execute()) {
                $message = "Registration successful! You can now login.";
                $status = "success";
            } else {
                $message = "Something went wrong. Try again.";
                $status = "error";
            }
            $ins->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account - BRK Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #0f172a;}
        .reg-box { background: #111827; padding: 30px; border-radius: 12px; width: 320px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); border: 1px solid #1e2937; }
        h3 { text-align: center; color: #0d6efd; margin-bottom: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; background: #1f2937; border: 1px solid #374151; color: white; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #0d6efd; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .msg { padding: 10px; border-radius: 5px; font-size: 14px; text-align: center; margin-bottom: 10px; }
        .error { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; }
        .success { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981; }
        p { font-size: 13px; text-align: center; color: #9ca3af; }
        p a { color: #0d6efd; text-decoration: none; }
    </style>
</head>
<body>
<div class="reg-box">
    <h3>Sign Up</h3>
    <?php if(!empty($message)): ?>
        <div class="msg <?php echo $status; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register Account</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
