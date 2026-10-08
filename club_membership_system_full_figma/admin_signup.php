<?php
session_start();
require_once "config.php";
$errors = [];
$fullname = "";
$username = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $admin_code = trim($_POST["admin_code"] ?? "");

    if ($fullname === "") $errors[] = "Full name is required.";
    if ($username === "") $errors[] = "Username is required.";
    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email address.";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters.";
    if ($admin_code !== "CLUBADMIN2026") $errors[] = "Invalid administrator code.";

    if (!$errors) {
        $check = $conn->prepare("SELECT id FROM admins WHERE username = ? OR email = ? LIMIT 1");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) $errors[] = "That admin username or email is already registered.";
        $check->close();
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admins (fullname, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $username, $email, $hash);
        if ($stmt->execute()) {
            $_SESSION["admin_signup_success"] = "Admin account created. You can now log in.";
            $stmt->close();
            header("Location: admin_login.php");
            exit();
        }
        $errors[] = "Could not create the administrator account.";
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Sign Up</title><link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page admin-signup-page">
<main class="auth-screen">
<section class="auth-form-panel admin-signup-form-panel">
<div class="auth-form-content">
<h1>Sign Up</h1>
<div class="mini-social" aria-hidden="true">◉ ♫ ◎</div>
<?php if ($errors): ?><div class="form-error"><?php foreach ($errors as $e): ?><div><?php echo htmlspecialchars($e); ?></div><?php endforeach; ?></div><?php endif; ?>
<form method="POST" action="admin_signup.php" novalidate>
<div class="input-row"><span class="field-icon">●</span><input name="fullname" placeholder="Full Name:" value="<?php echo htmlspecialchars($fullname); ?>" required></div>
<div class="input-row"><span class="field-icon">✉</span><input name="username" placeholder="Username:" value="<?php echo htmlspecialchars($username); ?>" required></div>
<div class="input-row"><span class="field-icon">♟</span><input type="email" name="email" placeholder="Email:" value="<?php echo htmlspecialchars($email); ?>" required></div>
<div class="input-row"><span class="field-icon">♟</span><input type="password" name="password" placeholder="Password:" required></div>
<div class="input-row"><span class="field-icon">▣</span><input type="password" name="admin_code" placeholder="Enter your admin code:" required></div>
<button class="auth-button" type="submit">Sign Up</button>
</form>
<div class="member-links"><span>Already have an account?</span><a href="admin_login.php">Log In</a></div>
</div></section></main>
</body></html>
