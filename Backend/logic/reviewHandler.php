<?php
session_start();
header("Content-Type: application/json");

require_once "../models/reviewClass.php";

$reviewObj = new Review();

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

    if ($reviewObj->hasUserReviewed($userId, $productId, $orderId)) {
        echo json_encode(["success" => false, "message" => "Sie haben dieses Produkt bereits bewertet."]);
        exit;
    }

    $success = $reviewObj->createReview($userId, $productId, $orderId, (int)$rating, trim($comment));

    if ($success) {
        echo json_encode(["success" => true, "message" => "Bewertung gespeichert. Vielen Dank!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Fehler beim Speichern der Bewertung."]);
    }
    exit;
}

// GET — получить отзыв конкретного пользователя по товару и заказу
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["product_id"], $_GET["order_id"])) {
    $productId = (int)$_GET["product_id"];
    $orderId = (int)$_GET["order_id"];
    $review = $reviewObj->getUserReview($userId, $productId, $orderId);
    echo json_encode(["success" => true, "review" => $review]);
    exit;
}

echo json_encode(["success" => false, "message" => "Ungültige Anfrage."]);
