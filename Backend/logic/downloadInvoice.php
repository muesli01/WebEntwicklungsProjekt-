<?php
session_start();

require_once "../models/orderClass.php";
require_once "../models/productClass.php";
require_once "../lib/fpdf/fpdf.php";

if (!isset($_SESSION["user_id"])) {
    die("Nicht eingeloggt.");
}

$orderObj = new Order();
$productObj = new Product();
$order = null;
$orderId = null;
$bestellnummer = null;

// Prüfen, ob orderId oder bestellnummer angegeben ist
if (isset($_GET["orderId"])) {
    $orderId = intval($_GET["orderId"]);
    $order = $orderObj->getOrderById($orderId);
} elseif (isset($_GET["bestellnummer"])) {
    $bestellnummer = $_GET["bestellnummer"];
    $order = $orderObj->getOrderByBestellnummer($bestellnummer);
} else {
    die("Keine Bestellnummer oder Order ID angegeben.");
}

if (!$order) {
    die("Bestellung nicht gefunden.");
}

// Zugriffsrechte prüfen: Admin oder eigener Nutzer
if ($_SESSION["rolle"] !== "admin" && $order["user_id"] != $_SESSION["user_id"]) {
    die("Zugriff verweigert.");
}

// Gutschein laden (falls vorhanden)
$coupon = $orderObj->getCouponByOrderId($order["id"]);

// Bestellte Artikel holen
$orderItems = $orderObj->getOrderItems($order["id"]);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Titel
$pdf->Cell(0, 10, "Rechnung", 0, 1, "C");
$pdf->Ln(10);

// Bestellinformationen anzeigen
$pdf->SetFont('Arial', '', 12);
$angezeigteNummer = $order["bestellnummer"] ?? $order["id"];
$pdf->Cell(0, 10, "Bestellnummer: " . $angezeigteNummer, 0, 1);
$pdf->Cell(0, 10, "Datum: " . date('d.m.Y', strtotime($order["bestelldatum"])), 0, 1);
$pdf->Ln(10);

// Kopfzeile für Artikel
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, "Bild", 0, 0);
$pdf->Cell(70, 10, "Produkt", 0, 0);
$pdf->Cell(30, 10, "Menge", 0, 0);
$pdf->Cell(30, 10, "Preis", 0, 1);
$pdf->SetFont('Arial', '', 12);

// Artikel und Bilder ausgeben
foreach ($orderItems as $item) {
    $productDetails = $productObj->getProductById($item["product_id"]);

    if ($productDetails) {
        $y = $pdf->GetY();
        $imagePath = realpath(__DIR__ . "/../productpictures/" . $productDetails["image"]);

        if ($imagePath && file_exists($imagePath)) {
            $pdf->Image($imagePath, $pdf->GetX(), $y, 20, 20);
        }

        $pdf->SetXY($pdf->GetX() + 25, $y);
        $pdf->Cell(70, 20, $productDetails["name"], 0, 0);
        $pdf->Cell(30, 20, $item["quantity"], 0, 0);
        $pdf->Cell(30, 20, number_format($item["price"], 2) . " EUR", 0, 1);
        $pdf->Ln(5);
    }
}

$pdf->Ln(10);

// Gesamtpreis anzeigen
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, "Gesamtpreis: " . number_format($order["gesamtpreis"], 2) . " EUR", 0, 1, "R");

// Gutschein anzeigen, falls vorhanden
if ($coupon) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Verwendeter Gutschein: " . $coupon["code"], 0, 1, "R");
    $pdf->Cell(0, 10, "Gutscheinwert: -" . number_format($coupon["wert"], 2) . " EUR", 0, 1, "R");
}

$pdf->Ln(20);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Vielen Dank fuer Ihren Einkauf!", 0, 1, "C");

// PDF-Ausgabe als Download
$pdf->Output('D', "Rechnung_" . $angezeigteNummer . ".pdf");
exit;
?>
