<?php
require_once __DIR__ . '/../config.php';

$username = 'admin';
$password = password_hash('password', PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO admins (username, password)
     VALUES (?, ?)
     ON DUPLICATE KEY UPDATE password = VALUES(password)"
);

$stmt->bind_param("ss", $username, $password);
$stmt->execute();

echo "Admin password reset successfully!<br>";
echo "Username: admin<br>";
echo "Password: password<br>";
echo '<a href="login.php">Go to Admin Login</a>';
?>