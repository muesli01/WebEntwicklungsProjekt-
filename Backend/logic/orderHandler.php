<?php 
session_start();
header("Content-Type: application/json");

require_once "../models/productClass.php";
require_once "../models/orderClass.php";
require_once "../models/couponClass.php"; // Gutscheinklasse laden

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Nicht eingeloggt."]);
    exit;
}

if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    echo json_encode(["success" => false, "message" => "Warenkorb ist leer."]);
    exit;
}

$productObj = new Product();
$result = $productObj->getAllProducts();
$gesamtpreis = 0;
$items = [];

// Warenkorb durchgehen
while ($row = $result->fetch_assoc()) {
    $id = $row["id"];
    if (isset($_SESSION["cart"][$id])) {
        $quantity = $_SESSION["cart"][$id];
        $preis = $row["price"];

        $items[] = [
            "product_id" => $id,
            "quantity" => $quantity,
            "price" => $preis
        ];

        $gesamtpreis += $preis * $quantity;
    }
}

// Gutschein einlösen, falls vorhanden
$couponCode = isset($_POST["gutscheincode"]) ? strtoupper(trim($_POST["gutscheincode"])) : null;
$couponObj = new Coupon();

if (!empty($couponCode)) {
    $coupon = $couponObj->getCouponByCode($couponCode);

    if ($coupon && $coupon["status"] === "aktiv" && strtotime($coupon["gueltig_bis"]) >= time()) {
        $remainingValue = (float)$coupon["wert"]; // Gutschein-Wert

        if ($remainingValue > 0) {
            if ($remainingValue >= $gesamtpreis) {
                $newRemainingValue = $remainingValue - $gesamtpreis;
                $gesamtpreis = 0;
            } else {
                $gesamtpreis -= $remainingValue;
                $newRemainingValue = 0;
            }

            // Gutschein aktualisieren
            $couponObj->updateRemainingValue($coupon["id"], $newRemainingValue);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Ungültiger oder abgelaufener Gutschein."]);
        exit;
    }
}

// Bestellung erstellen
$orderObj = new Order();
$bestellnummer = "ORD" . time();
$orderCreated = $orderObj->createOrderWithItems($_SESSION["user_id"], $bestellnummer, $gesamtpreis, $items);

if ($orderCreated) {
    unset($_SESSION["cart"]);
    echo json_encode(["success" => true, "message" => "Bestellung erfolgreich abgeschickt!", "bestellnummer" => $bestellnummer]);
} else {
    echo json_encode(["success" => false, "message" => "Fehler beim Absenden der Bestellung."]);
}
?>
