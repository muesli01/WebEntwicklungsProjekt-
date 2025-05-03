<?php
session_start();
header("Content-Type: application/json");

require_once "../models/orderClass.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["rolle"] !== "admin") {
    echo json_encode(["error" => "Zugriff verweigert."]);
    exit;
}

$orderObj = new Order();

// Статус ändern
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "updateStatus") {
    $orderId = intval($_POST["orderId"]);
    $newStatus = $_POST["status"];

    if ($orderObj->updateOrderStatus($orderId, $newStatus)) {
        echo json_encode(["message" => "Status aktualisiert."]);
    } else {
        echo json_encode(["error" => "Fehler beim Aktualisieren."]);
    }
    exit;
}

// Получить детали заказа
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["orderId"])) {
    $orderId = intval($_GET["orderId"]);
    $orderDetails = $orderObj->getOrderWithItems($orderId);
    echo json_encode($orderDetails);
    exit;
}

echo json_encode(["error" => "Ungültige Anfrage."]);
