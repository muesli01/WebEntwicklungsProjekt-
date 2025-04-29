<?php
session_start();

$message = "";

// Wenn das Formular gesendet wurde
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "../../Backend/models/userClass.php";
    $user = new User();

    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($user->login($email, $password)) {
        $message = "Login erfolgreich!";
        header("Location: dashboard.php"); // Zielseite nach Login
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
    <title>Login – Auto Webshop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/PhpFiles/Webshop/WebEntwicklungsProjekt-/Frontend/res/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

    <!-- JavaScript für die Navigation -->
    <script src="../js/nav.js" defer></script>
</head>
<body>

    <!-- Navigation wird hier eingefügt -->
    <div id="nav-placeholder"></div>

    <div class="container my-5">
        <h2>Login</h2>

        <?php if (!empty($message)): ?>
            <p style="color: red;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail:</label>
                <input type="email" name="email" id="email" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Passwort:</label>
                <input type="password" name="password" id="password" class="form-control" required />
            </div>

            <button type="submit" class="btn btn-primary">Einloggen</button>
        </form>
    </div>

</body>
</html>
