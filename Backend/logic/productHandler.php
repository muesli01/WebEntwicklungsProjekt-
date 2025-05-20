<?php
header("Content-Type: application/json");
require_once "../models/productClass.php";

$product = new Product();
// Alle Produkte aus der Datenbank abrufen
$result = $product->getAllProducts();

$products = [];

// Ergebnisse durchgehen und in ein Array speichern
while ($row = $result->fetch_assoc()) {
    $products[] = [
        "id" => $row["id"],
        "name" => $row["name"],
        "description" => $row["description"],
        "price" => $row["price"],
        "image" => $row["image"]
    ];
}

// Produkte als JSON ausgeben
echo json_encode($products);
