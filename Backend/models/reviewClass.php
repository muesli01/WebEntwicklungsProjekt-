<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class Review {
    private $db;

    public function __construct() {
        // DBAccess-Instanz für DB-Verbindung
        $this->db = new DBAccess();
    }

    /**
     * Neue Bewertung erstellen
     */
    public function createReview($userId, $productId, $orderId, $rating, $comment) {
        $query = "INSERT INTO product_reviews (user_id, product_id, order_id, rating, comment) VALUES (?, ?, ?, ?, ?)";
        $params = [$userId, $productId, $orderId, $rating, $comment];
        return $this->db->executeQuery($query, $params);
    }

    /**
     * Prüfen, ob der Nutzer für dieses Produkt in der Bestellung schon bewertet hat
     */
    public function hasUserReviewed($userId, $productId, $orderId) {
        $query = "SELECT id FROM product_reviews WHERE user_id = ? AND product_id = ? AND order_id = ?";
        $params = [$userId, $productId, $orderId];
        $result = $this->db->executeQuery($query, $params);
        return ($result && $result->num_rows > 0);
    }

    /**
     * Alle Bewertungen zu einem Produkt abrufen
     */
    public function getReviewsByProductId($productId) {
        $query = "SELECT r.rating, r.comment, r.created_at, u.vorname, u.nachname
                  FROM product_reviews r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.product_id = ?
                  ORDER BY r.created_at DESC";
        $params = [$productId];
        $result = $this->db->executeQuery($query, $params);

        $reviews = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        }

        return $reviews;
    }

    /**
     * Bewertung zu einem Produkt innerhalb einer Bestellung abrufen
     */
    public function getReviewForOrderItem($productId, $orderId) {
        $query = "SELECT rating, comment FROM product_reviews WHERE product_id = ? AND order_id = ?";
        $params = [$productId, $orderId];
        $result = $this->db->executeQuery($query, $params);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    /**
     * Bewertung eines Nutzers für ein Produkt in einer Bestellung abrufen
     */
    public function getUserReview($userId, $productId, $orderId) {
        $query = "SELECT rating, comment FROM product_reviews WHERE user_id = ? AND product_id = ? AND order_id = ?";
        $params = [$userId, $productId, $orderId];
        $result = $this->db->executeQuery($query, $params);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }
}
?>
