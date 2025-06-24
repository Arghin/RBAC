<?php
session_start();
include 'db.php';

// Only admin can access this page
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: manage_users.php");
    exit;
}

// Fetch user info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/vied.css">
    
    <style>
        .user-box {
            max-width: 500px;
            margin: auto;
            padding: 25px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .user-box h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .user-box .field {
            margin-bottom: 15px;
        }
        .field label {
            font-weight: bold;
            display: block;
            color: #333;
        }
        .field span {
            display: block;
            padding: 8px;
            background: #f8f8f8;
            border: 1px solid #ddd;
        }
        .permissions {
            margin-top: 20px;
        }
        .permissions label {
            display: inline-block;
            margin-right: 20px;
        }
        .back-link {
            text-align: center;
            margin-top: 25px;
        }
    </style>
</head>
<body>

<div class="user-box">
    <h2><i class="fas fa-id-badge"></i> User Profile</h2>

    <div class="field">
        <label>Username:</label>
        <span><?= htmlspecialchars($user['username']) ?></span>
    </div>

    <div class="field">
        <label>Role:</label>
        <span><?= ucfirst($user['role']) ?></span>
    </div>

    <div class="field">
        <label>Status:</label>
        <span class="<?= $user['status'] === 'active' ? 'status-active' : 'status-inactive' ?>">
            <?= ucfirst($user['status']) ?>
        </span>
    </div>

    <div class="field">
        <label>Created At:</label>
        <span><?= date('F j, Y, g:i a', strtotime($user['created_at'])) ?></span>
    </div>
    <div class="permissions">
    <strong><i class="fas fa-key"></i> Permissions</strong><br><br>

    <div class="permissions">
        <label><input type="checkbox" <?= $user['can_create'] ? 'checked' : '' ?> disabled> <i class="fas fa-plus-circle"></i> Create</label>
        <label><input type="checkbox" <?= $user['can_read'] ? 'checked' : '' ?> disabled> <i class="fas fa-eye"></i> Read</label>
        <label><input type="checkbox" <?= $user['can_edit'] ? 'checked' : '' ?> disabled> <i class="fas fa-pen"></i> Edit</label>
        <label><input type="checkbox" <?= $user['can_delete'] ? 'checked' : '' ?> disabled> <i class="fas fa-trash"></i> Delete</label>
    </div>

    <div class="back-link">
        <a href="manage_users.php"><i class="fas fa-arrow-left"></i> Back to User List</a>
    </div>
</div>

</body>
</html>
