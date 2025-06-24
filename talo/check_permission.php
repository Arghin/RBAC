<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

function requirePermission($permissionKey) {
    global $user;

    if ($user['role'] === 'admin') return; // Admins always allowed

    if (empty($user[$permissionKey]) || $user[$permissionKey] != 1) {
        echo "<p style='color:red;text-align:center;'>Access denied: You don't have permission to perform this action.</p>";
        exit;
    }
}
