<?php
session_start();
include 'db.php';

// Only admin can access this page
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    $canCreate = isset($_POST['can_create']) ? 1 : 0;
    $canRead   = isset($_POST['can_read']) ? 1 : 0;
    $canEdit   = isset($_POST['can_edit']) ? 1 : 0;
    $canDelete = isset($_POST['can_delete']) ? 1 : 0;

    if (!empty($username) && !empty($password)) {
        // Check if username already exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            // Optional: secure password storage
            // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role, can_create, can_read, can_edit, can_delete)
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiiii", $username, $password, $role, $canCreate, $canRead, $canEdit, $canDelete);
            if ($stmt->execute()) {
                $success = "✅ User added successfully.";
            } else {
                $error = "Failed to add user.";
            }
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/aduse.css">
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
    <a href="manage_users.php"><i class="fas fa-arrow-left"></i> Back to Manage Users</a>
</div>

<form method="POST">
    <h2><i class="fas fa-user-plus"></i> Add User</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red; font-weight:bold;"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></p>
    <?php elseif (!empty($success)): ?>
        <p style="color:green; font-weight:bold;"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <label for="username"><i class="fas fa-user"></i> Username</label>
    <input type="text" name="username" id="username" required>

    <label for="password"><i class="fas fa-lock"></i> Password</label>
    <input type="password" name="password" id="password" required>

    <label for="role"><i class="fas fa-user-tag"></i> Role</label>
    <select name="role" id="role" required>
        <option value="">-- Select Role --</option>
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>

    <div class="permissions">
        <strong><i class="fas fa-key"></i> Permissions</strong><br><br>
        <div class="checkbox-group">
            <label><input type="checkbox" name="can_create" value="1"> <i class="fas fa-plus-circle"></i> Create</label>
            <label><input type="checkbox" name="can_read" value="1"> <i class="fas fa-eye"></i> Read</label>
            <label><input type="checkbox" name="can_edit" value="1"> <i class="fas fa-pen"></i> Edit</label>
            <label><input type="checkbox" name="can_delete" value="1"> <i class="fas fa-trash"></i> Delete</label>
        </div>
    </div>

    <button class="btn" type="submit"><i class="fas fa-user-plus"></i> Add User</button>
</form>

</body>
</html>
