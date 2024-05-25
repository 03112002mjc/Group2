<?php
include 'config.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'] ?? 'student';

    // Check if the email already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $errors[] = "This account is already exist. Please create new!";
    } else {
        // Insert new user
        $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
<div class="register-container">
    <h1>Register</h1>
    <div class="error-container">
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<p class="error-message">' . $error . '</p>';
            }
        }
        ?>
    </div>
    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <div class="pass-container">
            <input type="password" id="password" name="password" oninput="toggleEyeIcon()" placeholder="Password" required>
            <i id="eyeIcon" class="fas fa-eye" style="display: none;" onclick="togglePasswordVisibility()"></i>
        </div>
        <br>
        <div class="role-container">
            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="student">Student</option>
                <option value="instructor">Instructor</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <br>
        <button type="submit">Register</button>
    </form>
</div>
<div class="register-container2">
    <p class="text-muted text-center"><small>Already have an account?</small></p>
    <a href="index.php" class="btn btn-default btn-block">Login</a>
</div>
<footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="github.com"><small>Group2</small></a><br> WebDev</small>
    </p>
</footer>
<script src="js/register.js"></script>
</body>
</html>
