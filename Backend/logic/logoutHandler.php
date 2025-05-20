<?php 
session_start();
require_once "../config/dbaccess.php";

// Entfernen des remember_token aus der Datenbank, falls gesetzt
if (isset($_SESSION["user_id"])) {
    $db = new DBAccess();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
}

// Cookie löschen
setcookie("remember_token", "", time() - 3600, "/");

// Session beenden
session_unset();
session_destroy();

// Weiterleitung zur Login-Seite
header("Location: ../../Frontend/sites/login.html");
exit();
?>
