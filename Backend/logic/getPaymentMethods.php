<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

require_once '../models/paymentClass.php';
$userId = $_SESSION['user_id'];

$payment = new Payment();
$methods = $payment->getSavedMethods($userId);

require_once '../models/userClass.php';
$userModel = new User();
$userData  = $userModel->getUserById($userId);
if (!empty($userData['zahlung'])) {
    array_unshift($methods, ['id'=>0, 'name'=>'Standard', 'details'=>$userData['zahlung']]);
}


echo json_encode(['success' => true, 'methods' => $methods]);
