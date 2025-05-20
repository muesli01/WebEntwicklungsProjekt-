<?php
// Database configuration file

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'webshop');

class DBAccess
{
    private $conn;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function getParamTypes($params)
    {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b'; // blob или null
            }
        }
        return $types;
    }

    public function executeQuery($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Query prepare failed: " . $this->conn->error);
            return false;
        }

        if (!empty($params)) {
            $types = $this->getParamTypes($params);
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            error_log("Query execute failed: " . $stmt->error);
            return false;
        }

        if (stripos(trim($query), 'SELECT') === 0) {
            return $stmt->get_result(); // может вернуть false, если ошибка в fetch
        }

        return true;
    }

    public function getLastInsertId()
    {
        return $this->conn->insert_id;
    }

    public function getConnection()
    {
        return $this->conn;
    }





}
?>