<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];
$content = $_POST['content'];
$file_path = '';

// Handle file upload
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $uploadFileDir = './uploads/';
    $dest_path = $uploadFileDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $file_path = $dest_path;
    }
}

// Insert note with file path
$sql = "INSERT INTO notes (user_id, title, content, file_path) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $user_id, $title, $content, $file_path);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: dashboard.php");
exit;
?>
