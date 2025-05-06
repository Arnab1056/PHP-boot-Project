<?php
require 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1,2,3])) { // Only Editors and Contributors can access this page
    header("Location: login.php");
    exit;
}

$postId = intval($_GET['post_id']); // Get the post ID from the query string
$stmt = $conn->prepare("SELECT id, title, content FROM blog_posts WHERE id = ?");
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Blog post not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));

    $stmt = $conn->prepare("UPDATE blog_posts SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $postId);
    $stmt->execute();

    header("Location: blog_posts.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Blog Post</title>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Blog Post</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
</body>
</html>
