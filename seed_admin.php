<?php
// Termux Specific Fixed Database Configuration
$host = "127.0.0.1"; // Localhost error bypass karne ke liye loopback IP
$user = "root";
$pass = "";
$db   = "brk_hub";
$port = 3306; // Default MariaDB Port

// Error checking block ke sath connection link
mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
try {
    $conn = new mysqli($host, $user, $pass, $db, $port);
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . $e->getMessage() . "\nSuggestions: Check if 'mysqld_safe &' is running in background.\n");
}

$conn->set_charset("utf8mb4");

$username = 'admin';
$password = 'ChangeMe123!';

// Check if admin already exists
$stmt = $conn->prepare("SELECT id FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Admin already exists.\n";
    exit;
}
$stmt->close();

// Hash password securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo "====================================\n";
    echo "Admin account created successfully!\n";
    echo "Username: admin\n";
    echo "Password: ChangeMe123!\n";
    echo "====================================\n";
} else {
    echo "Error: " . $stmt->error . "\n";
}
$stmt->close();
$conn->close();
?>
