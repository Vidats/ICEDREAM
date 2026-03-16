<?php
session_start();
require_once("./config_momo.php");
require_once('../../Model/db.php');
require_once('../../Model/order.php');
require_once('../../Model/giohang.php');

// Lấy dữ liệu MoMo trả về.
$responseData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
if (empty($responseData)) {
    die('Không nhận được dữ liệu trả về từ MoMo.');
}

$partnerCode = $responseData['partnerCode'] ?? '';
$orderId = $responseData['orderId'] ?? ''; 
$requestId = $responseData['requestId'] ?? '';
$amount = $responseData['amount'] ?? '';
$orderInfo = $responseData['orderInfo'] ?? '';
$orderType = $responseData['orderType'] ?? '';
$transId = $responseData['transId'] ?? '';
$resultCode = $responseData['resultCode'] ?? '';
$message = $responseData['message'] ?? '';
$payType = $responseData['payType'] ?? '';
$responseTime = $responseData['responseTime'] ?? '';
$extraData = $responseData['extraData'] ?? '';
$momoSignature = $responseData['signature'] ?? '';

// Tạo chuỗi để kiểm tra chữ ký (Sắp xếp theo thứ tự bảng chữ cái key)
$rawHash = "accessKey=" . $momo_accessKey .
    "&amount=" . $amount .
    "&extraData=" . $extraData .
    "&message=" . $message .
    "&orderId=" . $orderId .
    "&orderInfo=" . $orderInfo .
    "&orderType=" . $orderType .
    "&partnerCode=" . $partnerCode .
    "&payType=" . $payType .
    "&requestId=" . $requestId .
    "&responseTime=" . $responseTime .
    "&resultCode=" . $resultCode .
    "&transId=" . $transId;

$localSignature = hash_hmac("sha256", $rawHash, $momo_secretKey);

$orderModel = new OrderModel($conn);
$giohangModel = new GiohangModel($conn);

if ($localSignature == $momoSignature) {
    // Giải mã extraData để lấy ID đơn hàng gốc
    $extraData_decoded = json_decode(base64_decode($extraData), true);
    $db_order_id = $extraData_decoded['order_id'] ?? null;
    
    if (!$db_order_id) {
        die("Lỗi: Không tìm thấy mã đơn hàng gốc trong dữ liệu MoMo.");
    }
    
    $order = $orderModel->getOrderById($db_order_id);

    if ($order) {
        // Xử lý IPN (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             if ($resultCode == 0 && $order['status'] == 'Đang xử lý') {
                // Chỉ giảm số lượng sản phẩm nếu đơn hàng đang ở trạng thái 'Đang xử lý'
                $orderDetails = $orderModel->getOrderDetails($db_order_id);
                foreach ($orderDetails as $item) {
                    $orderModel->decreaseProductQuantity($item['product_id'], $item['quantity']);
                }
                // Có thể cập nhật một flag 'is_paid' nếu DB có, ở đây ta giữ nguyên trạng thái
             }
             http_response_code(204);
             exit();
        }

        // Xử lý Redirect (GET)
        if ($resultCode == 0) {
            // Khôi phục phiên đăng nhập nếu bị mất
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = $order['user_id'];
            }

            // 1. Luôn xóa giỏ hàng khi thanh toán thành công
            $giohangModel->clearCart();

            // 2. Kiểm tra nếu đơn hàng chưa được xử lý (tránh IPN xử lý trùng)
            if ($order['status'] == 'Đang xử lý') {
                $orderDetails = $orderModel->getOrderDetails($db_order_id);
                foreach ($orderDetails as $item) {
                    $orderModel->decreaseProductQuantity($item['product_id'], $item['quantity']);
                }
                // Cập nhật trạng thái đơn hàng để đánh dấu đã xử lý
                $orderModel->updateOrderStatus($db_order_id, 'Đang xử lý');
            }

            $_SESSION['swal_type'] = 'success';
            $_SESSION['swal_title'] = 'Thanh toán thành công!';
            $_SESSION['swal_message'] = 'Cảm ơn bạn đã tin tưởng chọn Icedream!';
            $_SESSION['swal_order_success'] = true;
            header('Location: ../../View/my_order.php');
            exit();
        } else {
            // Thanh toán thất bại hoặc bị hủy
            $orderModel->updateOrderStatus($db_order_id, 'Thanh toán thất bại');
            $_SESSION['swal_type'] = 'error';
            $_SESSION['swal_title'] = 'Thanh toán thất bại';
            $_SESSION['swal_message'] = 'Lỗi: ' . $message;
            header('Location: ../../View/giohang.php');
            exit();
        }
    } else {
        die("Lỗi: Không tìm thấy đơn hàng với ID: " . $db_order_id);
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        http_response_code(400);
        exit();
    }
    // Giao diện thông báo lỗi chữ ký cho người dùng
    echo "<div style='text-align: center; margin-top: 50px;'>";
    echo "<h3>Chữ ký không hợp lệ</h3>";
    echo "<p>Có lỗi xảy ra khi xác thực giao dịch từ MoMo. Vui lòng liên hệ hỗ trợ.</p>";
    echo "<a href='../../View/index.php'>Quay về trang chủ</a>";
    echo "</div>";
}
