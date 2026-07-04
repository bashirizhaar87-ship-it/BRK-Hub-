<?php
// Path: BRK-Hub/admin/downloads.php

// Centralized session security ko include kiya
require_once __DIR__ . '/../includes/session.php';
checkAdminLogin(); // Sirf logged in admin hi dekh sakega

require_once __DIR__ . '/../database/connection.php';

/*
  Fetch download history (Latest records sab se upar aayenge)
*/
$sql = "SELECT id, url, platform, status, ip_address, created_at FROM download_history ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download History - BRK Hub Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: #111827;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #1e2937;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #1e2937;
            padding-bottom: 15px;
        }

        .header-section h2 {
            margin: 0;
            color: #3b82f6;
        }

        .back-btn {
            color: white;
            text-decoration: none;
            background: #1f2937;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            border: 1px solid #374151;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #1e2937;
        }

        table th {
            background: #1f2937;
            color: #3b82f6;
            font-weight: bold;
        }

        table tr:hover {
            background: #1e2937;
        }

        /* Status Badges */
        .status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        .pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid #f59e0b; }
        .success { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981; }
        .failed  { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; }

        .url-text {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <h2>📥 Video Download History</h2>
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Video URL</th>
                <th>Platform</th>
                <th>Status</th>
                <th>IP Address</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                        <td>
                            <a class="url-text" href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank" title="<?php echo htmlspecialchars($row['url']); ?>" style="color: #9ca3af; text-decoration: none;">
                                <?php echo htmlspecialchars($row['url']); ?>
                            </a>
                        </td>
                        <td>
                            <strong style="color: #fff;"><?php echo htmlspecialchars(ucfirst($row['platform'])); ?></strong>
                        </td>
                        <td>
                            <span class="status <?php echo htmlspecialchars(strtolower($row['status'])); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td style="color: #9ca3af;"><?php echo htmlspecialchars($row['ip_address'] ?? '0.0.0.0'); ?></td>
                        <td style="color: #6b7280;"><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #9ca3af; padding: 30px;">No download records found in the database.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php
$conn->close();
?>
