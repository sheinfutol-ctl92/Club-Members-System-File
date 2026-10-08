<?php
session_start();
require_once "config.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identity = trim($_POST["identity"] ?? "");
    $password = $_POST["password"] ?? "";
    if ($identity === "" || $password === "") {
        $error = "Enter your username/email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, fullname, username, email, password FROM admins WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $identity, $identity);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin["password"])) {
                session_regenerate_id(true);
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_username"] = $admin["username"];
                $_SESSION["admin_fullname"] = $admin["fullname"];
                header("Location: admin_dashboard.php"); exit();
            }
        }
        $error = "Incorrect username/email or password.";
        $stmt->close();
    }
}
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login</title><link rel="stylesheet" href="css/style.css">
</head><body class="auth-page admin-login-page">
<main class="auth-screen"><section class="auth-form-panel admin-login-form-panel"><div class="auth-form-content">
<h1>Welcome Admin!</h1><p class="auth-subtitle">Log in to access Club Membership System.</p>
<?php if (!empty($_SESSION["admin_signup_success"])): ?><div class="form-success"><?php echo htmlspecialchars($_SESSION["admin_signup_success"]); unset($_SESSION["admin_signup_success"]); ?></div><?php endif; ?>
<?php if ($error !== ""): ?><div class="form-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="POST" action="admin_login.php" novalidate>
<div class="input-row"><span class="field-icon">●</span><input name="identity" placeholder="Email/Username:" required></div>
<div class="input-row"><span class="field-icon">♟</span><input type="password" name="password" placeholder="Password:" required></div>
<div class="login-options"><label><input type="checkbox" name="remember"><span>Remember me</span></label><span>Forgot Password?</span></div>
<button class="auth-button" type="submit">Log In</button>
</form>
<div class="member-links"><span>Not an Admin yet?</span><a href="admin_signup.php">Sign Up</a></div>
<a href="login.php" class="admin-link">Member? Log in here</a>
</div></section></main></body></html>
