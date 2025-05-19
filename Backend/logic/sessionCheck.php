<?php
session_start();
header("Content-Type: application/json");

require_once "../config/dbaccess.php";

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_token"])) {
    $token = $_COOKIE["remember_token"];

    $db = new DBAccess();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT id, username, rolle FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["rolle"] = $user["rolle"];
    }
}

if (isset($_SESSION["user_id"])) {
    echo json_encode([
        "loggedIn" => true,
        "username" => $_SESSION["username"],
        "rolle" => $_SESSION["rolle"]
    ]);
} else {
    echo json_encode(["loggedIn" => false]);
}
