<?php
// Path: BRK-Hub/login.php

require_once __DIR__ . '/api/config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid credentials!";
            }
        } else {
            $error = "Invalid credentials!";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - BRK Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #0f172a; }
        .login-box { background: #111827; padding: 30px; border-radius: 12px; width: 320px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); border: 1px solid #1e2937; }
        h3 { text-align: center; color: #10b981; margin-bottom: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; background: #1f2937; border: 1px solid #374151; color: white; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .error { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; padding: 10px; border-radius: 5px; font-size: 14px; text-align: center; margin-bottom: 10px; }
        p { font-size: 13px; text-align: center; color: #9ca3af; }
        p a { color: #10b981; text-decoration: none; }
    </style>
</head>
<body>
<div class="login-box">
    <h3>User Login</h3>
    <?php if(!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign In</button>
    </form>
    <p>Don't have an account? <a href="register.php">Sign up</a></p>
</div>
</body>
</html>
