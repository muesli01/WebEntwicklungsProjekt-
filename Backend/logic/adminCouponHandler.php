<?php
session_start();
header("Content-Type: application/json");

require_once "../models/couponClass.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["rolle"] !== "admin") {
    echo json_encode(["error" => "Zugriff verweigert."]);
    exit;
}

$couponObj = new Coupon();

// Gutschein erstellen
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "create") {
    $wert = $_POST["wert"];
    $gueltigBis = $_POST["gueltig_bis"];
    $code = generateCouponCode();

    $result = $couponObj->createCoupon($code, $wert, $gueltigBis);

    if ($result) {
        echo json_encode(["message" => "Gutschein erfolgreich erstellt."]);
    } else {
        echo json_encode(["error" => "Fehler beim Erstellen des Gutscheins."]);
    }
    exit;
}

// Alle Gutscheine abrufen und Status prüfen
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $coupons = $couponObj->getAllCoupons();

    // Überprüfen, ob Gutscheine abgelaufen sind
    $heute = date("Y-m-d");
    foreach ($coupons as &$coupon) {
        if ($coupon["gueltig_bis"] < $heute && $coupon["status"] === "aktiv") {
            // Wenn abgelaufen, Status aktualisieren
            $couponObj->updateCouponStatus($coupon["id"], "abgelaufen");
            $coupon["status"] = "abgelaufen";
        }
    }

    echo json_encode($coupons);
    exit;
}
// Gutschein als eingelöst markieren
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "markAsUsed") {
    $couponId = $_POST["couponId"];

    $result = $couponObj->updateCouponStatus($couponId, "eingelöst");

    if ($result) {
        echo json_encode(["message" => "Gutschein wurde als eingelöst markiert."]);
    } else {
        echo json_encode(["error" => "Fehler beim Aktualisieren des Gutscheins."]);
    }
    exit;
}



// Ungültige Anfrage
echo json_encode(["error" => "Ungültige Anfrage."]);

// Gutschein-Code Generator (5 Zeichen: Buchstaben + Zahlen)
function generateCouponCode() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < 5; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}
?>
