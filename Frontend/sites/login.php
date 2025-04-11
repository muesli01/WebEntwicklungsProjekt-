<?php
session_start();

$message = "";

// Если форма отправлена
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "../../Backend/models/userClass.php";
    $user = new User();

    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($user->login($email, $password)) {
        $message = "Login erfolgreich!";
        header("Location: dashboard.php"); // замените на свою защищенную страницу
        exit;
    } else {
        $message = "Login fehlgeschlagen. E-Mail oder Passwort ist falsch.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="../res/css/style.css" />
</head>
<body>
    <h2>Login</h2>

    <?php if (!empty($message)): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="email">E-Mail:</label><br />
        <input type="email" name="email" required /><br />

        <label for="password">Passwort:</label><br />
        <input type="password" name="password" required /><br />

        <button type="submit">Einloggen</button>
    </form>
</body>
</html>
