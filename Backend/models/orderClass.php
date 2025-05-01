<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class Order {
    private $db;

    public function __construct() {
        $this->db = new DBAccess();
    }

    public function createOrder($userId, $bestellnummer, $gesamtpreis) {
        $query = "INSERT INTO orders (user_id, bestellnummer, gesamtpreis) VALUES (?, ?, ?)";
        $params = [$userId, $bestellnummer, $gesamtpreis];

        return $this->db->executeQuery($query, $params);
    }
    public function getOrdersByUser($userId) {
        $query = "SELECT bestellnummer, bestelldatum, gesamtpreis, status FROM orders WHERE user_id = ? ORDER BY bestelldatum DESC";
        $params = [$userId];
        $result = $this->db->executeQuery($query, $params);
    
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    
        return $orders;
    }
    public function getOrderByBestellnummer($bestellnummer) {
        $query = "SELECT * FROM orders WHERE bestellnummer = ?";
        $params = [$bestellnummer];
        $result = $this->db->executeQuery($query, $params);
    
        return $result->fetch_assoc();
    }
    public function createOrderWithItems($userId, $bestellnummer, $gesamtpreis, $items) {
        // Bestellung speichern
        $query = "INSERT INTO orders (user_id, bestellnummer, gesamtpreis) VALUES (?, ?, ?)";
        $params = [$userId, $bestellnummer, $gesamtpreis];
        $result = $this->db->executeQuery($query, $params);
    
        if (!$result) {
            return false;
        }
    
        // Holen der neuen Order-ID
        $orderId = $this->db->getLastInsertId();
    
        // Order Items speichern
        foreach ($items as $item) {
            $queryItem = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $paramsItem = [$orderId, $item["product_id"], $item["quantity"], $item["price"]];
            $this->db->executeQuery($queryItem, $paramsItem);
        }
    
        return $orderId;
    }
    public function getOrderItems($orderId) {
        $query = "SELECT product_id, quantity, price FROM order_items WHERE order_id = ?";
        $params = [$orderId];
        $result = $this->db->executeQuery($query, $params);
    
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }
    
    
    
    
}
