<?php
session_start();
include 'config.php';
include 'function.php';

// Ensure only admins can access this page
checkRole('admin');

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Error: An account with this email already exists.";
    } else {
        $sql = "INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $role, $password);

        if ($stmt->execute()) {
            $success_message = "New user created successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New User</title>
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <style>
        /* General styles for form */
        *{
            font-family: 'Source sans 3';
            font-weight: 350;
        }
form {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 5px;
    max-width: 400px;
    margin: 0 auto;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

form input, form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    box-sizing: border-box;
}

form button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

form button:hover {
    background-color: #45a049;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

a.button {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 10px;
    background-color: #ccc;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
}

a.button:hover {
    background-color: #bbb;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;

}
.message {
    
    font-weight: 400;
    margin-top: 10px;
    text-align: center;
}
.message.error{
    color: red;
}
.message.success{
    color: #4CAF50;
}
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-book"></i> Admin Dashboard
            </div>
            <div class="user-info">
                <i class="fas fa-user-circle" id="user-circle"></i>
                <div class="dropdown">
                <i class="fa-solid fa-caret-down"></i>
                    <div class="dropdown-content">
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
    <nav>
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="admin_notes.php"><i class="fas fa-sticky-note"></i> Notes</a></li>
                <li><a href="admin_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </nav>
    
    
    
    <form method="POST" action="">
    <h1>Create New User</h1>
    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="student">Student</option>
            <option value="instructor">Instructor</option>
            <option value="admin">Admin</option>
        </select><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Create User</button>
        <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
    </form>
    <br>
    </main>
    <footer>
        <p>&copy; 2024 | Notebook by <a href="https://github.com/Group2" target="_blank">Group2</a></p>
    </footer>
</body>
</html>
