<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Role Management</title>
    <!-- External styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="in.css">
    
</head>
<body>

<h2><i class="fas fa-users-cog"></i> Role List</h2>

<div class="top-bar">
    <p>
        Welcome, <strong><?= $_SESSION['user']['username'] ?></strong>
        (<?= $_SESSION['user']['role'] ?>) |
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </p>
</div>

<?php if ($_SESSION['user']['role'] === 'admin'): ?>
    <div class="create-button">
        <a href="create.php"><i class="fas fa-plus-circle"></i> Create New Role</a>
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
    while ($row = $result->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['role_name']) ?></td>
        <td class="action-icons">
            <a href="view.php?id=<?= $row['id'] ?>" title="View"><i class="fas fa-eye"></i></a>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="edit.php?id=<?= $row['id'] ?>" title="Edit"><i class="fas fa-pen"></i></a>
                <a href="delete.php?id=<?= $row['id'] ?>" title="Delete" onclick="return confirm('Delete this role?')">
                    <i class="fas fa-trash"></i>
                </a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
