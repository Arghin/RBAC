
<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'role_management';

// Enable error reporting during development (disable in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8mb4"); // Set character set
} catch (Exception $e) {
    // Log error or display generic message
    die("Database connection failed: " . $e->getMessage());
}
?>
