<?php
session_start();
include 'config.php';
include 'function.php';

// Ensure only admins can access this page
checkRole('admin');

// Fetch roles for the dropdown
$sql_roles = "SELECT DISTINCT role FROM users";
$result_roles = $conn->query($sql_roles);

// Fetch selected role from the dropdown
$selected_role = isset($_GET['role']) ? $_GET['role'] : '';

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the admin's name
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name);
$stmt->fetch();
$stmt->close();

// Fetch users based on the selected role
if ($selected_role) {
    $sql_users = "SELECT id, name, email, role FROM users WHERE role = ?";
    $stmt_users = $conn->prepare($sql_users);
    $stmt_users->bind_param("s", $selected_role);
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();
} else {
    $sql_users = "SELECT id, name, email, role FROM users";
    $result_users = $conn->query($sql_users);
}

// Fetch all notes for the admin to manage
$sql_notes = "SELECT id, title, content, user_id, created_at FROM notes";
$result_notes = $conn->query($sql_notes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-book"></i> Admin Dashboard
            </div>
            <div class="user-info">
                <i class="fas fa-user-circle" id="user-circle"></i>
                <span class="username"><?php echo htmlspecialchars($name); ?></span>
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
        <nav class="side-nav">
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="admin_notes.php"><i class="fas fa-sticky-note"></i> Notes</a></li>
                <li><a href="admin_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <div class="top-container">
            <h1><i class="fas fa-users"></i> Manage Users</h1>
    <form method="GET" action="">
        <label for="role">Filter by Role:</label>
        <select name="role" id="role" onchange="this.form.submit()">
            <option value="">All Roles</option>
            <?php while ($row_role = $result_roles->fetch_assoc()): ?>
                <option value="<?php echo $row_role['role']; ?>" <?php if ($row_role['role'] == $selected_role) echo 'selected'; ?>>
                    <?php echo ucfirst($row_role['role']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row_user = $result_users->fetch_assoc()): ?>
            <tr class="row-selector">
                <td><?php echo $row_user['id']; ?></td>
                <td><?php echo $row_user['name']; ?></td>
                <td><?php echo $row_user['email']; ?></td>
                <td><?php echo $row_user['role']; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $row_user['id']; ?>">Edit</a>
                    <a href="delete_user.php?id=<?php echo $row_user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    </div>
    <div class="manage_notes">
    <h2>Manage Notes</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>User ID</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row_notes = $result_notes->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row_notes['id']; ?></td>
                <td><?php echo $row_notes['title']; ?></td>
                <td><?php echo $row_notes['content']; ?></td>
                <td><?php echo $row_notes['user_id']; ?></td>
                <td><?php echo $row_notes['created_at']; ?></td>
                <td>
                    <a href="view_note.php?id=<?php echo $row_notes['id']; ?>">View</a>
                    <a href="edit_note.php?id=<?php echo $row_notes['id']; ?>">Edit</a>
                    <a href="delete_note.php?id=<?php echo $row_notes['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    </div>
        </div>
        
    </main>
    <footer>
        <p>&copy; 2024 | Notebook by <a href="https://github.com/Group2" target="_blank">Group2</a></p>
    </footer>
</body>
</html>

<?php
if (isset($stmt_users)) {
    $stmt_users->close();
}
$conn->close();
?>
