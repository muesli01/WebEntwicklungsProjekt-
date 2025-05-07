<?php
session_start();
header("Content-Type: application/json");

require_once '../config/dbaccess.php';
require_once "../models/couponClass.php";

if (!isset($_POST["gutscheincode"])) {
    echo json_encode(["success" => false, "message" => "Kein Gutscheincode angegeben."]);
    exit;
}

$code = strtoupper(trim($_POST["gutscheincode"]));
$couponObj = new Coupon();
$coupon = $couponObj->getCouponByCode($code);

if ($coupon) {
    $heute = date('Y-m-d');

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
