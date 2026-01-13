<?php
session_start();
require_once '../Model/sanpham.php';
require_once '../Model/giohang.php';
require_once '../Model/db.php';

$sanphamModel = new SanphamModel($conn);
$giohangModel = new GiohangModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: form.php?tab=login&status=error&message=Vui lòng đăng nhập!');
        exit();
    }

    $id = intval($_POST['id']);
    $soluong = intval($_POST['quantity']);

    if ($soluong > 0) {
        $giohangModel->addToCart($id, $soluong); 
        header('Location: giohang.php');
        exit();
    }
}

$product = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $product = $sanphamModel->getProductById($id);
    
    if (!$product) {
        die("Sản phẩm không tồn tại.");
    }
} else {
    die("Không tìm thấy ID sản phẩm.");
}
?>