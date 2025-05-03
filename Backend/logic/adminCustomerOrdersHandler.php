<?php
session_start();
header("Content-Type: application/json");

require_once "../models/orderClass.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["rolle"] !== "admin") {
    echo json_encode(["message" => "Zugriff verweigert."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["userId"])) {
    $userId = intval($_GET["userId"]);
    $order = new Order();
    $orders = $order->getOrdersByUserId($userId);
    echo json_encode($orders);
    exit;
}

echo json_encode(["error" => "Ungültige Anfrage."]);
