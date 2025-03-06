<?php
session_start();
include "db.php";

if (isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit();
}

$tiposErrores = [
    "empty_fields" => "All fields are required.",
    "user_already_registered" => "This username is already registered.",
    "registration_error" => "Error in registration, please try again.",
    "successful_registration" => "Registration successful. You can now log in.",
    "incorrect_username_or_password" => "Incorrect username or password."
];

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $name = trim($_POST['name']);
        $password = trim($_POST['password']);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        if (empty($name) || empty($password)) {
            $error = "empty_fields";
        } else {
            $sql = "SELECT * FROM users WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "user_already_registered";
            } else {
                $sql = "INSERT INTO users (name, password) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ss', $name, $passwordHash);
                if ($stmt->execute()) {
                    $error = "successful_registration";
                } else {
                    $error = "registration_error";
                }
            }
        }
    }

    if (isset($_POST['login'])) {
        $name = trim($_POST['name']);
        $password = trim($_POST['password']);
        if (empty($name) || empty($password)) {
            $error = "empty_fields";
        } else {
            $sql = "SELECT * FROM users WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                $error = "incorrect_username_or_password";
            } else {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['userId'] = $user['id'];
                    $_SESSION['userName'] = $user['name'];
                    $_SESSION['admin'] = $user['admin'];
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "incorrect_username_or_password";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="authstyles.css">
    <script>
        window.onload = function() {
            var errorElement = document.getElementById("error-message");
            if (errorElement) {
                var errorClass = errorElement.classList[1];
                if (errorClass.includes('successful_registration')) {
                    errorElement.style.color = 'lime';
                }
            }
        };
    </script>
</head>
<body>
    <div class="auth-container">
        <?php if ($error): ?>
            <p class="error <?= "error-$error" ?>" id="error-message"><?= htmlspecialchars($tiposErrores[$error]) ?></p>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" class="auth-form">
                <h3>Register</h3>
                <input type="text" maxlength="25" name="name" placeholder="Username" required />
                <input type="password" maxlength="25" name="password" placeholder="Password" required />
                <button type="submit" name="register">Register</button>
            </form>

            <div class="divisor"></div>
            <form method="POST" class="auth-form">
                <h3>Login</h3>
                <input type="text" maxlength="25" name="name" placeholder="Username" required />
                <input type="password" maxlength="25" name="password" placeholder="Password" required />
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
