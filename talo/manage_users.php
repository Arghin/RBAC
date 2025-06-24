<?php
session_start();
include 'db.php';

// Access control: only admin allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/muse.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7fa;
            padding: 30px;
        }

        .top-bar {
            text-align: left;
            margin-bottom: 20px;
        }

        .top-bar a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .add-btn-wrap {
            text-align: center;
            margin-bottom: 20px;
        }

        .add-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #198754;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .add-btn:hover {
            background-color: #157347;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #0d6efd;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-icons a {
            margin: 0 6px;
            text-decoration: none;
            color: #0d6efd;
            font-size: 18px;
        }

        .action-icons a:hover {
            color: #0a58ca;
        }

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-inactive {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<h2><i class="fas fa-users"></i> User Management</h2>

<div class="add-btn-wrap">
    <a class="add-btn" href="add_user.php"><i class="fas fa-user-plus"></i> Add New User</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
    if ($result->num_rows > 0):
        while ($user = $result->fetch_assoc()):
    ?>
    <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
        <td class="<?= $user['status'] === 'active' ? 'status-active' : 'status-inactive' ?>">
            <?= ucfirst($user['status']) ?>
        </td>
        <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
        <td class="action-icons">
            <a href="view_user.php?id=<?= $user['id'] ?>" title="View"><i class="fas fa-eye"></i></a>
            <a href="edit_user.php?id=<?= $user['id'] ?>" title="Edit"><i class="fas fa-pen"></i></a>
            <a href="delete_user.php?id=<?= $user['id'] ?>" title="Delete" onclick="return confirm('Delete this user?')"><i class="fas fa-trash"></i></a>
            <a href="toggle_user.php?id=<?= $user['id'] ?>" title="Toggle Status"><i class="fas fa-toggle-on"></i></a>
        </td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="6">No users found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
