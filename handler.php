<?php
// BRK Hub - Downloader Handler (v1)

header('Content-Type: application/json');

require_once __DIR__ . '/../database/connection.php'; 
// (agar connection.php nahi hai to main next step me bana dunga)

function detectPlatform($url, $manual) {
    if ($manual !== "auto") return $manual;

    if (strpos($url, "youtube.com") !== false || strpos($url, "youtu.be") !== false) {
        return "youtube";
    }
    if (strpos($url, "instagram.com") !== false) {
        return "instagram";
    }
    if (strpos($url, "facebook.com") !== false) {
        return "facebook";
    }
    if (strpos($url, "tiktok.com") !== false) {
        return "tiktok";
    }

    return "unknown";
}

function saveHistory($conn, $url, $platform, $status, $ip) {
    $stmt = $conn->prepare("
        INSERT INTO download_history (url, platform, status, ip_address)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssss", $url, $platform, $status, $ip);
    $stmt->execute();
}

$url = $_POST['url'] ?? '';
$platform = $_POST['platform'] ?? 'auto';
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

if (empty($url)) {
    echo json_encode([
        "status" => "error",
        "message" => "URL is required"
    ]);
    exit;
}

$platform = detectPlatform($url, $platform);

// ❗ Future: Real API integration yahan lagegi
function generateDownloadLink($url, $platform) {
    // Demo placeholder logic
    return "https://example.com/download?video=" . urlencode($url);
}

$download_url = generateDownloadLink($url, $platform);

// Save history (if DB exists)
try {
    if (isset($conn)) {
        saveHistory($conn, $url, $platform, "success", $ip);
    }
} catch (Exception $e) {
    // ignore DB error in v1
}

echo json_encode([
    "status" => "success",
    "platform" => $platform,
    "download_url" => $download_url
]);