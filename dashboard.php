<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'db_connection.php';

$userName = htmlspecialchars($_SESSION['user_name']); // Sanitize output
$roleId = $_SESSION['role_id'];

$roleContent = [
    1 => "Welcome Admin! You can manage users, roles, and content.",
    2 => "Welcome Editor! You can edit and manage content.",
    3 => "Welcome Contributor! You can contribute content.",
    4 => "Welcome User! You can create blog posts."
];

$content = $roleContent[$roleId] ?? "Welcome! Your role is not recognized.";

// Fetch only approved users if the role is Admin
if ($roleId == 1) {
    $result = $conn->query("SELECT id, name, email, role_id, is_approved FROM users WHERE is_approved = 1");
    $users = $result->fetch_all(MYSQLI_ASSOC);

    // Fetch all blog posts
    $blogResult = $conn->query("SELECT blog_posts.id, blog_posts.title, blog_posts.content, users.name AS author 
                                FROM blog_posts 
                                JOIN users ON blog_posts.user_id = users.id");
    $blogPosts = $blogResult->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">RBAC System</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                    <a class="nav-link" href="blog_posts.php">Post Feed</a>
                </li>
                <?php if ($roleId == 1): // Admin role ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_approval.php">Admin Approval</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php?redirect=login">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h2>Dashboard</h2>
    <div class="alert alert-info">
        <strong>Hello, <?= $userName ?>!</strong>
    </div>
    <p><?= htmlspecialchars($content) ?></p>

    <?php if ($roleId == 1): // Admin-specific content ?>
        <h3 class="mt-4">Manage Users</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role_id']) ?></td>
                        <td>
                            <form method="POST" action="manage_user.php" class="d-inline">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" name="action" value="edit" class="btn btn-warning btn-sm">Edit</button>
                            </form>
                            <form method="POST" action="manage_user.php" class="d-inline">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        

        <h3 class="mt-4">Manage Blog Posts</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blogPosts as $post): ?>
                    <tr>
                        <td><?= htmlspecialchars($post['id']) ?></td>
                        <td><?= htmlspecialchars($post['title']) ?></td>
                        <td><?= htmlspecialchars($post['content']) ?></td>
                        <td><?= htmlspecialchars($post['author']) ?></td>
                        <td>
                            <form method="POST" action="manage_blog.php" class="d-inline">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button type="submit" name="action" value="edit" class="btn btn-warning btn-sm">Edit</button>
                            </form>
                            <form method="POST" action="manage_blog.php" class="d-inline">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if ($roleId == 4): // User-specific content ?>
        <a href="blog_posts.php" class="btn btn-primary mt-4">Go to Blog Posts</a>
    <?php endif; ?>
</div>
</body>
</html>
