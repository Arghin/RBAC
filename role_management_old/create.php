<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = trim($_POST['role_name']);

    $canCreate = isset($_POST['Create']) ? 1 : 0;
    $canRead = isset($_POST['Read']) ? 1 : 0;
    $canEdit = isset($_POST['Edit']) ? 1 : 0;
    $canDelete = isset($_POST['Delete']) ? 1 : 0;

    if (!empty($role)) {
        $stmt = $conn->prepare("INSERT INTO roles (role_name, can_create, can_read, can_edit, can_delete) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiii", $role, $canCreate, $canRead, $canEdit, $canDelete);
        $stmt->execute();
        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Role</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="cre.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</head>
<body>

<div class="form-container">
    <h2><i class="fas fa-user-shield"></i> Create New Role</h2>
    
    <form method="POST">
        <label for="role_name"><i class="fas fa-id-badge"></i> Role Name</label>
        <input type="text" name="role_name" id="role_name" required>

        <div class="permissions">
            <label><input type="checkbox" name="Create" value="1"> <i class="fas fa-plus-circle"></i> Create</label>
            <label><input type="checkbox" name="Read" value="1"> <i class="fas fa-eye"></i> Read</label>
            <label><input type="checkbox" name="Edit" value="1"> <i class="fas fa-edit"></i> Edit</label>
            <label><input type="checkbox" name="Delete" value="1"> <i class="fas fa-trash-alt"></i> Delete</label>
        </div>

        <button type="submit"><i class="fas fa-save"></i> Create Role</button>
    </form>

    <div class="back-link">
        <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Role List</a>
    </div>
</div>

</body>
</html>
