<?php
session_start();
header("Content-Type: application/json");

require_once "../models/orderClass.php";

// Prüfen, ob der Nutzer eingeloggt ist
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Nicht eingeloggt."]);
    exit;
}

$orderObj = new Order();
// Bestellungen des eingeloggten Nutzers abrufen
$orders = $orderObj->getOrdersByUser($_SESSION["user_id"]);

// Wenn Bestellungen vorhanden sind, zurückgeben
if ($orders !== null) {
    echo json_encode(["success" => true, "orders" => $orders]);
} else {
    // Keine Bestellungen gefunden
    echo json_encode(["success" => false, "message" => "Keine Bestellungen gefunden."]);
}
