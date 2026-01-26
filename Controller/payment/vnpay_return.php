<?php
session_start();
require_once("./config_vnpay.php");
require_once('../../Model/db.php');
require_once('../../Model/order.php');
require_once('../../Model/giohang.php');

$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Bắt đầu xử lý logic
$orderModel = new OrderModel($conn);
$giohangModel = new GiohangModel($conn);
$order_id = $inputData['vnp_TxnRef'];

if ($secureHash == $vnp_SecureHash) {
    // Lấy thông tin đơn hàng
    $order = $orderModel->getOrderById($order_id);

    if ($order) {
        if ($inputData['vnp_ResponseCode'] == '00') {
            // Chỉ xử lý khi trạng thái là "Chờ thanh toán"
            if ($order['status'] == 'Chờ thanh toán') {
                
                // 1. Cập nhật trạng thái đơn hàng
                $orderModel->updateOrderStatus($order_id, 'Đang xử lý');
                
                // 2. Giảm số lượng tồn kho
                $orderDetails = $orderModel->getOrderDetails($order_id);
                foreach ($orderDetails as $item) {
                    $orderModel->decreaseProductQuantity($item['product_id'], $item['quantity']);
                }

                // 3. Xóa giỏ hàng
                $giohangModel->clearCart();

                // 4. Thiết lập thông báo và chuyển hướng
                $_SESSION['swal_type'] = 'success';
                $_SESSION['swal_title'] = 'Thanh toán thành công!';
                $_SESSION['swal_message'] = 'Cảm ơn bạn đã tin tưởng chọn Icedream!';
                $_SESSION['swal_order_success'] = true;
                $_SESSION['swal_redirect'] = 'my_order.php';
                header('Location: ../../View/my_order.php');
                exit();

            } else {
                // Đơn hàng đã được xử lý trước đó
                $_SESSION['swal_type'] = 'info';
                $_SESSION['swal_title'] = 'Thông báo';
                $_SESSION['swal_message'] = 'Đơn hàng này đã được xác nhận thanh toán trước đó.';
                $_SESSION['swal_redirect'] = 'my_order.php';
                header('Location: ../../View/my_order.php');
                exit();
            }
        } else {
            // Giao dịch thất bại
            $orderModel->updateOrderStatus($order_id, 'Thanh toán thất bại');
            
            // Thiết lập thông báo và chuyển hướng về trang giỏ hàng
            $_SESSION['swal_type'] = 'error';
            $_SESSION['swal_title'] = 'Thanh toán thất bại';
            $_SESSION['swal_message'] = 'Đã có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.';
            $_SESSION['swal_redirect'] = 'giohang.php';
            header('Location: ../../View/giohang.php');
            exit();
        }
    } else {
        echo "<h3>Lỗi: Không tìm thấy đơn hàng</h3>";
    }
} else {
    echo "<h3>Chữ ký không hợp lệ</h3>";
}
