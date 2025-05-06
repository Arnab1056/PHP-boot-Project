<?php
session_start();

class AuthMiddleware {
    public static function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
            exit;
        }
    }

    public static function requireRole($roles) {
        if (!isset($_SESSION['role_id']) || !in_array($_SESSION['role_id'], $roles)) {
            header("Location: ../index.php");
            exit;
        }
    }
}
