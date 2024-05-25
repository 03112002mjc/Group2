<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note_id = $_POST['note_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE notes SET title = ?, content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $title, $content, $note_id, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?success=1");
        exit;
    } else {
        echo "Error updating note: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
