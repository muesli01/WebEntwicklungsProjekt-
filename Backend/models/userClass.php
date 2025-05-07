<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class User {
    private $db;
    public $id;
    public $email;

    public function __construct($id = null, $email = null) {
        $this->db = new DBAccess();
        $this->id = $id;
        $this->email = $email;
    }
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
            'user' 
        ];
    
        return $this->db->executeQuery($query, $params);
    }
    public function getUserById($userId) {
        $query = "SELECT anrede, vorname, nachname, adresse, plz, ort, email, username, zahlung FROM users WHERE id = ?";
        $params = [$userId];
        $result = $this->db->executeQuery($query, $params);
    
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }
    public function getUserFullById($userId) {
        $query = "SELECT * FROM users WHERE id = ?";
        $params = [$userId];
        $result = $this->db->executeQuery($query, $params);
    
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }
    public function updateUserProfile($userId, $vorname, $nachname, $adresse, $zahlung) {
        $query = "UPDATE users SET vorname = ?, nachname = ?, adresse = ?, zahlung = ? WHERE id = ?";
        $params = [$vorname, $nachname, $adresse, $zahlung, $userId];
    
        return $this->db->executeQuery($query, $params);
    }
    
    
    
    

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $params = [$username, $email, $hashedPassword];

        return $this->db->executeQuery($query, $params);
    }

    public function login($emailOrUsername, $password) { 
        $query = "SELECT * FROM users WHERE (email = ? OR username = ?) AND active = 1";
        $params = [$emailOrUsername, $emailOrUsername];
        $result = $this->db->executeQuery($query, $params);
    
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                
                return [
                    "id" => $row['id'],
                    "username" => $row['username'],
                    "rolle" => $row['rolle']  
                ];
            }
        }
    
        return false;
    }
    
    
    // Alle Kunden holen
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

// Kundenstatus aktiv/inaktiv umschalten
public function toggleCustomerActive($id) {
    // Zuerst den aktuellen Status holen
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

    
    

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
    }
}
?>
