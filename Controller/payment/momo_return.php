<?php
session_start();
require_once("./config_momo.php");
require_once('../../Model/db.php');
require_once('../../Model/order.php');
require_once('../../Model/giohang.php');

// Lấy dữ liệu MoMo trả về. Dữ liệu có thể được gửi qua GET (redirect) hoặc POST (IPN).
$responseData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
if (empty($responseData)) {
    die('Không nhận được dữ liệu trả về từ MoMo.');
}

$partnerCode = $responseData['partnerCode'] ?? '';
$orderId = $responseData['orderId'] ?? ''; // Đây là orderId duy nhất gửi cho MoMo
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

// Tạo chuỗi để kiểm tra chữ ký
$rawHash = "accessKey=" . $momo_accessKey .
    "&amount=" . $amount .
    "&extraData=" . $extraData .
    "&ipnUrl=" . $momo_notifyUrl .
    "&orderId=" . $orderId .
    "&orderInfo=" . $orderInfo .
    "&partnerCode=" . $partnerCode .
    "&redirectUrl=" . $momo_returnUrl .
    "&requestId=" . $requestId .
    "&requestType=captureWallet";

$localSignature = hash_hmac("sha256", $rawHash, $momo_secretKey);

// Bắt đầu xử lý logic
$orderModel = new OrderModel($conn);
$giohangModel = new GiohangModel($conn);

if ($localSignature == $momoSignature) {
    // Giải mã extraData để lấy ID đơn hàng gốc
    $extraData_decoded = json_decode(base64_decode($extraData), true);
    if (!isset($extraData_decoded['order_id'])) {
        die("Lỗi: Không tìm thấy mã đơn hàng gốc trong dữ liệu MoMo trả về.");
    }
    $db_order_id = $extraData_decoded['order_id'];
    
    $order = $orderModel->getOrderById($db_order_id);

    if ($order) {
        // Xử lý theo IPN (POST request) là an toàn nhất
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             if ($resultCode == 0 && $order['status'] == 'Chờ thanh toán') {
                $orderModel->updateOrderStatus($db_order_id, 'Đang xử lý');
                $orderDetails = $orderModel->getOrderDetails($db_order_id);
                foreach ($orderDetails as $item) {
                    $orderModel->decreaseProductQuantity($item['product_id'], $item['quantity']);
                }
             }
             http_response_code(204); // Phản hồi cho MoMo server rằng đã nhận được IPN
             exit();
        }

        // Xử lý cho người dùng (GET request)
        if ($resultCode == 0) {
            if ($order['status'] == 'Chờ thanh toán') {
                $orderModel->updateOrderStatus($db_order_id, 'Đang xử lý');
                $orderDetails = $orderModel->getOrderDetails($db_order_id);
                foreach ($orderDetails as $item) {
                    $orderModel->decreaseProductQuantity($item['product_id'], $item['quantity']);
                }
                $giohangModel->clearCart();

                $_SESSION['swal_type'] = 'success';
                $_SESSION['swal_title'] = 'Thanh toán thành công!';
                $_SESSION['swal_message'] = 'Cảm ơn bạn đã tin tưởng chọn Icedream!';
                $_SESSION['swal_order_success'] = true;
                header('Location: ../../View/my_order.php');
                exit();
            } else {
                $_SESSION['swal_type'] = 'info';
                $_SESSION['swal_title'] = 'Thông báo';
                $_SESSION['swal_message'] = 'Đơn hàng này đã được xác nhận thanh toán trước đó.';
                header('Location: ../../View/my_order.php');
                exit();
            }
        } else {
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
    // Nếu là IPN request, không nên hiển thị lỗi ra màn hình
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        http_response_code(400); // Bad Request
        exit();
    }
    echo "<h3>Chữ ký không hợp lệ</h3>";
}
