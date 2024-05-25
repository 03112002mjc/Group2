<?php
session_start();
include 'config.php';
include 'function.php';

// Ensure only admins can access this page
checkRole('admin');

$errors = [];

// Fetch user details
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details
    $sql = "SELECT id, name, email, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    } else {
        // Redirect to users page if user does not exist
        header("Location: admin_dashboard.php");
        exit;
    }

    $stmt->close();
} else {
    // Redirect to users page if id is not provided
    header("Location: admin_dashboard.php");
    exit;
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update user
    $update_sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $name, $email, $role, $user_id);

    if ($update_stmt->execute()) {
        $success_message = "Update successfully!";
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $errors[] = "Error: " . $update_stmt->error;
    }

    $update_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <style>
        *{
            font-family: 'Source sans 3', 'sans serif';
            font-weight: 350;
        }
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
        .form-container select {
            width: 95%;
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
        .createBtn{
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
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
        <div class="main-content">
            <div class="form-container">
                <h1><i class="fas fa-user-edit"></i> Edit User</h1>
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (isset($success_message)): ?>
                    <div class="success">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $user_id; ?>" method="post">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="student" <?php echo ($user['role'] === 'student') ? 'selected' : ''; ?>>Student</option>
                        <option value="instructor" <?php echo ($user['role'] === 'instructor') ? 'selected' : ''; ?>>Instructor</option>
                        <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>

                    <button type="submit">Update User</button>
                    <div class="createBtn">
                        <a href="create_user.php" class="button">Create New User</a> <!-- Add this button -->
                        <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 | Notebook by <a href="https://github.com/Group2" target="_blank">Group2</a></p>
    </footer>
</body>
</html>
