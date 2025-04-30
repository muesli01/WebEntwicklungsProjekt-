<?php
session_start();
header("Content-Type: application/json");

require_once "../models/userClass.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Alle Werte abholen
    $anrede   = trim($_POST["anrede"] ?? '');
    $vorname  = trim($_POST["vorname"] ?? '');
    $nachname = trim($_POST["nachname"] ?? '');
    $adresse  = trim($_POST["adresse"] ?? '');
    $plz      = trim($_POST["plz"] ?? '');
    $ort      = trim($_POST["ort"] ?? '');
    $email    = trim($_POST["email"] ?? '');
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    $zahlung  = trim($_POST["zahlung"] ?? '');

    // Serverseitige Validierung
    if (
        empty($anrede) || empty($vorname) || empty($nachname) || empty($adresse) ||
        empty($plz) || empty($ort) || empty($email) || empty($username) || empty($password) || empty($zahlung)
    ) {
        echo json_encode(["success" => false, "message" => "Bitte alle Felder ausfüllen."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Ungültige E-Mail-Adresse."]);
        exit;
    }

    if (!preg_match("/^\d{4,5}$/", $plz)) {
        echo json_encode(["success" => false, "message" => "Ungültige PLZ."]);
        exit;
    }

    // Neuen User erstellen
    $user = new User();
    $result = $user->registerExtended($anrede, $vorname, $nachname, $adresse, $plz, $ort, $email, $username, $password, $zahlung);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Registrierung erfolgreich!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Fehler bei der Registrierung."]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Ungültige Anfrage."]);
