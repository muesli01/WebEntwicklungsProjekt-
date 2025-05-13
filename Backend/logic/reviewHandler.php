<?php
session_start();
header("Content-Type: application/json");

require_once "../models/reviewClass.php";

$reviewObj = new Review();

// Проверка авторизации
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Nicht eingeloggt."]);
    exit;
}

$userId = $_SESSION["user_id"];

// POST — создать отзыв
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = $_POST["product_id"] ?? null;
    $orderId = $_POST["order_id"] ?? null;
    $rating = $_POST["rating"] ?? null;
    $comment = $_POST["comment"] ?? "";

    if (!$productId || !$orderId || !$rating) {
        echo json_encode(["success" => false, "message" => "Fehlende Angaben."]);
        exit;
    }

    // Проверка: пользователь уже оставлял отзыв?
    if ($reviewObj->hasUserReviewed($userId, $productId, $orderId)) {
        echo json_encode(["success" => false, "message" => "Sie haben dieses Produkt bereits bewertet."]);
        exit;
    }

    // Сохраняем отзыв
    $success = $reviewObj->createReview($userId, $productId, $orderId, (int)$rating, trim($comment));

    if ($success) {
        echo json_encode(["success" => true, "message" => "Bewertung gespeichert. Vielen Dank!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Fehler beim Speichern der Bewertung."]);
    }

    exit;
}

// GET — получить отзывы по продукту
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["product_id"])) {
    $productId = $_GET["product_id"];
    $reviews = $reviewObj->getReviewsByProductId($productId);
    echo json_encode(["success" => true, "reviews" => $reviews]);
    exit;
}

// Неверный запрос
echo json_encode(["success" => false, "message" => "Ungültige Anfrage."]);
