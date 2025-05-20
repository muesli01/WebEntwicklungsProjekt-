<?php
session_start();
header("Content-Type: application/json");

require_once "../config/dbaccess.php";

// Wenn nicht eingeloggt, aber "remember_token" Cookie gesetzt ist, versuche automatische Anmeldung
if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_token"])) {
    $token = $_COOKIE["remember_token"];

    $db = new DBAccess();
    $conn = $db->getConnection();

    // Benutzer anhand des Tokens aus der Datenbank holen
    $stmt = $conn->prepare("SELECT id, username, rolle FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Wenn Benutzer gefunden, Session-Daten setzen
    if ($user) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["rolle"] = $user["rolle"];
    }
}

// Antwort, ob eingeloggt oder nicht
if (isset($_SESSION["user_id"])) {
    echo json_encode([
        "loggedIn" => true,
        "username" => $_SESSION["username"],
        "rolle" => $_SESSION["rolle"]
    ]);
} else {
    echo json_encode(["loggedIn" => false]);
}
