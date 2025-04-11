<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<h2>Willkommen, <?php echo $_SESSION["username"]; ?>!</h2>
<a href="logout.php">Logout</a>
