<?php
session_start();
header("Content-Type: application/json");

require_once "../models/userClass.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["rolle"] !== "admin") {
    echo json_encode(["message" => "Zugriff verweigert."]);
    exit;
}

$user = new User();

// Kundenliste laden
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $customers = $user->getAllCustomers();
    echo json_encode($customers);
    exit;
}

// Kundenstatus ändern (aktiv/inaktiv)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "toggle") {
    $id = intval($_POST["id"]);
    $updated = $user->toggleCustomerActive($id);
    if ($updated) {
        echo json_encode(["message" => "Status erfolgreich geändert."]);
    } else {
        echo json_encode(["message" => "Fehler beim Ändern des Status."]);
    }
    exit;
}

echo json_encode(["error" => "Ungültige Anfrage."]);
