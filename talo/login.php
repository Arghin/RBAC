<?php
session_start();
include 'db.php';

function getIP() {
    return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $secretKey = '6Ld1nmkrAAAAAFZeHMZGZuUbGnfpUCzytLDDtSBX';

    // reCAPTCHA verification
    $verifyURL = 'https://www.google.com/recaptcha/api/siteverify';
    $data = ['secret' => $secretKey, 'response' => $recaptchaResponse, 'remoteip' => getIP()];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verifyURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $recaptchaResult = curl_exec($ch);
    curl_close($ch);
    $responseData = json_decode($recaptchaResult);

    if (!$responseData->success) {
        $error = "reCAPTCHA failed. Please try again.";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $ip = getIP();

        if ($user) {
            // Check if user is inactive
            if ($user['status'] === 'inactive') {
                $error = "Your account is inactive. Please contact admin to reactivate your account.";
            }
            // Check if locked due to too many attempts
            elseif ($user['failed_attempts'] >= 5 && strtotime($user['last_failed_attempt']) > strtotime('-1 day')) {
                $error = "Too many failed attempts. Please try again after 24 hours.";
            }
            elseif ($user['password'] === $password) {
                // SUCCESS: Reset failed attempts and update last login
                $reset = $conn->prepare("UPDATE users SET failed_attempts = 0, last_failed_attempt = NULL, last_login = NOW() WHERE id = ?");
                $reset->bind_param("i", $user['id']);
                $reset->execute();

                $_SESSION['user'] = [
                    'id'          => $user['id'],
                    'username'    => $user['username'],
                    'role'        => $user['role'],
                    'can_create'  => $user['can_create'],
                    'can_read'    => $user['can_read'],
                    'can_edit'    => $user['can_edit'],
                    'can_delete'  => $user['can_delete']
                ];

                $log = $conn->prepare("INSERT INTO logs (user_id, username, action, status, ip_address) VALUES (?, ?, 'login', 'success', ?)");
                $log->bind_param("iss", $user['id'], $username, $ip);
                $log->execute();

                header("Location: index.php");
                exit;
            } else {
                // FAIL: Increment failed attempts
                $updateFail = $conn->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_attempt = NOW() WHERE id = ?");
                $updateFail->bind_param("i", $user['id']);
                $updateFail->execute();

                $log = $conn->prepare("INSERT INTO logs (user_id, username, action, status, ip_address) VALUES (?, ?, 'login', 'fail', ?)");
                $log->bind_param("iss", $user['id'], $username, $ip);
                $log->execute();

                $error = "Invalid password. Attempt " . ($user['failed_attempts'] + 1) . " of 5.";
            }
        } else {
            $error = "Username not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
</head>
<body>

<form method="POST">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <div class="g-recaptcha" data-sitekey="6Ld1nmkrAAAAAFZVZbp986t_zr09RNSoIbRjKB-c" data-action="LOGIN"></div>

    <button type="submit">Login</button>
</form>

</body>
</html>
