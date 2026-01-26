<?php
session_start();
require_once '../Model/db.php';
require_once '../Model/order.php';
require_once '../Model/giohang.php';

$orderModel = new OrderModel($conn);
$giohangModel = new GiohangModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../View/auth.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $items = $giohangModel->getCartItems(); 
    $total_price = $giohangModel->getTotalPrice($items); 

    $order_id = $orderModel->createOrder($user_id, $full_name, $email, $address, $total_price);

    if ($order_id) {
        // Giảm số lượng sản phẩm trong kho sau khi đặt hàng thành công
        // VÀ lưu chi tiết đơn hàng vào bảng order_details
        foreach ($items as $item) {
            $orderModel->decreaseProductQuantity($item['id'], $item['soluong']);
            // Lưu chi tiết: order_id, product_id, price (gia), quantity (soluong)
            $orderModel->addOrderDetail($order_id, $item['id'], $item['gia'], $item['soluong']);
        }
        
        $giohangModel->clearCart(); 
        
        $_SESSION['swal_type'] = 'success';
        $_SESSION['swal_title'] = 'Đặt hàng thành công!';
        $_SESSION['swal_message'] = 'Cảm ơn bạn đã tin tưởng chọn Icedream!';
        $_SESSION['swal_order_success'] = true; // Flag for Lottie animation
        $_SESSION['swal_redirect'] = 'my_order.php';
        
        header('Location: ../View/my_order.php');
        exit();
    } else {
        $_SESSION['swal_type'] = 'error';
        $_SESSION['swal_title'] = 'Lỗi!';
        $_SESSION['swal_message'] = 'Lỗi hệ thống khi đặt hàng: ' . $conn->error;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>