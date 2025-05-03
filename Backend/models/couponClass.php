<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class Coupon {
    private $db;

    public function __construct() {
        $this->db = new DBAccess();
    }

    // Gutschein erstellen
    public function createCoupon($code, $wert, $gueltigBis) {
        $query = "INSERT INTO coupons (code, wert, gueltig_bis) VALUES (?, ?, ?)";
        $params = [$code, $wert, $gueltigBis];
        return $this->db->executeQuery($query, $params);
    }

    // Alle Gutscheine abrufen
    public function getAllCoupons() {
        $query = "SELECT * FROM coupons ORDER BY gueltig_bis ASC";
        $result = $this->db->executeQuery($query);

        $coupons = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $coupons[] = $row;
            }
        }
        return $coupons;
    }

    // Gutscheinstatus aktualisieren (z.B. eingelöst oder abgelaufen)
    public function updateCouponStatus($couponId, $status) {
        $query = "UPDATE coupons SET status = ? WHERE id = ?";
        $params = [$status, $couponId];
        return $this->db->executeQuery($query, $params);
    }

    // Gutschein anhand Code abrufen (optional später bei Einlösung)
    public function getCouponByCode($code) {
        $query = "SELECT * FROM coupons WHERE code = ?";
        $params = [$code];
        $result = $this->db->executeQuery($query, $params);
        return $result->fetch_assoc();
    }
}
?>
