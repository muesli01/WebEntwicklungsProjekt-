<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class User {
    private $db;

    public function __construct() {
        $this->db = new DBAccess(); // Используем уже готовый класс
    }

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $params = [$username, $email, $hashedPassword];

        return $this->db->executeQuery($query, $params);
    }
    

    public function login($email, $password) {
        session_start();
        $query = "SELECT id, username, password FROM users WHERE email = ?";
        $params = [$email];
        $result = $this->db->executeQuery($query, $params);

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                return true;
            }
        }
        return false;
    }

    public function isLoggedIn() {
        session_start();
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_start();
        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
    }
}
?>
