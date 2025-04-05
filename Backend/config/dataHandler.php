<?php
require_once "dbaccess.php"; // Подключение к базе данных
require_once __DIR__ . "/../models/userСlass.php"; // Подключаем класс пользователя
 // Подключаем класс пользователя

class DataHandler {
    private $db;

    public function __construct() {
        $this->db = new DBAccess(); // Создаем объект для работы с БД
    }

    public function getUsers() {
        $query = "SELECT id, email FROM users"; 
        $result = $this->db->executeQuery($query);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = new User($row['id'], $row['email']);
        }
        return $users;
    }

    public function addUser($user) {
        $query = "INSERT INTO users (email, password) VALUES (?, ?)";
        $params = [$user->email, password_hash($user->password, PASSWORD_BCRYPT)];
        return $this->db->executeQuery($query, $params);
    }
}
?>
