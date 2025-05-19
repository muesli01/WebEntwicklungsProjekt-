<?php
session_start();
header("Content-Type: application/json");
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false,'message'=>'Nicht eingeloggt']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$name    = trim($data['name'] ?? '');
$details = trim($data['details'] ?? '');

if ($name === '') {
    echo json_encode(['success'=>false,'message'=>'Name der Zahlungsmethode erforderlich.']);
    exit;
}

require_once '../models/paymentClass.php';
$payment = new Payment();
$ok = $payment->addMethod($_SESSION['user_id'], $name, $details);

echo json_encode([
    'success' => $ok,
    'message' => $ok ? 'Zahlungsmethode hinzugefügt.' : 'Fehler beim Hinzufügen.'
]);
