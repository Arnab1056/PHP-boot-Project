<?php
require '../db_connection.php'; // Use relative path to include db_connection.php

class AdminApprovalController {
    public static function handleApproval($userId, $action) {
        global $conn;

        if ($action === 'approve') {
            $stmt = $conn->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
        } elseif ($action === 'reject') {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        } else {
            return false; // Invalid action
        }

        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    public static function getPendingUsers() {
        global $conn;

        $result = $conn->query("SELECT id, name, email, role_id FROM users WHERE is_approved = 0");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
