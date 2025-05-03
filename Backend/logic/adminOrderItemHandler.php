<?php
session_start();
header("Content-Type: application/json");

require_once "../models/orderClass.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["rolle"] !== "admin") {
    echo json_encode(["error" => "Zugriff verweigert."]);
    exit;
}

$orderObj = new Order();

// Produkt aus Bestellung löschen
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "deleteItem") {
    $itemId = $_POST["itemId"];

    $result = $orderObj->deleteOrderItem($itemId);

    if ($result) {
        echo json_encode(["message" => "Produkt wurde entfernt."]);
    } else {
        echo json_encode(["error" => "Fehler beim Entfernen des Produkts."]);
    }
    exit;
}

echo json_encode(["error" => "Ungültige Anfrage."]);
?>
