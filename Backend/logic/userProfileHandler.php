<?php
session_start();
header("Content-Type: application/json");

require_once "../models/userClass.php";

// Prüfen, ob der Nutzer eingeloggt ist
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Nicht eingeloggt."]);
    exit;
}

$userId = $_SESSION["user_id"];
$userObj = new User();

// Benutzerinformationen anhand der ID abrufen
$userData = $userObj->getUserById($userId);

// Wenn Daten gefunden, als JSON ausgeben, sonst Fehlermeldung
if ($userData) {
    echo json_encode(["success" => true, "user" => $userData]);
} else {
    echo json_encode(["success" => false, "message" => "Benutzer nicht gefunden."]);
}
