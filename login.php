<?php
session_start();
require 'db_conn.php'; // Now handles errors internally


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; 

    if (isset($_POST['register'])) {
        // Registration logic
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p style='color:red;'>Invalid email format.</p>";
        } elseif (strlen($password) < 6) {
            echo "<p style='color:red;'>Password must be at least 6 characters.</p>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
            $stmt->bind_param('ss', $email, $hashedPassword);
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Registration successful!</p>";
            } else {
                echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
    } elseif (isset($_POST['login'])) {
        // Login logic
        $stmt = $conn->prepare('SELECT id, email, password FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email']
            ];
            header('Location: home.php');
            exit;
        } else {
            echo "<p style='color:red;'>Invalid email or password.</p>";
        }
        $stmt->close();
    } elseif (isset($_POST['forgot_password'])) {
        // Forgot password logic
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Generate a reset token
            $reset_token = bin2hex(random_bytes(16));
            $stmt = $conn->prepare('UPDATE users SET reset_token = ? WHERE email = ?');
            $stmt->bind_param('ss', $reset_token, $email);
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Reset link sent to email!</p>";
                // TODO: Send email with $reset_token link
            }
        } else {
            echo "<p style='color:red;'>No user found with that email.</p>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 0; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 300px; }
        h2 { margin-bottom: 20px; text-align: center; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #5cb85c; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #4cae4c; }
        a { display: block; text-align: center; margin-top: 10px; text-decoration: none; color: #5cb85c; }
    </style>
</head>
<body>
<div class="container">
    <?php if (!isset($_GET['action']) || $_GET['action'] === 'login'): ?>
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
            <a href="?action=register">Don't have an account? Register</a>
            <a href="?action=forgot_password">Forgot Password?</a>
        </form>
    <?php elseif ($_GET['action'] === 'register'): ?>
        <h2>Register</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
            <a href="?action=login">Already have an account? Login</a>
        </form>
    <?php elseif ($_GET['action'] === 'forgot_password'): ?>
        <h2>Forgot Password</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" name="forgot_password">Submit</button>
            <a href="?action=login">Back to Login</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
