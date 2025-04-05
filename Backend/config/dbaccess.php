<?php
// Database configuration file

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'webshop');

class DBAccess {
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function executeQuery($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query preparation failed: " . $this->conn->error);
        }
    
        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
    
        if (!$stmt->execute()) {
            die("Query execution failed: " . $stmt->error);
        }
    
        return $stmt->get_result();
    }
    
}
?>
