<?php
require_once(__DIR__ . '/../config/dbaccess.php');

class Review {
    private $db;

    public function __construct() {
        $this->db = new DBAccess();
    }

    // Добавить отзыв
    public function createReview($userId, $productId, $orderId, $rating, $comment) {
        $query = "INSERT INTO product_reviews (user_id, product_id, order_id, rating, comment) VALUES (?, ?, ?, ?, ?)";
        $params = [$userId, $productId, $orderId, $rating, $comment];
        return $this->db->executeQuery($query, $params);
    }

    // Проверить, оставил ли пользователь отзыв на этот продукт в рамках этого заказа
    public function hasUserReviewed($userId, $productId, $orderId) {
        $query = "SELECT id FROM product_reviews WHERE user_id = ? AND product_id = ? AND order_id = ?";
        $params = [$userId, $productId, $orderId];
        $result = $this->db->executeQuery($query, $params);
        return ($result && $result->num_rows > 0);
    }

    // Получить все отзывы для продукта
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
    public function getReviewForOrderItem($productId, $orderId) {
    $query = "SELECT rating, comment FROM product_reviews WHERE product_id = ? AND order_id = ?";
    $params = [$productId, $orderId];
    $result = $this->db->executeQuery($query, $params);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}
public function getUserReview($userId, $productId, $orderId) {
    // echo "Getting review for user=$userId, product=$productId, order=$orderId";
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
