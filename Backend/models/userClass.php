<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class User {
    private $db;
    public $id;
    public $email;

    public function __construct($id = null, $email = null) {
        // DBAccess-Instanz für DB-Verbindung
        $this->db = new DBAccess();
        $this->id = $id;
        $this->email = $email;
    }

    /**
     * Registrierung mit erweiterten Nutzerdaten
     */
    public function registerExtended($anrede, $vorname, $nachname, $adresse, $plz, $ort, $email, $username, $password, $zahlung) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $query = "INSERT INTO users (anrede, vorname, nachname, adresse, plz, ort, email, username, password, zahlung, rolle) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        $params = [
            $anrede,
            $vorname,
            $nachname,
            $adresse,
            $plz,
            $ort,
            $email,
            $username,
            $hashedPassword,
            $zahlung,
            'user' // Standardrolle
        ];
    
        return $this->db->executeQuery($query, $params);
    }

    /**
     * Nutzerinformationen (ohne Passwort) anhand der ID abrufen
     */
    public function getUserById($userId) {
        $query = "SELECT anrede, vorname, nachname, adresse, plz, ort, email, username, zahlung FROM users WHERE id = ?";
        $params = [$userId];
        $result = $this->db->executeQuery($query, $params);
    
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    /**
     * Alle Nutzerdaten (inkl. Passwort, Rollen etc.) anhand der ID abrufen
     */
    public function getUserFullById($userId) {
        $query = "SELECT * FROM users WHERE id = ?";
        $params = [$userId];
        $result = $this->db->executeQuery($query, $params);
    
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    /**
     * Nutzerprofil (bestimmte Felder) aktualisieren
     */
    public function updateUserProfile($userId, $vorname, $nachname, $adresse, $zahlung) {
        $query = "UPDATE users SET vorname = ?, nachname = ?, adresse = ?, zahlung = ? WHERE id = ?";
        $params = [$vorname, $nachname, $adresse, $zahlung, $userId];
    
        return $this->db->executeQuery($query, $params);
    }

    /**
     * Einfache Registrierung (nur Nutzername, Email, Passwort)
     */
    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $params = [$username, $email, $hashedPassword];

        return $this->db->executeQuery($query, $params);
    }

    /**
     * Nutzer-Login mit Email oder Benutzername + Passwort
     */
    public function login($emailOrUsername, $password) { 
        $query = "SELECT * FROM users WHERE (email = ? OR username = ?) AND active = 1";
        $params = [$emailOrUsername, $emailOrUsername];
        $result = $this->db->executeQuery($query, $params);
    
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // Login erfolgreich - Nutzerdaten zurückgeben
                return [
                    "id" => $row['id'],
                    "username" => $row['username'],
                    "rolle" => $row['rolle']  
                ];
            }
        }
    
        return false;
    }

    /**
     * Alle Kunden (Rolle 'user') abrufen
     */
    public function getAllCustomers() {
        $query = "SELECT id, username, email, active FROM users WHERE rolle = 'user'";
        $result = $this->db->executeQuery($query);
        $customers = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }

        return $customers;
    }

    /**
     * Kundenstatus (aktiv/inaktiv) umschalten
     */
    public function toggleCustomerActive($id) {
        // Aktuellen Status abfragen
        $query = "SELECT active FROM users WHERE id = ?";
        $params = [$id];
        $result = $this->db->executeQuery($query, $params);

        if ($row = $result->fetch_assoc()) {
            $newStatus = $row['active'] == 1 ? 0 : 1;
            $updateQuery = "UPDATE users SET active = ? WHERE id = ?";
            $updateParams = [$newStatus, $id];
            return $this->db->executeQuery($updateQuery, $updateParams);
        }

        return false;
    }

    /**
     * Prüfen, ob Nutzer eingeloggt ist
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Nutzer ausloggen (Session zerstören)
     */
    public function logout() {
        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
    }
}
?>
