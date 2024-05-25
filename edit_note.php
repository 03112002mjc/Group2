<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
} else {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Note</title>
    <link rel="stylesheet" href="css/edit_note.css">
</head>
<body>
<div class="edit-note-container">
    <h1>Edit Note</h1>
    <form method="post" action="update_note.php">
        <input type="hidden" name="note_id" value="<?php echo $note_id; ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        <br>
        <label for="content">Content:</label>
        <textarea id="content" name="content" required><?php echo htmlspecialchars($content); ?></textarea>
        <br>
        <button type="submit">Update Note</button>
    </form>
</div>
</body>
</html>
