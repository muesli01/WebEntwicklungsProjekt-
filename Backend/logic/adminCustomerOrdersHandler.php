<?php
session_start(); // Session starten, um auf Session-Daten zuzugreifen
header("Content-Type: application/json"); // JSON-Antwort-Header setzen

require_once "../models/orderClass.php"; // Order-Klasse laden (Model)

// Zugriff prüfen: Nur eingeloggte Admins dürfen fortfahren
if (!isset($_SESSION["user_id"]) || $_SESSION["rolle"] !== "admin") {
    // Zugriff verweigert, JSON-Fehlermeldung senden und Skript beenden
    echo json_encode(["message" => "Zugriff verweigert."]);
    exit;
}

// Prüfen, ob die Anfrage eine GET-Anfrage ist und userId übergeben wurde
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["userId"])) {
    // userId aus GET-Parameter holen und in Integer umwandeln
    $userId = intval($_GET["userId"]);

    // Neues Order-Objekt erstellen
    $order = new Order();

    // Bestellungen für den angegebenen userId abrufen
    $orders = $order->getOrdersByUserId($userId);

    // Bestellungen als JSON ausgeben und Skript beenden
    echo json_encode($orders);
    exit;
}

// Falls keine gültige Anfrage vorliegt, Fehlermeldung als JSON zurückgeben
echo json_encode(["error" => "Ungültige Anfrage."]);
