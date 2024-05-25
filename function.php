<?php
function checkRole($role) {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
        header("Location: index.php");
        exit;
    }
}
?>
