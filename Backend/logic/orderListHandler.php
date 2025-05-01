<?php
session_start();
header("Content-Type: application/json");

require_once "../models/orderClass.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Nicht eingeloggt."]);
    exit;
}

$orderObj = new Order();
$orders = $orderObj->getOrdersByUser($_SESSION["user_id"]);

if ($orders !== null) {
    echo json_encode(["success" => true, "orders" => $orders]);
} else {
    echo json_encode(["success" => false, "message" => "Keine Bestellungen gefunden."]);
}
