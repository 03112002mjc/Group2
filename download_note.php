<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['note_id'])) {
    $note_id = $_GET['note_id'];
    $user_id = $_SESSION['user_id'];

    // Fetch the note details
    $sql = "SELECT title, content FROM notes WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $note_id, $user_id);
    $stmt->execute();
    $stmt->bind_result($title, $content);
    $stmt->fetch();
    $stmt->close();

    if ($title && $content) {
        // Set headers to download the file
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $title . '.txt"');
        echo "Title: " . $title . "\n\n";
        echo "Content:\n" . $content;
    } else {
        echo "Note not found or you do not have permission to download this note.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
