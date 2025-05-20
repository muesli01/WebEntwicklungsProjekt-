<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../config/dataHandler.php"; 
require_once "../models/userClass.php"; 

$dataHandler = new DataHandler(); 

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Alle Nutzer holen
    $users = $dataHandler->getUsers();
    echo json_encode($users);
}

elseif ($method === 'POST') {
    // Neuen Nutzer anlegen
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData["email"]) && isset($inputData["password"])) {
        $newUser = new User($inputData["email"], $inputData["password"]);
        $dataHandler->addUser($newUser);
        echo json_encode(["message" => "User registered successfully"]);
    } else {
        echo json_encode(["error" => "Invalid input"]);
    }
}

else {
    // Andere HTTP-Methoden nicht erlaubt
    echo json_encode(["error" => "Method not allowed"]);
}
?>
