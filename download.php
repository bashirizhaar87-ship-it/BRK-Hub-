<?php
// Path: BRK-Hub/api/download.php

header('Content-Type: application/json');
// FIX: connection.php ki jagah config.php ko include kiya kyonke connection uske andar pehle se hai
require_once __DIR__ . '/config.php';

function getClientIP() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function detectPlatform($url, $manual) {
    if ($manual !== "auto" && in_array($manual, ['youtube', 'instagram', 'facebook', 'tiktok'])) {
        return $manual;
    }
    $url = strtolower($url);
    if (strpos($url, "youtube.com") !== false || strpos($url, "youtu.be") !== false) return "youtube";
    if (strpos($url, "instagram.com") !== false) return "instagram";
    if (strpos($url, "facebook.com") !== false) return "facebook";
    if (strpos($url, "tiktok.com") !== false) return "tiktok";
    return "unknown";
}

function saveHistory($conn, $url, $platform, $status, $ip, $userAgent) {
    $stmt = $conn->prepare("INSERT INTO download_history (url, platform, status, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssss", $url, $platform, $status, $ip, $userAgent);
        $stmt->execute();
        $stmt->close();
    }
}

$url = trim($_POST['url'] ?? '');
$platformInput = trim($_POST['platform'] ?? 'auto');
$ip = getClientIP();
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please enter a valid video URL."
    ]);
    exit;
}

// Check if downloads are enabled globally from config/database
if (isset($downloads_enabled) && $downloads_enabled === "0") {
    echo json_encode([
        "status" => "error",
        "message" => "Downloads are currently disabled by the administrator."
    ]);
    exit;
}

$platform = detectPlatform($url, $platformInput);

function generateRealDownloadLink($url, $platform) {
    // FIX: Dummy example.com domain hata diya hai. 
    // Agar aapke paas koi paid/personal video scraping API engine hai toh uska link yahan aayega.
    // Filhaal yeh direct video URL hi return karega testing ke liye.
    return $url; 
}

$download_url = generateRealDownloadLink($url, $platform);

if ($download_url) {
    saveHistory($conn, $url, $platform, 'success', $ip, $userAgent);
    echo json_encode([
        "status" => "success",
        "platform" => $platform,
        "download_url" => $download_url
    ]);
} else {
    saveHistory($conn, $url, $platform, 'failed', $ip, $userAgent);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to extract video links from this platform."
    ]);
}

$conn->close();
?>

