<?php
session_start();
header("Content-Type: application/json");

require_once "../models/productClass.php";
require_once "../models/orderClass.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Nicht eingeloggt."]);
    exit;
}

if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    echo json_encode(["success" => false, "message" => "Warenkorb ist leer."]);
    exit;
}

// Warenkorb auslesen
$product = new Product();
$result = $product->getAllProducts();
$gesamtpreis = 0;

while ($row = $result->fetch_assoc()) {
    $id = $row["id"];
    if (isset($_SESSION["cart"][$id])) {
        $gesamtpreis += $row["price"] * $_SESSION["cart"][$id];
    }
}

// Bestellnummer generieren
$bestellnummer = "ORD" . time();

// Bestellung speichern
$orderObj = new Order();
$orderCreated = $orderObj->createOrder($_SESSION["user_id"], $bestellnummer, $gesamtpreis);

if ($orderCreated) {
    // Warenkorb leeren
    unset($_SESSION["cart"]);
    echo json_encode(["success" => true, "message" => "Bestellung erfolgreich abgeschickt!", "bestellnummer" => $bestellnummer]);
} else {
    echo json_encode(["success" => false, "message" => "Fehler beim Absenden der Bestellung."]);
}
