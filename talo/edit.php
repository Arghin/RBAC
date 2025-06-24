<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$isAdmin = $user['role'] === 'admin';
$canEdit = $user['can_edit'] ?? 0;

// Only allow admin or users with edit permission
if (!$isAdmin && !$canEdit) {
    echo "<p style='color:red; text-align:center;'>Access denied: You do not have permission to edit roles.</p>";
    exit;
}

if (!$isAdmin && !$canEdit) {
    echo "<p style='color:red; text-align:center;'>Access denied: You do not have permission to edit roles.</p>";
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

// Fetch role info
$stmt = $conn->prepare("SELECT * FROM roles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$role = $stmt->get_result()->fetch_assoc();

if (!$role) {
    echo "<p>Role not found.</p>";
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_name = trim($_POST['role_name']);

    $can_create = isset($_POST['can_create']) ? 1 : 0;
    $can_read   = isset($_POST['can_read'])   ? 1 : 0;
    $can_edit   = isset($_POST['can_edit'])   ? 1 : 0;
    $can_delete = isset($_POST['can_delete']) ? 1 : 0;

    if (!empty($role_name)) {
        $update = $conn->prepare("UPDATE roles SET role_name=?, can_create=?, can_read=?, can_edit=?, can_delete=? WHERE id=?");
        $update->bind_param("siiiii", $role_name, $can_create, $can_read, $can_edit, $can_delete, $id);
        $update->execute();
        header("Location: index.php");
        exit;
    } else {
        $error = "Role name is required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Role</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/ed.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0d6efd;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }
        .permissions {
            margin-top: 20px;
        }
        .permissions label {
            display: block;
            margin: 8px 0;
        }
        button {
            margin-top: 25px;
            background: #0d6efd;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: #0b5ed7;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .back-link a {
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2><i class="fas fa-user-edit"></i> Edit Role</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="role_name"><i class="fas fa-id-badge"></i> Role Name</label>
        <input type="text" name="role_name" id="role_name" required value="<?= htmlspecialchars($role['role_name']) ?>">

        <div class="permissions">
            <strong><i class="fas fa-key"></i> Permissions</strong><br><br>
            <label><input type="checkbox" name="can_create" value="1" <?= $role['can_create'] ? 'checked' : '' ?>> Create</label>
            <label><input type="checkbox" name="can_read" value="1" <?= $role['can_read'] ? 'checked' : '' ?>> Read</label>
            <label><input type="checkbox" name="can_edit" value="1" <?= $role['can_edit'] ? 'checked' : '' ?>> Edit</label>
            <label><input type="checkbox" name="can_delete" value="1" <?= $role['can_delete'] ? 'checked' : '' ?>> Delete</label>
        </div>

        <button type="submit"><i class="fas fa-save"></i> Save Changes</button>
    </form>

    <div class="back-link">
        <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Role List</a>
    </div>
</div>

</body>
</html>
