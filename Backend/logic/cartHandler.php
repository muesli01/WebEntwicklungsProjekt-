<?php
session_start();
header("Content-Type: application/json");

require_once "../models/productClass.php";

// GET: Получить содержимое корзины
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

// POST: Удаление товара из корзины
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "remove") {
    $productId = $_POST["productId"];

    if (isset($_SESSION["cart"][$productId])) {
        unset($_SESSION["cart"][$productId]);
        echo json_encode(["message" => "Produkt wurde entfernt."]);
    } else {
        echo json_encode(["message" => "Produkt nicht gefunden."]);
    }
    exit;
}
// POST: Увеличение количества товара
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "increase") {
    $productId = $_POST["productId"];

    if (isset($_SESSION["cart"][$productId])) {
        $_SESSION["cart"][$productId]++;
        echo json_encode(["message" => "Menge erhöht."]);
    } else {
        echo json_encode(["message" => "Produkt nicht gefunden."]);
    }
    exit;
}

// POST: Уменьшение количества товара
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "decrease") {
    $productId = $_POST["productId"];

    if (isset($_SESSION["cart"][$productId]) && $_SESSION["cart"][$productId] > 1) {
        $_SESSION["cart"][$productId]--;
        echo json_encode(["message" => "Menge verringert."]);
    } elseif (isset($_SESSION["cart"][$productId])) {
        unset($_SESSION["cart"][$productId]); // Если количество стало 0 — удаляем товар
        echo json_encode(["message" => "Produkt entfernt."]);
    } else {
        echo json_encode(["message" => "Produkt nicht gefunden."]);
    }
    exit;
}


// POST: Добавление товара в корзину
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

// Неверный запрос
echo json_encode(["error" => "Ungültige Anfrage."]);
