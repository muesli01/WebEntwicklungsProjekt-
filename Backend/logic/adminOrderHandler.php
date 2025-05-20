<?php
session_start(); // Session starten, um auf Session-Daten zuzugreifen
header("Content-Type: application/json"); // JSON-Antwort-Header setzen

require_once "../models/orderClass.php"; // Order-Klasse laden
require_once "../models/reviewClass.php"; // Review-Klasse laden

// Zugriff prüfen: Nur eingeloggte Admins dürfen fortfahren
if (!isset($_SESSION["user_id"]) || $_SESSION["rolle"] !== "admin") {
    // Zugriff verweigert, JSON-Fehlermeldung senden und Skript beenden
    echo json_encode(["error" => "Zugriff verweigert."]);
    exit;
}

$orderObj = new Order();   // Order-Objekt erstellen
$reviewObj = new Review(); // Review-Objekt erstellen

// Statusänderung der Bestellung verarbeiten
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "updateStatus") {
    $orderId = intval($_POST["orderId"]); // orderId aus POST holen und in Integer umwandeln
    $newStatus = $_POST["status"];        // neuer Status aus POST holen

    // Status aktualisieren und Erfolg oder Fehler als JSON zurückgeben
    if ($orderObj->updateOrderStatus($orderId, $newStatus)) {
        echo json_encode(["message" => "Status aktualisiert."]);
    } else {
        echo json_encode(["error" => "Fehler beim Aktualisieren."]);
    }
    exit;
}

// Details einer Bestellung abfragen
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["orderId"])) {
    $orderId = intval($_GET["orderId"]); // orderId aus GET holen und in Integer umwandeln
    $orderDetails = $orderObj->getOrderWithItems($orderId); // Bestellung mit Artikeln laden

    // Für jeden Artikel die Bewertung abrufen und anhängen
    foreach ($orderDetails as &$item) {
        $review = $reviewObj->getReviewForOrderItem($item["product_id"], $orderId);
        $item["review"] = $review; // Bewertung zum Artikel hinzufügen
    }

    // Bestellung mit Bewertungen als JSON ausgeben
    echo json_encode($orderDetails);
    exit;
}

// Dieser Block scheint doppelt und wird nie erreicht, da zuvor schon exit steht
foreach ($orderDetails as &$item) {
    $review = $reviewObj->getReviewForOrderItem($item["product_id"], $orderId);
    $item["review"] = $review;
}

// Ungültige Anfrage, wenn keine obigen Bedingungen erfüllt wurden
echo json_encode(["error" => "Ungültige Anfrage."]);
