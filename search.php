<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "notebook";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$output = '';
if(isset($_POST['query'])){
    $search = $_POST['query'];
    $sql = "SELECT titlr FROM notes WHERE title LIKE '%$search%'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $output .= '<div>'.$row['title'].'</div>';
        }
    } else {
        $output .= '<div>No results found</div>';
    }
}

echo $output;
$conn->close();
?>