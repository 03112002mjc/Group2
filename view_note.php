<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note_id'])) {
    $note_id = $_POST['note_id'];
    $user_id = $_SESSION['user_id'];

    // Fetch the note details
    $sql = "SELECT title, content FROM notes WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $note_id, $user_id);
    $stmt->execute();
    $stmt->bind_result($title, $content);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    echo json_encode(['title' => $title, 'content' => $content]);
}
?>