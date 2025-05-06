<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'db_connection.php';

$userName = htmlspecialchars($_SESSION['user_name']); // Sanitize output
$roleId = $_SESSION['role_id'];
$userId = $_SESSION['user_id'];

// Handle blog post deletion for Admins and Contributors
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && in_array($roleId, [1, 3])) {
    $postId = intval($_POST['post_id']);
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $successMessage = "Blog post deleted successfully!";
}

// Handle blog post submission for Admins, Users, and Contributors
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add' && in_array($roleId, [1, 3, 4])) {
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));
    $stmt = $conn->prepare("INSERT INTO blog_posts (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $title, $content);
    $stmt->execute();
    $successMessage = "Blog post created successfully!";
}

// Fetch all blog posts
$blogResult = $conn->query("SELECT blog_posts.id, blog_posts.title, blog_posts.content, users.name AS author, blog_posts.created_at 
                            FROM blog_posts 
                            JOIN users ON blog_posts.user_id = users.id 
                            ORDER BY blog_posts.created_at DESC");
$blogPosts = $blogResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Blog Posts</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">RBAC System</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php?redirect=index">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h2>Blog Posts</h2>

    <?php if (in_array($roleId, [1, 3,4])): // Admin and User-specific content ?>
        <h3 class="mt-4">Create a Blog Post</h3>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
    <?php endif; ?>

    <h3 class="mt-5">All Blog Posts</h3>
    <div class="row">
        <?php foreach ($blogPosts as $post): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <p class="text-muted">By <?= htmlspecialchars($post['author']) ?> on <?= htmlspecialchars($post['created_at']) ?></p>
                        <?php if (in_array($roleId, [1,3, 2])): // Admin and Editor-specific content ?>
                            <a href="edit_blog.php?post_id=<?= $post['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php endif; ?>
                        <?php if ($roleId == 1): // Admin-specific content ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
