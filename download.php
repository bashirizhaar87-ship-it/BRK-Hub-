<?php
require_once __DIR__ . '/database/connection.php';

/*
  Get client IP
*/
function getClientIP()
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/*
  Detect platform (basic)
*/
function detectPlatform($url)
{
    $url = strtolower($url);

    if (strpos($url, 'youtube') !== false) return 'YouTube';
    if (strpos($url, 'facebook') !== false) return 'Facebook';
    if (strpos($url, 'instagram') !== false) return 'Instagram';
    if (strpos($url, 'tiktok') !== false) return 'TikTok';

    return 'Other';
}

/*
  Save download record
*/
function saveDownload($conn, $url, $platform, $status, $ip)
{
    $stmt = $conn->prepare("
        INSERT INTO download_history (url, platform, status, ip_address)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("ssss", $url, $platform, $status, $ip);
    return $stmt->execute();
}

/*
  Main logic
*/
$url = $_POST['url'] ?? '';

if (empty($url)) {
    die("No URL provided");
}

$ip = getClientIP();
$platform = detectPlatform($url);

/*
  Simple validation
*/
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    saveDownload($conn, $url, $platform, 'failed', $ip);
    die("Invalid URL");
}

/*
  Here you can later add real processing logic
  (video/audio download, API call, etc.)
*/

$status = 'success';

// Save record
saveDownload($conn, $url, $platform, $status, $ip);

/*
  Response
*/
echo json_encode([
    "status" => "ok",
    "message" => "Download recorded successfully",
    "platform" => $platform
]);