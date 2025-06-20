<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Invalid role ID.</p>";
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM roles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$role = $result->fetch_assoc();

if (!$role) {
    echo "<p>Role not found.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = trim($_POST['role_name']);
    $canCreate = isset($_POST['Create']) ? 1 : 0;
    $canRead = isset($_POST['Read']) ? 1 : 0;
    $canEdit = isset($_POST['Edit']) ? 1 : 0;
    $canDelete = isset($_POST['Delete']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE roles SET role_name = ?, can_create = ?, can_read = ?, can_edit = ?, can_delete = ? WHERE id = ?");
    $stmt->bind_param("siiiii", $newName, $canCreate, $canRead, $canEdit, $canDelete, $id);
    $stmt->execute();
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Role</title>
    <!-- Font Awesome -->
     <link rel="stylesheet" href="ed.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</head>
<body>

<div class="form-container">
    <h2><i class="fas fa-pen-to-square"></i> Edit Role</h2>

    <form method="POST">
        <label for="role_name"><i class="fas fa-id-badge"></i> Role Name</label>
        <input type="text" name="role_name" id="role_name" required value="<?= htmlspecialchars($role['role_name']) ?>">

        <div class="permissions">
            <label><input type="checkbox" name="Create" value="1" <?= $role['can_create'] ? 'checked' : '' ?>> <i class="fas fa-plus-circle"></i> Create</label>
            <label><input type="checkbox" name="Read" value="1" <?= $role['can_read'] ? 'checked' : '' ?>> <i class="fas fa-eye"></i> Read</label>
            <label><input type="checkbox" name="Edit" value="1" <?= $role['can_edit'] ? 'checked' : '' ?>> <i class="fas fa-edit"></i> Edit</label>
            <label><input type="checkbox" name="Delete" value="1" <?= $role['can_delete'] ? 'checked' : '' ?>> <i class="fas fa-trash-alt"></i> Delete</label>
        </div>

        <button type="submit"><i class="fas fa-save"></i> Update Role</button>
    </form>

    <div class="back-link">
        <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Role List</a>
    </div>
</div>

</body>
</html>
