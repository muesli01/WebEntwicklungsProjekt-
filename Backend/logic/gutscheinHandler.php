<?php
session_start();
header("Content-Type: application/json");

require_once '../config/dbaccess.php';
require_once "../models/couponClass.php";

// Prüfen, ob ein Gutscheincode übergeben wurde
if (!isset($_POST["gutscheincode"])) {
    echo json_encode(["success" => false, "message" => "Kein Gutscheincode angegeben."]);
    exit;
}

// Gutscheincode bereinigen und in Großbuchstaben umwandeln
$code = strtoupper(trim($_POST["gutscheincode"]));
$couponObj = new Coupon();
$coupon = $couponObj->getCouponByCode($code);

// Wenn kein Gutschein gefunden wurde
if ($coupon) {
    $heute = date('Y-m-d');

    // Prüfen, ob Gutschein aktiv und gültig ist
    if ($coupon['status'] === 'aktiv' && $coupon['gueltig_bis'] >= $heute) {
        echo json_encode([
            "success" => true,
            "message" => "Gutschein erfolgreich eingelöst!",
            "amount" => (float)$coupon['wert']
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Dieser Gutschein ist nicht mehr gültig oder wurde bereits eingelöst."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Ungültiger Gutscheincode."
    ]);
}
