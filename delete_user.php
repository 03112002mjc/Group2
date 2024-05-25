<?php
session_start();
include 'config.php';
include 'function.php';

// Ensure only admins can access this page
checkRole('admin');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete user
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $user_id);

    if ($delete_stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $delete_stmt->close();
    $conn->close();
} else {
    header("Location: admin_dashboard.php");
    exit;
}
?>
