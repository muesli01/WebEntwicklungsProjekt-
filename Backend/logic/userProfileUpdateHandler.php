<?php
session_start();
header("Content-Type: application/json");

require_once "../models/userClass.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Nicht eingeloggt."]);
    exit;
}

// Eingaben holen
$vorname = trim($_POST["vorname"] ?? '');
$nachname = trim($_POST["nachname"] ?? '');
$adresse = trim($_POST["adresse"] ?? '');
$zahlung = trim($_POST["zahlung"] ?? '');
$passwordConfirm = $_POST["password_confirm"] ?? '';

if (empty($vorname) || empty($nachname) || empty($adresse) || empty($passwordConfirm)) {
    echo json_encode(["success" => false, "message" => "Bitte alle Pflichtfelder ausfüllen."]);
    exit;
}

// Benutzer aus der DB holen
$userObj = new User();
$userData = $userObj->getUserFullById($_SESSION["user_id"]);

if (!$userData) {
    echo json_encode(["success" => false, "message" => "Benutzer nicht gefunden."]);
    exit;
}

// Passwort überprüfen
if (!password_verify($passwordConfirm, $userData["password"])) {
    echo json_encode(["success" => false, "message" => "Passwort ist falsch."]);
    exit;
}

// Aktualisierung durchführen
$result = $userObj->updateUserProfile($_SESSION["user_id"], $vorname, $nachname, $adresse, $zahlung);

if ($result) {
    echo json_encode(["success" => true, "message" => "Profil erfolgreich aktualisiert."]);
} else {
    echo json_encode(["success" => false, "message" => "Fehler beim Aktualisieren."]);
}
