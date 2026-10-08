<?php
require_once "config.php";

$username = "admin";
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO admins (username, password)
     VALUES (?, ?)
     ON DUPLICATE KEY UPDATE password = VALUES(password)"
);
$stmt->bind_param("ss", $username, $hash);

if ($stmt->execute()) {
    echo "Admin account created/updated successfully.<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br><br>";
    echo "Delete create_admin.php from your project after using it.";
} else {
    echo "Failed to create admin account: " . htmlspecialchars($conn->error);
}
?>
