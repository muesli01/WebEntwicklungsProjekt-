<?php
session_start();
require_once '../config/dbaccess.php'; // Подключение к базе

header('Content-Type: application/json');

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht angemeldet.']);
    exit;
}

if (!isset($_GET['orderId'])) {
    echo json_encode(['success' => false, 'message' => 'Keine Bestell-ID angegeben.']);
    exit;
}

$orderId = intval($_GET['orderId']);
$userId = $_SESSION['user_id'];

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Verbindung fehlgeschlagen.']);
    exit;
}

// Проверить принадлежит ли заказ пользователю
$queryCheck = "SELECT id FROM orders WHERE id = ? AND user_id = ?";
$stmtCheck = $db->prepare($queryCheck);
$stmtCheck->bind_param('ii', $orderId, $userId);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Keine Berechtigung.']);
    exit;
}

// Получить товары заказа
$query = "SELECT oi.product_id, p.name AS product_name, oi.price, oi.quantity
          FROM order_items oi
          JOIN products p ON oi.product_id = p.id
          WHERE oi.order_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $orderId);
$stmt->execute();
$result = $stmt->get_result();

$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode(['success' => true, 'items' => $items]);
?>
