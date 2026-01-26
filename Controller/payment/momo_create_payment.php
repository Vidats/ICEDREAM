<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("./config_momo.php");
require_once('../../Model/db.php');
require_once('../../Model/order.php');
require_once('../../Model/giohang.php');

// 1. Check Login & POST data
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../View/auth.php');
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

// 5. Chuẩn bị dữ liệu gửi sang MoMo
$db_order_id = $order_id; // Lưu ID đơn hàng từ DB
$requestId = time() . "";
$orderId = $db_order_id . "_" . $requestId; // Tạo orderId duy nhất cho MoMo
$amount = (string)$total_price;
$orderInfo = "Thanh toán đơn hàng Icedream #" . $db_order_id;
$requestType = "payWithMethod";

// Dữ liệu bổ sung, dùng để lấy lại mã đơn hàng gốc khi MoMo trả về
$extraData = base64_encode(json_encode(['order_id' => $db_order_id]));

// Tạo chuỗi để ký
$rawHash = "accessKey=" . $momo_accessKey .
    "&amount=" . $amount .
    "&extraData=" . $extraData .
    "&ipnUrl=" . $momo_notifyUrl .
    "&orderId=" . $orderId .
    "&orderInfo=" . $orderInfo .
    "&partnerCode=" . $momo_partnerCode .
    "&redirectUrl=" . $momo_returnUrl .
    "&requestId=" . $requestId .
    "&requestType=" . $requestType;

// Ký chuỗi bằng HMAC_SHA256
$signature = hash_hmac("sha256", $rawHash, $momo_secretKey);

// Dữ liệu body của request
$requestBody = json_encode([
    'partnerCode' => $momo_partnerCode,
    'accessKey' => $momo_accessKey,
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $momo_returnUrl,
    'ipnUrl' => $momo_notifyUrl,
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature,
    'lang' => 'vi'
]);


// Gửi yêu cầu cURL đến MoMo
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $momo_endpoint);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$jsonResponse = json_decode($response, true);

// Xử lý kết quả trả về
if (isset($jsonResponse['resultCode']) && $jsonResponse['resultCode'] == 0) {
    header('Location: ' . $jsonResponse['payUrl']);
    exit();
} else {
    echo "<h3>Lỗi khi tạo thanh toán MoMo</h3>";
    echo "<p>Message: " . ($jsonResponse['message'] ?? 'Không nhận được phản hồi') . "</p>";
    echo "<p>Response: " . $response . "</p>";
}
