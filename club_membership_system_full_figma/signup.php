<?php
session_start();
require_once "config.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];
$fullname = "";
$email = "";
$club = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $club = trim($_POST["club"] ?? "");

    if ($fullname === "") {
        $errors[] = "Full Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s.'-]+$/", $fullname)) {
        $errors[] = "Full Name must contain letters only.";
    }

    if ($email === "") {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($password === "") {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if ($club !== "" && !in_array($club, ["Art", "Theater / Acting", "Music"], true)) {
        $errors[] = "Please select a valid club.";
    }

    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "An account with this email already exists.";
        }
        $check->close();
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (fullname, email, password, club)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $fullname, $email, $hashed_password, $club);

        if ($stmt->execute()) {
            $_SESSION["success"] = "Account created successfully. You can now log in.";
            $stmt->close();
            header("Location: login.php");
            exit();
        }

        $errors[] = "Registration failed. Please try again.";
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page - Club Membership System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page signup-page">

    <main class="auth-screen">
        <section class="auth-form-panel signup-form-panel">
            <div class="auth-form-content">

                <h1>Sign Up</h1>

                <?php if (!empty($errors)): ?>
                    <div class="form-error">
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="signup.php" novalidate>

                    <div class="input-row">
                        <span class="field-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="3.5"></circle>
                                <path d="M5 20c.8-3.3 3.2-5 7-5s6.2 1.7 7 5"></path>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="fullname"
                            placeholder="Full Name:"
                            value="<?php echo htmlspecialchars($fullname); ?>"
                            autocomplete="name"
                            required
                        >
                    </div>

                    <div class="input-row">
                        <span class="field-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <rect x="3.5" y="5.5" width="17" height="13" rx="2"></rect>
                                <path d="m5 7 7 5 7-5"></path>
                            </svg>
                        </span>
                        <input
                            type="email"
                            name="email"
                            placeholder="Email:"
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
                            autocomplete="new-password"
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

                    <div class="input-row select-row">
                        <span class="field-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <circle cx="8" cy="8" r="3"></circle>
                                <circle cx="16.5" cy="9" r="2.5"></circle>
                                <path d="M2.5 19c.6-3.2 2.5-5 5.5-5s4.9 1.8 5.5 5"></path>
                                <path d="M14 15c2.4.1 4 1.4 4.6 4"></path>
                            </svg>
                        </span>
                        <select name="club">
                            <option value="">Select Your Clubs (Optional)</option>
                            <option value="Art" <?php echo $club === "Art" ? "selected" : ""; ?>>Art</option>
                            <option value="Theater / Acting" <?php echo $club === "Theater / Acting" ? "selected" : ""; ?>>Theater / Acting</option>
                            <option value="Music" <?php echo $club === "Music" ? "selected" : ""; ?>>Music</option>
                        </select>
                    </div>

                    <button type="submit" class="auth-button">Sign Up</button>
                </form>

                <div class="member-links signup-link">
                    <span>Already have an account?</span>
                    <a href="login.php">Log In</a>
                </div>

            </div>
        </section>
    </main>

</body>
</html>
