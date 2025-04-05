<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../config/dataHandler.php"; // Подключаем обработчик данных
require_once "../models/userСlass.php"; // Подключаем модель пользователя

$dataHandler = new DataHandler(); // Создаем объект обработки данных

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Получение всех пользователей
    $users = $dataHandler->getUsers();
    echo json_encode($users);
}

elseif ($method === 'POST') {
    // Читаем JSON-данные из тела запроса
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
    echo json_encode(["error" => "Method not allowed"]);
}
?>
