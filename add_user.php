<?php
session_start();
include 'config.php';
include 'function.php';

// Ensure only admins can access this page
checkRole('admin');

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
        $errors[] = "This email is already taken. Please choose another.";
    } else {
        // Insert new user
        $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
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
    <title>Add User</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <style>
        /* Additional styles specific to add_user.php */
        .form-container {
            max-width: 400px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ccc;
        }

        .form-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            background-color: #1177D1;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }

        .form-container button:hover {
            background-color: #0f5fa4;
        }

        .error {
            color: #f00;
            margin-bottom: 10px;
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
                    <button class="dropbtn"><?php echo $_SESSION['name']; ?></button>
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
        <div class="main-content">
            <div class="form-container">
                <h1><i class="fas fa-user-plus"></i> Add User</h1>
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="student">Student</option>
                        <option value="instructor">Instructor</option>
                        <option value="admin">Admin</option>
                    </select>

                    <button type="submit">Add User</button>
                </form>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 | Notebook by <a href="https://github.com/Group2" target="_blank">Group2</a></p>
    </footer>
</body>
</html>