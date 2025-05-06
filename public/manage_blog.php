<?php
require '../db_connection.php';
require '../middleware/AuthMiddleware.php';

AuthMiddleware::requireAuth();
AuthMiddleware::requireRole([1, 2, 3]); // Admin, Editor, and Contributor can access this page

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id']);
    $action = $_POST['action'];

    if ($action === 'edit') {
        header("Location: edit_blog.php?post_id=$postId");
        exit;
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        header("Location: dashboard.php");
        exit;
    }
}
?>