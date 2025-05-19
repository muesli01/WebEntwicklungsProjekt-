<?php
require_once __DIR__ . '/../config/dbaccess.php';

class Payment
{
    private $conn;

    public function __construct()
    {
        // Получаем чистое mysqli-соединение из твоего DBAccess
        $db = new DBAccess();
        $this->conn = $db->getConnection();
    }

    /**
     * Получить все доступные сохранённые способы оплаты для пользователя
     */
    public function getSavedMethods($userId)
    {
        $sql = "SELECT id, method, details 
                  FROM payment_methods 
                 WHERE user_id = ? 
                   AND bestellnummer = ''"; 
        // считаем, что для сохранённых методов bestellnummer = ''
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
     * Сохранить способ оплаты для конкретного заказа
     * (это и есть savePayment, убираем старую путаницу с payments)
     */
    public function savePayment($userId, $orderNumber, $method, $details = "")
    {
        $sql = "INSERT INTO payment_methods (user_id, bestellnummer, method, details) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isss", $userId, $orderNumber, $method, $details);
        return $stmt->execute();
    }
}
