<?php
// Path: BRK-Hub/admin/login.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../database/connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Username and Password are required!";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_user'] = $admin['username'];
                $_SESSION['login_time'] = time();

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Invalid username or password!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - BRK Hub</title>
    <style>
        body { font-family: Arial; background: #0f172a; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: #111827; padding: 25px; border-radius: 10px; width: 300px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        h3 { text-align: center; margin-bottom: 20px; color: #0d6efd; }
        input { width: 100%; padding: 10px; margin: 8px 0; background: #1f2937; border: 1px solid #374151; color: white; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #0d6efd; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        button:hover { background: #0b5ed7; }
        .error { color: #ef4444; margin-bottom: 10px; font-size: 14px; text-align: center; }
    </style>
</head>
<body>

<div class="box">
    <h3>Admin Login</h3>

    <?php if(!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
