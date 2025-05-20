<?php
session_start();
header("Content-Type: application/json");

require_once "../models/userClass.php";
require_once "../config/dbaccess.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = new User();

    $email = $_POST["email"];
    $password = $_POST["password"];
    $rememberMe = isset($_POST["rememberMe"]) && $_POST["rememberMe"] == 1;

    $userData = $user->login($email, $password);

    if ($userData) {
        $_SESSION["user_id"] = $userData["id"];
        $_SESSION["username"] = $userData["username"];
        $_SESSION["rolle"] = $userData["rolle"];

        // Wenn "Login merken" aktiviert wurde
        if ($rememberMe) {
            $token = bin2hex(random_bytes(32)); // sicherer zufälliger Token
            $db = new DBAccess();
            $conn = $db->getConnection();

            $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmt->bind_param("si", $token, $userData["id"]);
            $stmt->execute();

            // Setze das Cookie für 30 Tage
            setcookie("remember_token", $token, time() + (86400 * 30), "/");
        }

        echo json_encode(["success" => true, "message" => "Login erfolgreich!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Login fehlgeschlagen."]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Ungültige Anfrage."]);
