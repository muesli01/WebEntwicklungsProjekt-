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
}
?>
