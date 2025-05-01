<?php
session_start();
header("Content-Type: application/json");

require_once "../models/userClass.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Nicht eingeloggt."]);
    exit;
}

$userId = $_SESSION["user_id"];
$userObj = new User();

// Hier neue Methode aufrufen
$userData = $userObj->getUserById($userId);

if ($userData) {
    echo json_encode(["success" => true, "user" => $userData]);
} else {
    echo json_encode(["success" => false, "message" => "Benutzer nicht gefunden."]);
}
