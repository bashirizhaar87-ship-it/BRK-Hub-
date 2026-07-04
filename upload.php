<?php
// Path: BRK-Hub/api/upload.php

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

// Check user or admin session
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_logged_in'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access. Please login first."
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? 'files');
    $version = trim($_POST['version'] ?? '1.0.0');
    $description = trim($_POST['description'] ?? '');

    if (empty($title) || !isset($_FILES['uploaded_file'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Title and File are required fields."
        ]);
        exit;
    }

    $file = $_FILES['uploaded_file'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Security check: Block dangerous extensions
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExts = ['apk', 'mp3', 'mp4', 'zip', 'pdf', 'jpg', 'png'];
    
    if (in_array($fileExt, ['php', 'phtml', 'php3', 'php4', 'php5', 'exe', 'sh', 'bat'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Security Alert: This file extension is not allowed!"
        ]);
        exit;
    }

    if ($fileError === 0) {
        // Max 50MB file size limit
        if ($fileSize <= 52428800) {
            
            // Map folder paths according to your structure
            $targetSubFolder = 'files/';
            if (strtolower($category) === 'apk') $targetSubFolder = 'users/'; // customized mapping
            // defaults to uploads/files/
            
            $uploadDir = __DIR__ . '/../uploads/' . $targetSubFolder;
            
            // Generate unique secure file name
            $newFileName = uniqid('BRK_', true) . '.' . $fileExt;
            $destination = $uploadDir . $newFileName;

            // Move file to destination folder
            if (move_uploaded_file($fileTmp, $destination)) {
                
                // Save info inside Database (Future expansion for files table)
                $stmt = $conn->prepare("INSERT INTO download_history (url, platform, status, title, file_type) VALUES (?, 'local_upload', 'success', ?, ?)");
                $fileUrlPath = 'uploads/' . $targetSubFolder . $newFileName;
                $stmt->bind_param("sss", $fileUrlPath, $title, $fileExt);
                
                if ($stmt->execute()) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "File uploaded and registered successfully!",
                        "file_path" => $fileUrlPath
                    ]);
                } else {
                    echo json_encode([
                        "status" => "success",
                        "message" => "File uploaded but database logging failed."
                    ]);
                }
                $stmt->close();
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to move file to destination folder. Check folder permissions."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "File size is too large. Maximum limit is 50MB."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "An error occurred during file transmission."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
$conn->close();
?>
