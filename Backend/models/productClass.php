<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class Product {
    private $db;

    public function __construct() {
        $this->db = new DBAccess();
    }

    public function getAllProducts() {
        $query = "SELECT * FROM products";
        return $this->db->executeQuery($query);
    }
    public function getProductById($productId) {
        $query = "SELECT * FROM products WHERE id = ?";
        $params = [$productId];
        $result = $this->db->executeQuery($query, $params);
    
        return $result->fetch_assoc();
    }
    
}
?>
