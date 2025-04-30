<?php
session_start();
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "../models/userClass.php";
    $user = new User();

    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($user->login($email, $password)) {
        echo json_encode(["success" => true, "message" => "Login erfolgreich!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Login fehlgeschlagen."]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Ungültige Anfrage."]);
