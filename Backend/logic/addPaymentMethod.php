<?php
session_start(); // Session starten, um auf Session-Daten zuzugreifen
header("Content-Type: application/json"); // JSON-Antwort-Header setzen

// Prüfen, ob der Benutzer eingeloggt ist (user_id in Session vorhanden)
if (!isset($_SESSION['user_id'])) {
    // Wenn nicht eingeloggt, JSON-Fehlermeldung senden und Skript beenden
    echo json_encode(['success'=>false,'message'=>'Nicht eingeloggt']);
    exit;
}

// JSON-Daten aus dem Request-Body auslesen (php://input) und als Array dekodieren
$data = json_decode(file_get_contents("php://input"), true);

// Name und Details der Zahlungsmethode aus den empfangenen Daten holen, Trim für Leerzeichen entfernen
$name    = trim($data['name'] ?? '');
$details = trim($data['details'] ?? '');

// Prüfen, ob der Name leer ist – wenn ja, Fehlermeldung senden und Skript beenden
if ($name === '') {
    echo json_encode(['success'=>false,'message'=>'Name der Zahlungsmethode erforderlich.']);
    exit;
}

// Payment-Klasse laden (Model)
require_once '../models/paymentClass.php';

// Neues Payment-Objekt erstellen
$payment = new Payment();

// Zahlungsmethode mit der User-ID und den eingegebenen Daten hinzufügen
$ok = $payment->addMethod($_SESSION['user_id'], $name, $details);

// JSON-Antwort senden: Erfolg oder Fehler, je nach Ergebnis von addMethod
echo json_encode([
    'success' => $ok,
    'message' => $ok ? 'Zahlungsmethode hinzugefügt.' : 'Fehler beim Hinzufügen.'
]);
