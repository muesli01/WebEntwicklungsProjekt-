<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = new DBAccess();
    }

    // Erstellt eine neue Bestellung
    public function createOrder($userId, $bestellnummer, $gesamtpreis)
    {
        $query = "INSERT INTO orders (user_id, bestellnummer, gesamtpreis) VALUES (?, ?, ?)";
        $params = [$userId, $bestellnummer, $gesamtpreis];

        return $this->db->executeQuery($query, $params);
    }

    // Holt alle Bestellungen eines Benutzers
    public function getOrdersByUser($userId)
    {
        $query = "SELECT bestellnummer, bestelldatum, gesamtpreis, status FROM orders WHERE user_id = ? ORDER BY bestelldatum DESC";
        $params = [$userId];
        $result = $this->db->executeQuery($query, $params);
        $orders = [];
        if ($result === false) {
            error_log("Database query failed: " . $this->db->getConnection()->error);
            return [];
        }

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        return $orders;
    }

    // Holt eine Bestellung anhand der Bestellnummer
    public function getOrderByBestellnummer($bestellnummer)
    {
        $query = "SELECT * FROM orders WHERE bestellnummer = ?";
        $params = [$bestellnummer];
        $result = $this->db->executeQuery($query, $params);

        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }

    // Erstellt eine Bestellung mit Artikeln und Coupon (Transaktion)
    public function createOrderWithItems($userId, $bestellnummer, $gesamtpreis, $items, $couponId)
    {
        $this->db->getConnection()->begin_transaction();

        try {
            // 1. Bestellung anlegen (inkl. Coupon)
            $query = "INSERT INTO orders (user_id, bestellnummer, gesamtpreis, coupon_id) VALUES (?, ?, ?, ?)";
            $params = [$userId, $bestellnummer, $gesamtpreis, $couponId];
            $this->db->executeQuery($query, $params);

            // 2. ID der neu erstellten Bestellung erhalten
            $orderId = $this->db->getConnection()->insert_id;

            // 3. Artikel zur Bestellung hinzufügen
            $queryItem = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->getConnection()->prepare($queryItem);

            foreach ($items as $item) {
                $stmt->bind_param('iiid', $orderId, $item['product_id'], $item['quantity'], $item['price']);
                $stmt->execute();
            }

            // 4. Transaktion abschließen
            $this->db->getConnection()->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->getConnection()->rollback();
            error_log("Fehler bei createOrderWithItems: " . $e->getMessage());
            return false;
        }
    }

    // Holt Artikel einer bestimmten Bestellung
    public function getOrderItems($orderId)
    {
        $query = "SELECT product_id, quantity, price FROM order_items WHERE order_id = ?";
        $params = [$orderId];
        $result = $this->db->executeQuery($query, $params);

        $orders = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        return $orders;
    }

    // Holt alle Bestellungen eines bestimmten Benutzers
    public function getOrdersByUserId($userId)
    {
        $query = "SELECT id, bestelldatum, gesamtpreis, status FROM orders WHERE user_id = ?";
        $params = [$userId];
        $result = $this->db->executeQuery($query, $params);

        $orders = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }

        return $orders;
    }

    // Holt eine Bestellung anhand der ID
    public function getOrderById($orderId)
    {
        $query = "SELECT * FROM orders WHERE id = ?";
        $params = [$orderId];
        $result = $this->db->executeQuery($query, $params);

        return $result ? $result->fetch_assoc() : null;
    }

    // Aktualisiert den Status einer Bestellung
    public function updateOrderStatus($orderId, $status)
    {
        $query = "UPDATE orders SET status = ? WHERE id = ?";
        $params = [$status, $orderId];
        return $this->db->executeQuery($query, $params);
    }

    // Holt eine Bestellung zusammen mit den zugehörigen Artikeln
    public function getOrderWithItems($orderId)
    {
        $query = "
        SELECT 
            o.id AS order_id, 
            o.bestelldatum, 
            o.status, 
            o.gesamtpreis,
            oi.id AS item_id, 
            oi.product_id, 
            oi.quantity, 
            oi.price
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE o.id = ?
    ";

        $result = $this->db->executeQuery($query, [$orderId]);

        if ($result === false) {
            error_log("Fehler bei getOrderWithItems - Query fehlgeschlagen. Order ID: $orderId");
            error_log("MySQL Fehler: " . $this->db->getConnection()->error);
            return [];
        }

        $orderDetails = [];
        if ($result && $result instanceof mysqli_result) {
            while ($row = $result->fetch_assoc()) {
                $orderDetails[] = $row;
            }
        }

        return $orderDetails;
    }

    // Löscht einen einzelnen Artikel aus einer Bestellung und aktualisiert den Gesamtpreis
    public function deleteOrderItem($itemId)
    {
        // Zuerst order_id des Artikels ermitteln
        $query = "SELECT order_id FROM order_items WHERE id = ?";
        $params = [$itemId];
        $result = $this->db->executeQuery($query, $params);

        if (!$result || $result->num_rows === 0) {
            return false;
        }

        $row = $result->fetch_assoc();
        if (!$row) {
            return false;
        }

        $orderId = $row['order_id'];

        // Artikel löschen
        $queryDelete = "DELETE FROM order_items WHERE id = ?";
        $this->db->executeQuery($queryDelete, [$itemId]);

        // Neuen Gesamtpreis berechnen
        $querySum = "SELECT SUM(price * quantity) AS total FROM order_items WHERE order_id = ?";
        $resultSum = $this->db->executeQuery($querySum, [$orderId]);
        $newTotal = 0;

        if ($resultSum && $sumRow = $resultSum->fetch_assoc()) {
            $newTotal = $sumRow['total'];
        }

        // Gesamtpreis in der orders-Tabelle aktualisieren
        $queryUpdate = "UPDATE orders SET gesamtpreis = ? WHERE id = ?";
        $this->db->executeQuery($queryUpdate, [$newTotal, $orderId]);

        return true;
    }

    // Holt Coupon-Informationen zu einer Bestellung
    public function getCouponByOrderId($orderId)
    {
        $query = "
            SELECT c.code, c.wert
            FROM orders o
            JOIN coupons c ON o.coupon_id = c.id
            WHERE o.id = ?
        ";
        $result = $this->db->executeQuery($query, [$orderId]);

        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }
}
