<?php
require_once '../db_connection.php';

class ManageUserController {
    public static function deleteUser($userId) {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }
}
