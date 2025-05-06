<?php
require_once '../db_connection.php';

class BlogController {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function fetchAllPosts() {
        $query = "SELECT blog_posts.id, blog_posts.title, blog_posts.content, users.name AS author, blog_posts.created_at 
                  FROM blog_posts 
                  JOIN users ON blog_posts.user_id = users.id 
                  ORDER BY blog_posts.created_at DESC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addPost($userId, $title, $content) {
        $stmt = $this->conn->prepare("INSERT INTO blog_posts (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $title, $content);
        return $stmt->execute();
    }

    public function deletePost($postId) {
        $stmt = $this->conn->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->bind_param("i", $postId);
        return $stmt->execute();
    }
}
