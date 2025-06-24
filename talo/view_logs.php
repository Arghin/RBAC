<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db.php';
$result = $conn->query("SELECT * FROM logs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login & Activity Logs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
        }
        .top-bar {
            text-align: left;
            margin-bottom: 20px;
        }
        .top-bar a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: bold;
        }
        .top-bar a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #0d6efd;
            color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<h2><i class="fas fa-history"></i> Login & Activity Logs</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username / Action</th>
        <th>Status</th>
        <th>IP Address</th>
        <th>Date</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td>
            <?php
            // Show different icons for login and other actions
            if ($row['status'] === 'success') {
                echo '<span style="color: green;"><i class="fas fa-check-circle"></i> Success</span>';
            } elseif ($row['status'] === 'fail') {
                echo '<span style="color: red;"><i class="fas fa-times-circle"></i> Fail</span>';
            } else {
                // For info/status like activation/deactivation
                echo '<span style="color: #0d6efd;"><i class="fas fa-info-circle"></i> ' . htmlspecialchars(ucfirst($row['status'])) . '</span>';
            }
            ?>
        </td>
        <td><?= $row['ip_address'] ?></td>
        <td><?= $row['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>