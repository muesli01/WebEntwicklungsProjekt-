<?php
session_start();

require_once "../models/orderClass.php";
require_once "../lib/fpdf/fpdf.php";

if (!isset($_SESSION["user_id"])) {
    die("Nicht eingeloggt.");
}

if (!isset($_GET["bestellnummer"])) {
    die("Keine Bestellnummer angegeben.");
}

$bestellnummer = $_GET["bestellnummer"];
$orderObj = new Order();
$order = $orderObj->getOrderByBestellnummer($bestellnummer);

// Überprüfung der Bestellzugehörigkeit für den Benutzer
if (!$order || $order["user_id"] != $_SESSION["user_id"]) {
    die("Zugriff verweigert.");
}

// Aufbau PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Заголовок
$pdf->Cell(0, 10, "Rechnung", 0, 1, "C");
$pdf->Ln(10);

// Bestelldaten
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Bestellnummer: " . $order["bestellnummer"], 0, 1);
$pdf->Cell(0, 10, "Datum: " . date('d.m.Y', strtotime($order["bestelldatum"])), 0, 1);
$pdf->Cell(0, 10, "Gesamtpreis: " . number_format($order["gesamtpreis"], 2) . " EUR", 0, 1);
$pdf->Cell(0, 10, "Status: " . $order["status"], 0, 1);

$pdf->Ln(20);
$pdf->Cell(0, 10, "Vielen Dank fuer Ihren Einkauf!", 0, 1, "C");

// Header zum Herunterladen
$pdf->Output('D', "Rechnung_" . $order["bestellnummer"] . ".pdf");
exit;
?>
