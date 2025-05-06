<?php
require __DIR__ . '/../db_connection.php'; 

class RegisterController {
    public static function register($name, $email, $password, $role_id) {
        global $conn;

        $email = strtolower($email);

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $email, $hashedPassword, $role_id);

        if ($stmt->execute()) {
            return "Registration successful! Wait for admin approval.";
        } else {
            return "Error: " . $stmt->error;
        }
    }
}
?>
