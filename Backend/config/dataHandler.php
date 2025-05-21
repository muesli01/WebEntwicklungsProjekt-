<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "dbaccess.php"; // DB-Verbindung laden
require_once __DIR__ . "/../models/userClass.php"; // User-Klasse einbinden

class DataHandler {
    private $db;

    public function __construct() {
        $this->db = new DBAccess(); // DBAccess-Instanz
    }

    public function getUsers() {
        $query = "SELECT id, email FROM users"; 
        $result = $this->db->executeQuery($query);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            // User-Objekt mit id und email erzeugen
            $users[] = new User($row['id'], $row['email']);
        }
        return $users;
    }

    public function addUser($user) {
        // ACHTUNG: In deinem User-Objekt existiert anscheinend kein $password-Property,
        // besser das Passwort als Parameter übergeben oder in User-Klasse implementieren
        $query = "INSERT INTO users (email, password) VALUES (?, ?)";
        $params = [$user->email, password_hash($user->password, PASSWORD_BCRYPT)];
        return $this->db->executeQuery($query, $params);
    }
}

// JSON-API für Registrierung
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $handler = new DataHandler();

    if ($_POST['action'] === 'register') {
        $user = new User();
        // Nutze die User::register Methode mit username, email und passwort
        $success = $user->register($_POST['username'], $_POST['email'], $_POST['password']);
        if ($success) {
            echo json_encode(["message" => "Registrierung erfolgreich!"]);
        } else {
            echo json_encode(["message" => "Registrierung fehlgeschlagen. Schau bitte ins error log."]);
        }
        exit;
    }
}
?>
