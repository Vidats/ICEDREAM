<?php
session_start();
require_once __DIR__ . '/../Model/giohang.php';
require_once __DIR__ . '/../Model/db.php';

$giohangModel = new GiohangModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear_cart'])) {
        $giohangModel->clearCart();
    }
    header('Location: ../View/giohang.php');
    exit();
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    if ($action == 'increase' || $action == 'decrease') {
        $giohangModel->updateQuantity($id, $action);
    } elseif ($action == 'remove') {
        $giohangModel->removeItem($id);
    }
    header('Location: ../View/giohang.php');
    exit();
}

function getCartData() {
    global $giohangModel;
    return $giohangModel->getCartItems();
}
?>