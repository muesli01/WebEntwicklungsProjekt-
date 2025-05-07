<?php
session_start();
header("Content-Type: application/json");
require_once '../config/dbaccess.php';
require_once "../models/couponClass.php";

$gueltigeGutscheine = [
    "RABATT10" => 10,
    "SPAREN5" => 5,
    "DEAL20" => 20
];

if (!isset($_POST["gutscheincode"])) {
    echo json_encode(["success" => false, "message" => "Kein Gutscheincode angegeben."]);
    exit;
}

$code = strtoupper(trim($_POST["gutscheincode"] ?? ''));

if (array_key_exists($code, $gueltigeGutscheine)) {
    echo json_encode([
        "success" => true,
        "message" => "Gutschein erfolgreich eingelöst!",
        "amount" => $gueltigeGutscheine[$code]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Ungültiger Gutscheincode."
    ]);
}
