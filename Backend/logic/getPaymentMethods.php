<?php
session_start();
header('Content-Type: application/json');

// Prüfen, ob Nutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

require_once '../models/paymentClass.php';
$userId = $_SESSION['user_id'];

$payment = new Payment();
// Gespeicherte Zahlungsmethoden für Nutzer holen
$methods = $payment->getSavedMethods($userId);

require_once '../models/userClass.php';
$userModel = new User();
// Zusätzliche Zahlungsdaten des Nutzers abrufen
$userData  = $userModel->getUserById($userId);

// Falls Standard-Zahlungsmethode vorhanden, ganz vorne einfügen
if (!empty($userData['zahlung'])) {
    array_unshift($methods, ['id'=>0, 'name'=>'Standard', 'details'=>$userData['zahlung']]);
}

// Antwort mit Zahlungsmethoden senden
echo json_encode(['success' => true, 'methods' => $methods]);
