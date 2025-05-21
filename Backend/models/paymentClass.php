<?php
require_once __DIR__ . '/../config/dbaccess.php';

class Payment
{
    private $conn;

    public function __construct()
    {
        // Hole die reine mysqli-Verbindung aus DBAccess
        $db = new DBAccess();
        $this->conn = $db->getConnection();
    }

    /**
     * Gibt alle gespeicherten Zahlungsmethoden eines Benutzers zurück
     * (Nur solche, die nicht mit einer konkreten Bestellung verknüpft sind)
     */
    public function getSavedMethods($userId)
    {
        $sql = "SELECT id, method, details 
                  FROM payment_methods 
                 WHERE user_id = ? 
                   AND bestellnummer = ''"; 
        // Gespeicherte Zahlungsmethoden haben eine leere bestellnummer
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();

        $methods = [];
        while ($row = $res->fetch_assoc()) {
            $methods[] = [
                'id'      => $row['id'],
                'name'    => $row['method'],
                'details' => $row['details']
            ];
        }
        return $methods;
    }

    /**
     * Speichert eine Zahlungsmethode für eine bestimmte Bestellung
     */
    public function savePayment($userId, $orderNumber, $method, $details = "")
    {
        $sql = "INSERT INTO payment_methods (user_id, bestellnummer, method, details) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isss", $userId, $orderNumber, $method, $details);
        return $stmt->execute();
    }

    /**
     * Fügt eine neue gespeicherte Zahlungsmethode für den Benutzer hinzu
     */
    public function addMethod(int $userId, string $name, string $details = ""): bool
    {
        $sql = "INSERT INTO payment_methods (user_id, bestellnummer, method, details)
                VALUES (?, '', ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $userId, $name, $details);
        return $stmt->execute();
    }
}
