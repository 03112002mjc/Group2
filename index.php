<?php
include 'config.php';
session_start();

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role);

    if ($stmt->num_rows == 1) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = $role;
            
            if ($role == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="body-container">
<div class="header-container">
    <h1>My Notebook</h1>
</div>
<div class="container login-container">
    <h3>Login to continue</h3>
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="example@mail.com" required>
        <br>
        <label for="password">Password:</label>
        <div class="pass-container">
            <input type="password" id="password" name="password" oninput="toggleEyeIcon()" placeholder="Password" required>
            <i id="eyeIcon" class="fas fa-eye" style="display: none;" onclick="togglePasswordVisibility()"></i>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
<div class="container login-container2">
    <p class="text-muted text-center"><small>Do not have an account?</small></p>
    <a href="register.php" class="btn btn-default btn-block"><small>Create an account</small></a>
</div>
<footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com">Group2</a><br> WebDev</small>
    </p>
</footer>
<script src="js/login.js"></script>
</body>
</html>
