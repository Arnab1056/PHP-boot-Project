<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userName = $_SESSION['user_name'];
$roleId = $_SESSION['role_id'];

$roleContent = [
    1 => "Welcome Admin! You can manage users and roles.",
    2 => "Welcome Editor! You can edit and manage content.",
    3 => "Welcome Contributor! You can contribute content.",
    4 => "Welcome User! You can view content."
];

$content = $roleContent[$roleId] ?? "Welcome! Your role is not recognized.";
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
        <strong>Hello, <?= htmlspecialchars($userName) ?>!</strong>
    </div>
    <p><?= htmlspecialchars($content) ?></p>
</div>
</body>
</html>
