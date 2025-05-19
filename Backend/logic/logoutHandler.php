<?php 
session_start();
require_once "../config/dbaccess.php";

// Удаление remember_token из БД, если установлен
if (isset($_SESSION["user_id"])) {
    $db = new DBAccess();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
}

// Удалить cookie
setcookie("remember_token", "", time() - 3600, "/");

// Завершение сессии
session_unset();
session_destroy();

// Перенаправление
header("Location: ../../Frontend/sites/login.html");
exit();
?>
