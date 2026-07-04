<?php
/**
 * BRK Hub
 * Secure Configuration File
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
*/

define('DB_HOST', 'localhost');
define('DB_NAME', 'brk_hub');
define('DB_USER', 'root');
define('DB_PASS', '');

/*
|--------------------------------------------------------------------------
| Website Configuration
|--------------------------------------------------------------------------
*/

define('SITE_URL', '');
define('SITE_NAME', 'BRK Hub');
define('SITE_VERSION', '2.0.0');

date_default_timezone_set('Asia/Karachi');

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| PDO Database Connection
|--------------------------------------------------------------------------
*/

try {

    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    exit("Database Connection Failed : " . $e->getMessage());

}

/*
|--------------------------------------------------------------------------
| Load Website Settings
|--------------------------------------------------------------------------
*/

$settings = [];

try {

    $stmt = $pdo->query("SELECT * FROM settings LIMIT 1");

    $settings = $stmt->fetch();

} catch (Exception $e) {

    $settings = [];

}

/*
|--------------------------------------------------------------------------
| Security Headers
|--------------------------------------------------------------------------
*/

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

/*
|--------------------------------------------------------------------------
| Helper Constants
|--------------------------------------------------------------------------
*/

define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('APK_PATH', UPLOAD_PATH . 'apk/');
define('VIDEO_PATH', UPLOAD_PATH . 'videos/');
define('SONG_PATH', UPLOAD_PATH . 'songs/');

/*
|--------------------------------------------------------------------------
| Create Upload Directories Automatically
|--------------------------------------------------------------------------
*/

$directories = [
    UPLOAD_PATH,
    APK_PATH,
    VIDEO_PATH,
    SONG_PATH
];

foreach ($directories as $dir) {

    if (!is_dir($dir)) {

        mkdir($dir, 0777, true);

    }

}
?>