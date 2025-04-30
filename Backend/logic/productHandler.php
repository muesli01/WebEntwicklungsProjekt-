<?php
header("Content-Type: application/json");
require_once "../models/productClass.php";

$product = new Product();
$result = $product->getAllProducts();

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = [
        "id" => $row["id"],
        "name" => $row["name"],
        "description" => $row["description"],
        "price" => $row["price"],
        "image" => $row["image"]
    ];
}

echo json_encode($products);
