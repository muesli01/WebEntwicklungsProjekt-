<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class User {
    private $db;

    public function __construct() {
        $this->db = new DBAccess();
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
    
    

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $params = [$username, $email, $hashedPassword];

        return $this->db->executeQuery($query, $params);
    }

    public function login($emailOrUsername, $password) {
        
        $query = "SELECT id, username, password FROM users WHERE email = ? OR username = ?";
        $params = [$emailOrUsername, $emailOrUsername];
        $result = $this->db->executeQuery($query, $params);
    
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                return true;
            }
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
