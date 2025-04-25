<?php 
session_start();
header("Content-Type: application/json");

require_once "../models/productClass.php";

// GET PROCESSING: Get the contents of the shopping cart
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
        echo json_encode([]);
        exit;
    }

    $product = new Product();
    $result = $product->getAllProducts();

    $items = [];

    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        if (isset($_SESSION["cart"][$id])) {
            $row["quantity"] = $_SESSION["cart"][$id];
            $items[] = $row;
        }
    }

    echo json_encode($items);
    exit;
}

// POST PROCESSING: Add a product to the shopping cart
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = $_POST["productId"];

    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    if (isset($_SESSION["cart"][$productId])) {
        $_SESSION["cart"][$productId]++;
    } else {
        $_SESSION["cart"][$productId] = 1;
    }

    echo json_encode(["message" => "Produkt wurde zum Warenkorb hinzugefügt."]);
    exit;
}

//  Invalid request
echo json_encode(["error" => "Ungültige Anfrage."]);
