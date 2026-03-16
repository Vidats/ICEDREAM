<?php
session_start();
require_once '../Model/sanpham.php';
require_once '../Model/giohang.php';
require_once '../Model/db.php';

$sanphamModel = new SanphamModel($conn);
$giohangModel = new GiohangModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth.php?tab=login&status=error&message=Vui lòng đăng nhập!');
        exit();
    }

    $id = intval($_POST['id']);
    $soluong = intval($_POST['quantity']);

    if ($soluong > 0) {
        if ($giohangModel->addToCart($id, $soluong)) {
            $_SESSION['swal_type'] = 'success';
            $_SESSION['swal_title'] = 'Thành công!';
            $_SESSION['swal_message'] = 'Đã thêm sản phẩm vào giỏ hàng!';
        } else {
            $_SESSION['swal_type'] = 'error';
            $_SESSION['swal_title'] = 'Thất bại!';
            $_SESSION['swal_message'] = 'Không đủ số lượng sản phẩm trong kho!';
        }
        
        // Redirect back to the same page (detail page)
        header('Location: ' . $_SERVER['HTTP_REFERER']);
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

    // Lấy danh sách sản phẩm gợi ý (AI/Recommendation)
$recommendations = $sanphamModel->getRecommendedProducts($product['id'], $product['category_id']);} else {
    die("Không tìm thấy ID sản phẩm.");
}
?>