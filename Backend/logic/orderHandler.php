<?php
session_start();
header("Content-Type: application/json");

require_once "../models/productClass.php";
require_once "../models/orderClass.php";
require_once "../models/couponClass.php";
require_once "../models/paymentClass.php"; // Neue Klasse für Zahlungsinfos

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

// Gutscheinbehandlung
$couponCode = isset($_POST["gutscheincode"]) ? strtoupper(trim($_POST["gutscheincode"])) : null;
$couponObj = new Coupon();
$couponId = null; // <--- добавляем переменную, по умолчанию NULL

if (!empty($couponCode)) {
    $coupon = $couponObj->getCouponByCode($couponCode);

    if ($coupon && $coupon["status"] === "aktiv" && strtotime($coupon["gueltig_bis"]) >= time()) {
        $remainingValue = (float) $coupon["wert"];

        if ($remainingValue > 0) {
            if ($remainingValue >= $gesamtpreis) {
                $newRemainingValue = $remainingValue - $gesamtpreis;
                $gesamtpreis = 0;
            } else {
                $gesamtpreis -= $remainingValue;
                $newRemainingValue = 0;
            }

            $couponObj->updateRemainingValue($coupon["id"], $newRemainingValue);
        }

        $couponId = $coupon["id"]; // <--- присваиваем после успешной обработки
    } else {
        echo json_encode(["success" => false, "message" => "Ungültiger oder abgelaufener Gutschein."]);
        exit;
    }
}


// Zahlungsdetails erfassen
$paymentMethodId = isset($_POST["paymentMethodId"]) ? $_POST["paymentMethodId"] : null;
if ($paymentMethodId === null || $paymentMethodId === "") {
    echo json_encode(["success" => false, "message" => "Bitte wählen Sie eine Zahlungsmethode."]);
    exit;
}


// Bestellung erstellen...
require_once "../models/orderClass.php";
$orderObj = new Order();
$bestellnummer = 'ORD' . date('YmdHis') . rand(100, 999);
$orderCreated = $orderObj->createOrderWithItems($_SESSION["user_id"], $bestellnummer, $gesamtpreis, $items,$couponId);

if ($orderCreated) {
    $paymentObj = new Payment();
    $saved = $paymentObj->getSavedMethods($_SESSION["user_id"]);
    $chosen = array_filter($saved, fn($m) => $m['id'] == $_POST['paymentMethodId']);

    if ($_POST['paymentMethodId'] == "0") {
        require_once "../models/userClass.php";
        $userModel = new User();
        $userData = $userModel->getUserById($_SESSION["user_id"]);
        $paymentObj->savePayment($_SESSION["user_id"], $bestellnummer, "Standard", $userData['zahlung']);
    } elseif ($chosen) {
        $method = current($chosen)['name'];
        $details = current($chosen)['details'];
        $paymentObj->savePayment($_SESSION["user_id"], $bestellnummer, $method, $details);
    } else {
        $paymentObj->savePayment($_SESSION["user_id"], $bestellnummer, $_POST['paymentMethodId'], "");
    }


    unset($_SESSION["cart"]);
    echo json_encode([
        "success" => true,
        "message" => "Bestellung erfolgreich abgeschickt!",
        "bestellnummer" => $bestellnummer
    ]);
    exit;
}


?>