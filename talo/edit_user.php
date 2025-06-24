<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: manage_users.php");
    exit;
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    $can_create = isset($_POST['can_create']) ? 1 : 0;
    $can_read   = isset($_POST['can_read'])   ? 1 : 0;
    $can_edit   = isset($_POST['can_edit'])   ? 1 : 0;
    $can_delete = isset($_POST['can_delete']) ? 1 : 0;

    $update = $conn->prepare("UPDATE users SET username=?, role=?, status=?, can_create=?, can_read=?, can_edit=?, can_delete=? WHERE id=?");
    $update->bind_param("sssiiiii", $username, $role, $status, $can_create, $can_read, $can_edit, $can_delete, $id);
    $update->execute();

    header("Location: manage_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 20px;
        }

        form {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #198754;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }

        .permissions {
            margin-top: 20px;
        }

        .checkbox-group {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            font-weight: normal;
        }

        .btn {
            margin-top: 25px;
            width: 100%;
            background: #198754;
            color: white;
            padding: 12px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2><i class="fas fa-user-edit"></i> Edit User</h2>

    <label for="username"><i class="fas fa-user"></i> Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <label for="role"><i class="fas fa-user-tag"></i> Role</label>
    <select name="role" required>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
    </select>

    <label for="status"><i class="fas fa-toggle-on"></i> Status</label>
    <select name="status" required>
        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
    </select>

    <div class="permissions">
        <strong><i class="fas fa-key"></i> Permissions</strong><br><br>
        <div class="checkbox-group">
            <label><input type="checkbox" name="can_create" value="1" <?= $user['can_create'] ? 'checked' : '' ?>> <i class="fas fa-plus-circle"></i> Create</label>
            <label><input type="checkbox" name="can_read" value="1" <?= $user['can_read'] ? 'checked' : '' ?>> <i class="fas fa-eye"></i> Read</label>
            <label><input type="checkbox" name="can_edit" value="1" <?= $user['can_edit'] ? 'checked' : '' ?>> <i class="fas fa-pen"></i> Edit</label>
            <label><input type="checkbox" name="can_delete" value="1" <?= $user['can_delete'] ? 'checked' : '' ?>> <i class="fas fa-trash"></i> Delete</label>
        </div>
    </div>

    <button class="btn" type="submit"><i class="fas fa-save"></i> Save Changes</button>

    <div class="back-link">
        <a href="manage_users.php"><i class="fas fa-arrow-left"></i> Back to User List</a>
    </div>
</form>

</body>
</html>
