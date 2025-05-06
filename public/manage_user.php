<?php
require '../middleware/AuthMiddleware.php';
require '../controllers/ManageUserController.php';

AuthMiddleware::requireAuth();
AuthMiddleware::requireRole([1]); // Only Admin can access this page

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id']);
    $action = $_POST['action'];

    if ($action === 'edit') {
        header("Location: edit_user.php?user_id=$userId");
        exit;
    } elseif ($action === 'delete') {
        ManageUserController::deleteUser($userId);
        header("Location: dashboard.php");
        exit;
    }
}
?>