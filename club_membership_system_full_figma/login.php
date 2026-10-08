<?php
session_start();
require_once "config.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "") {
        $error = "Email is required.";
    } elseif ($password === "") {
        $error = "Password is required.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id, fullname, email, password, club
             FROM users
             WHERE email = ?
             LIMIT 1"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["fullname"] = $user["fullname"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["club"] = $user["club"];

                header("Location: dashboard.php");
                exit();
            }
        }

        $error = "Incorrect email or password.";
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - Club Membership System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page login-page">

    <main class="auth-screen">
        <section class="auth-form-panel login-form-panel">
            <div class="auth-form-content">

                <h1>Welcome Back!</h1>
                <p class="auth-subtitle">Log in to access Club Membership System.</p>

                <?php if ($error !== ""): ?>
                    <div class="form-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php" novalidate>

                    <div class="input-row">
                        <span class="field-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="3.5"></circle>
                                <path d="M5 20c.8-3.3 3.2-5 7-5s6.2 1.7 7 5"></path>
                            </svg>
                        </span>
                        <input
                            type="email"
                            name="email"
                            placeholder="E-mail/Username:"
                            value="<?php echo htmlspecialchars($email); ?>"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="input-row">
                        <span class="field-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                                <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                            </svg>
                        </span>
                        <input
                            type="password"
                            name="password"
                            placeholder="Password:"
                            autocomplete="current-password"
                            required
                        >
                        <span class="password-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 12s3.2-5 9-5 9 5 9 5-3.2 5-9 5-9-5-9-5Z"></path>
                                <circle cx="12" cy="12" r="2"></circle>
                                <path d="M4 4l16 16"></path>
                            </svg>
                        </span>
                    </div>

                    <div class="login-options">
                        <label>
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <span>Forgot Password?</span>
                    </div>

                    <button type="submit" class="auth-button">Log In</button>
                </form>

                <div class="member-links">
                    <span>Not a member yet?</span>
                    <a href="signup.php">Sign Up</a>
                </div>

                <a href="admin_login.php" class="admin-link">Admin? Login here</a>

            </div>
        </section>
    </main>

</body>
</html>
