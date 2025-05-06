<?php
require_once __DIR__ . '/../db_connection.php';

class RegisterController {
    public static function register($name, $email, $password, $role_id) {
        global $conn;

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role_id, is_approved) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("sssi", $name, $email, $hashedPassword, $role_id);

        if ($stmt->execute()) {
            return "Registration successful! Please wait for admin approval.";
        } else {
            return "Error: " . $stmt->error;
        }
    }
}
?>
