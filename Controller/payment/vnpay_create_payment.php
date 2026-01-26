<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("./config_vnpay.php");
require_once('../../Model/db.php');
require_once('../../Model/order.php');
require_once('../../Model/giohang.php');

// 1. Check Login & POST data
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../View/auth.php');
    exit();
}

// 2. Lấy thông tin từ Form và Giỏ hàng
$orderModel = new OrderModel($conn);
$giohangModel = new GiohangModel($conn);

$user_id = $_SESSION['user_id'];
$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$address = mysqli_real_escape_string($conn, $_POST['address']);

$items = $giohangModel->getCartItems(); 
if (empty($items)) {
    die("Giỏ hàng của bạn đang trống.");
}
$total_price = $giohangModel->getTotalPrice($items); 

// 3. Tạo đơn hàng với trạng thái "Đang xử lý"
$status = 'Đang xử lý';
$order_id = $orderModel->createOrder($user_id, $full_name, $email, $address, $total_price, $status);

if (!$order_id) {
    die("Lỗi: Không thể tạo đơn hàng trong cơ sở dữ liệu.");
}

// 4. Lưu chi tiết đơn hàng
foreach ($items as $item) {
    $orderModel->addOrderDetail($order_id, $item['id'], $item['gia'], $item['soluong']);
}

// 5. Chuẩn bị dữ liệu gửi sang VNPAY
$vnp_TxnRef = $order_id; //Sử dụng ID đơn hàng làm mã tham chiếu
$vnp_OrderInfo = "Thanh toan don hang #$order_id";
$vnp_OrderType = 'billpayment';
$vnp_Amount = $total_price * 100;
$vnp_Locale = 'vn';
$vnp_BankCode = 'NCB'; // Có thể để trống để khách chọn
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef,
);

if (isset($vnp_BankCode) && $vnp_BankCode != "") {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}
header('Location: ' . $vnp_Url);
die();
