<?php
session_start();
require_once '../config/dbaccess.php'; // Подключение к базе

header('Content-Type: application/json');

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht angemeldet.']);
    exit;
}

$userId = $_SESSION['user_id'];

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Verbindung fehlgeschlagen.']);
    exit;
}

// Получить все заказы пользователя
$query = "SELECT id, bestellnummer, bestelldatum, gesamtpreis, status FROM orders WHERE user_id = ? ORDER BY bestelldatum DESC";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode(['success' => true, 'orders' => $orders]);
?>
