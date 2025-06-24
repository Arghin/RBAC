<?php
session_start();
include 'db.php';

// ✅ Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$isAdmin = $user['role'] === 'admin';
$canDelete = $user['can_delete'] ?? 0;

// 🔐 Only allow delete if user is admin or has delete permission
if (!$isAdmin && !$canDelete) {
    echo "<p style='color:red; text-align:center;'>Access denied: You do not have permission to delete.</p>";
    exit;
}

// 📥 Get ID and type (user or role)
$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? 'role'; // Default to role

// 🛑 Validate input
if (!$id || !in_array($type, ['role', 'user'])) {
    echo "<p style='color:red; text-align:center;'>Invalid delete request.</p>";
    exit;
}

// 🗂 Determine which table and redirect path to use
$table = ($type === 'user') ? 'users' : 'roles';
$redirect = ($type === 'user') ? 'manage_users.php' : 'index.php';

// 🧨 Prepare deletion
$stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
$stmt->bind_param("i", $id);

// 🔨 Execute
if ($stmt->execute()) {
    // Optional: Add logging here
}

// 🚀 Redirect
header("Location: $redirect");
exit;
