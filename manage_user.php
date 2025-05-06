<?php
require 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id']);
    $action = $_POST['action'];

    if ($action === 'edit') {
        // Redirect to edit user page
        header("Location: edit_user.php?user_id=$userId");
        exit;
    } elseif ($action === 'delete') {
        // Delete user from the database
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        header("Location: dashboard.php");
        exit;
    }
}
?>
