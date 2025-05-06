<?php
require '../db_connection.php';
require '../middleware/AuthMiddleware.php';

AuthMiddleware::requireAuth();
AuthMiddleware::requireRole([1, 2]); // Allow Admins and Editors to access this page

$userId = intval($_GET['user_id'] ?? 0);

if (!$userId) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, name, email, role_id, is_approved FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = strtolower(trim($_POST['email']));
    $roleId = intval($_POST['role_id']);
    $isApproved = isset($_POST['is_approved']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role_id = ?, is_approved = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $name, $email, $roleId, $isApproved, $userId);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit User</title>
</head>
<body>
<div class="container mt-5">
    <h2>Edit User</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select class="form-control" id="role_id" name="role_id" required>
                <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Editor</option>
                <option value="3" <?= $user['role_id'] == 3 ? 'selected' : '' ?>>Contributor</option>
                <option value="4" <?= $user['role_id'] == 4 ? 'selected' : '' ?>>User</option>
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="is_approved" name="is_approved" <?= $user['is_approved'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_approved">Approved</label>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
</body>
</html>