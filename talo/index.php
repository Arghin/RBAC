<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

$user = $_SESSION['user'];

// Always give full permissions to admin
if ($user['role'] === 'admin') {
    $canCreate = $canRead = $canEdit = $canDelete = 1;
    $isAdmin = true;
} else {
    $canCreate = $user['can_create'] ?? 0;
    $canRead   = $user['can_read'] ?? 0;
    $canEdit   = $user['can_edit'] ?? 0;
    $canDelete = $user['can_delete'] ?? 0;
    $isAdmin   = false;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Role Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/in.css">
    <link rel="stylesheet" href="css/ed.css">
    <style>
        .action-links {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .action-links a {
            background: #28a745;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .action-links a:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<h2><i class="fas fa-users-cog"></i> Role List</h2>

<div class="top-bar">
    <p>
        Welcome, <strong><?= htmlspecialchars($user['username']) ?></strong>
        (<?= htmlspecialchars($user['role']) ?>) |
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </p>
</div>

<?php if ($canCreate || $isAdmin): ?>
    <div class="action-links">

        <?php if ($isAdmin): ?>
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a><br>
            <a href="view_logs.php"><i class="fas fa-clipboard-list"></i> View Logs</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Role Name</th>
        <th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM roles ORDER BY id DESC");
    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['role_name']) ?></td>
        <td class="action-icons">
            <?php if ($canRead): ?>
                <a href="view.php?id=<?= $row['id'] ?>" title="View"><i class="fas fa-eye"></i></a>
            <?php endif; ?>
            <?php if ($canEdit): ?>
                <a href="edit.php?id=<?= $row['id'] ?>" title="Edit"><i class="fas fa-pen"></i></a>
            <?php endif; ?>
            <?php if ($canDelete): ?>
                <a href="delete.php?id=<?= $row['id'] ?>" title="Delete" onclick="return confirm('Delete this role?')">
                    <i class="fas fa-trash"></i>
                </a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="3">No roles found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
