<?php
// Path: BRK-Hub/admin/dashboard.php

// Centralized session helper ko include kiya
require_once __DIR__ . '/../includes/session.php';
checkAdminLogin(); // Agar admin logged in nahi hoga to redirect ho jayega

require_once __DIR__ . '/../database/connection.php';

// Total downloads count
$totalQuery = "SELECT COUNT(*) as total FROM download_history";
$totalResult = $conn->query($totalQuery);
$totalDownloads = $totalResult->fetch_assoc()['total'] ?? 0;

// Today's downloads count
$todayQuery = "SELECT COUNT(*) as total FROM download_history WHERE DATE(created_at) = CURDATE()";
$todayResult = $conn->query($todayQuery);
$todayDownloads = $todayResult->fetch_assoc()['total'] ?? 0;

// Pending downloads count
$pendingQuery = "SELECT COUNT(*) as total FROM download_history WHERE status='pending'";
$pendingResult = $conn->query($pendingQuery);
$pendingDownloads = $pendingResult->fetch_assoc()['total'] ?? 0;

// Failed downloads count
$failedQuery = "SELECT COUNT(*) as total FROM download_history WHERE status='failed'";
$failedResult = $conn->query($failedQuery);
$failedDownloads = $failedResult->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - BRK Hub</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0f172a; color: #fff; margin: 0; }
        .header { background: #111827; color: white; padding: 15px 20px; border-bottom: 1px solid #1e2937; }
        .topbar { display: flex; justify-content: space-between; align-items: center; }
        .logout-btn { color: white; text-decoration: none; background: #ef4444; padding: 8px 15px; border-radius: 5px; font-weight: bold; transition: 0.3s; }
        .logout-btn:hover { background: #dc2626; }
        .container { padding: 30px; }
        .welcome-text { margin-bottom: 25px; color: #9ca3af; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .card { background: #111827; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #1e2937; }
        .card h2 { margin: 0; font-size: 32px; font-weight: bold; }
        .card p { margin: 8px 0 0; color: #9ca3af; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        .total { border-top: 4px solid #3b82f6; }
        .today { border-top: 4px solid #10b981; }
        .pending { border-top: 4px solid #f59e0b; }
        .failed { border-top: 4px solid #ef4444; }
    </style>
</head>
<body>

<div class="header">
    <div class="topbar">
        <h2>📊 BRK Hub Admin Dashboard</h2>
        <!-- Naye structure ke mutabik root level logout link -->
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="container">
    <div class="welcome-text">
        Welcome back, <strong><?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'Admin'); ?></strong>! Here is your website activity overview.
    </div>

    <div class="cards">
        <div class="card total">
            <h2><?php echo htmlspecialchars($totalDownloads); ?></h2>
            <p>Total Downloads</p>
        </div>

        <div class="card today">
            <h2><?php echo htmlspecialchars($todayDownloads); ?></h2>
            <p>Today Downloads</p>
        </div>

        <div class="card pending">
            <h2><?php echo htmlspecialchars($pendingDownloads); ?></h2>
            <p>Pending Queue</p>
        </div>

        <div class="card failed">
            <h2><?php echo htmlspecialchars($failedDownloads); ?></h2>
            <p>Failed Attempts</p>
        </div>
    </div>
</div>

</body>
</html>
