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
        $this->conn->set_charset("utf8mb4"); // Optional: Zeichenkodierung setzen
    }

    private function getParamTypes($params)
    {
        $types = '';
        foreach ($params as $param) {
            if (is_null($param)) {
                $types .= 's'; // Behandle NULL als string (funktioniert sicherer in MySQL)
            } elseif (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b'; // blob oder sonstige Typen
            }
        }
        return $types;
    }

    public function executeQuery($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Query prepare failed: " . $this->conn->error . " | SQL: $query");
            return false;
        }

        if (!empty($params)) {
            $types = $this->getParamTypes($params);
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            error_log("Query execute failed: " . $stmt->error . " | SQL: $query | Params: " . json_encode($params));
            return false;
        }

        if (stripos(trim($query), 'SELECT') === 0) {
            return $stmt->get_result(); // Kann false zurückgeben bei Fehler
        }

        return $stmt->affected_rows; // z. B. für INSERT/UPDATE/DELETE nützlich
    }

    public function getLastInsertId()
    {
        return $this->conn->insert_id;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    // Optional: Transaktionssupport
    public function beginTransaction()
    {
        return $this->conn->begin_transaction();
    }

    public function commit()
    {
        return $this->conn->commit();
    }

    public function rollback()
    {
        return $this->conn->rollback();
    }

    // Schließen (optional, da PHP dies beim Skriptende macht)
    public function close()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
