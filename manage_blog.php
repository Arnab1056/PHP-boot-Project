<?php
require 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id']);
    $action = $_POST['action'];

    if ($action === 'edit') {
        // Redirect to edit blog post page
        header("Location: edit_blog.php?post_id=$postId");
        exit;
    } elseif ($action === 'delete') {
        // Delete blog post from the database
        $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        header("Location: dashboard.php");
        exit;
    }
}
?>
