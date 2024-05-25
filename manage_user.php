<?php

session_start();
include 'config.php';
include 'function.php';
checkRole('admin');

// Fetch all users
$sql = "SELECT id, email, name, role FROM users";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <h1>Manage Users</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Name</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
