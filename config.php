<?php
// Path: BRK-Hub/api/config.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global Headers for API Security
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

// Database configuration bridge
require_once __DIR__ . '/../database/connection.php';

// Fetch dynamic settings from database helper function
function getSetting($conn, $key, $default = "") {
    if (!$conn) return $default;
    $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            $value = $row['setting_value'];
            $stmt->close();
            return $value;
        }
        $stmt->close();
    }
    return $default;
}

// Site settings variables
$site_name = getSetting($conn, 'site_name', 'BRK Hub');
$maintenance_mode = getSetting($conn, 'maintenance_mode', '0');
$downloads_enabled = getSetting($conn, 'downloads_enabled', '1');

// Check Maintenance Mode
if ($maintenance_mode === "1" && !isset($_SESSION['admin_logged_in'])) {
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "error",
        "message" => "Server is under maintenance. Please try again later."
    ]);
    exit;
}
?>

