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
    
    
}
