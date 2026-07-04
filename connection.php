<?php
// Path: BRK-Hub/database/connection.php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "brk_hub";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]));
}
$conn->set_charset("utf8mb4");
?>
