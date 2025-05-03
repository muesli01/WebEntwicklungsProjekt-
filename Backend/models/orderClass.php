<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = new DBAccess();
    }

    public function createOrder($userId, $bestellnummer, $gesamtpreis)
    {
        $query = "INSERT INTO orders (user_id, bestellnummer, gesamtpreis) VALUES (?, ?, ?)";
        $params = [$userId, $bestellnummer, $gesamtpreis];

        return $this->db->executeQuery($query, $params);
    }
    public function getOrdersByUser($userId)
    {
        $query = "SELECT bestellnummer, bestelldatum, gesamtpreis, status FROM orders WHERE user_id = ? ORDER BY bestelldatum DESC";
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
    public function getOrderByBestellnummer($bestellnummer)
    {
        $query = "SELECT * FROM orders WHERE bestellnummer = ?";
        $params = [$bestellnummer];
        $result = $this->db->executeQuery($query, $params);

        return $result->fetch_assoc();
    }
    public function createOrderWithItems($userId, $bestellnummer, $gesamtpreis, $items)
    {
        // Открыть транзакцию
        $this->db->getConnection()->begin_transaction();

        try {
            // 1. Создать заказ
            $query = "INSERT INTO orders (user_id, bestellnummer, gesamtpreis) VALUES (?, ?, ?)";
            $params = [$userId, $bestellnummer, $gesamtpreis];
            $this->db->executeQuery($query, $params);

            // 2. Получить ID созданного заказа
            $orderId = $this->db->getConnection()->insert_id;

            // 3. Вставить товары
            $queryItem = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->getConnection()->prepare($queryItem);

            foreach ($items as $item) {
                // $item должен быть массивом: ['product_id' => ..., 'quantity' => ..., 'price' => ...]
                $stmt->bind_param('iiid', $orderId, $item['product_id'], $item['quantity'], $item['price']);
                $stmt->execute();
            }

            // 4. Подтвердить транзакцию
            $this->db->getConnection()->commit();
            return true;
        } catch (Exception $e) {
            // В случае ошибки откатить транзакцию
            $this->db->getConnection()->rollback();
            return false;
        }
    }

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
    // Alle Bestellungen eines Users holen
    // Alle Bestellungen eines Users holen
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
    // Bestellung anhand der ID holen
    public function getOrderById($orderId)
    {
        $query = "SELECT * FROM orders WHERE id = ?";
        $params = [$orderId];
        $result = $this->db->executeQuery($query, $params);

        return $result ? $result->fetch_assoc() : null;
    }
    // ändern
    public function updateOrderStatus($orderId, $status)
    {
        $query = "UPDATE orders SET status = ? WHERE id = ?";
        $params = [$status, $orderId];
        return $this->db->executeQuery($query, $params);
    }

    // Erhalten Sie eine Bestellung mit Waren 
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
        error_log("Fehler bei getOrderWithItems - Query failed. Order ID: $orderId");
        error_log("MySQL Fehler: " . $this->db->getConnection()->error);
        return [];
    }

    $orderDetails = [];
    while ($row = $result->fetch_assoc()) {
        $orderDetails[] = $row;
    }

    return $orderDetails;
}

    




    public function deleteOrderItem($itemId)
    {
        $query = "DELETE FROM order_items WHERE id = ?";
        $params = [$itemId];
        return $this->db->executeQuery($query, $params);
    }









}
