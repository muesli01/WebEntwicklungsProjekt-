<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = new DBAccess();
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM products";
        return $this->db->executeQuery($query);
    }
    // Neues Produkt erstellen
    public function createProduct($name, $description, $price, $image) {
        $query = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        $params = [$name, $description, $price, $image];
        return $this->execute($query, $params);
    }
    
    

    // Produkt entfernen
    public function deleteProduct($id)
    {
        $query = "DELETE FROM products WHERE id = ?";
        $params = [$id];
        return $this->execute($query, $params);
    }

    // Holen Sie sich alle Produkte (als Array)
    public function getAllProductsArray()
    {
        $query = "SELECT * FROM products";
        $result = $this->execute($query);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    // Hilfsfunktion für Abfragen
    private function execute($query, $params = [])
    {
        $db = new DBAccess();
        $stmt = $db->executeQuery($query, $params);
        return $stmt;
    }
    public function getProductById($productId) {
        $query = "SELECT * FROM products WHERE id = ?";
        $params = [$productId];
        $result = $this->db->executeQuery($query, $params);
    
        return $result->fetch_assoc();
    }
    public function updateProduct($id, $name, $description, $price, $image = "") {
        if ($image !== "") {
            $query = "UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
            $params = [$name, $description, $price, $image, $id];
        } else {
            $query = "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?";
            $params = [$name, $description, $price, $id];
        }
        return $this->execute($query, $params);
    }
    
    

}
?>