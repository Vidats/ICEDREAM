<?php
session_start();
require_once __DIR__ . '/../Model/giohang.php';
require_once __DIR__ . '/../Model/CouponModel.php'; // Add CouponModel
require_once __DIR__ . '/../Model/db.php';

$giohangModel = new GiohangModel($conn);
$couponModel = new CouponModel($conn); // Init CouponModel

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Xử lý thêm vào giỏ hàng từ trang danh sách (sanpham.php) ---
    if (isset($_POST['add_to_cart'])) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../View/auth.php?tab=login&status=error&message=Vui lòng đăng nhập!');
            exit();
        }

        $id = intval($_POST['id']);
        $soluong = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if ($soluong > 0) {
            if ($giohangModel->addToCart($id, $soluong)) {
                $_SESSION['swal_type'] = 'success';
                $_SESSION['swal_title'] = 'Thành công!';
                $_SESSION['swal_message'] = 'Đã thêm vào giỏ hàng!';
            } else {
                $_SESSION['swal_type'] = 'error';
                $_SESSION['swal_title'] = 'Thất bại!';
                $_SESSION['swal_message'] = 'Số lượng trong kho không đủ!';
            }
            
            // Quay lại trang danh sách sản phẩm
            header('Location: ' . $_SERVER['HTTP_REFERER']); 
            exit();
        }
    }
    // -----------------------------------------------------------------

    if (isset($_POST['clear_cart'])) {
        $giohangModel->clearCart();
        unset($_SESSION['coupon']); // Clear coupon when cart is cleared
    }
    
    // Xử lý áp dụng mã giảm giá
    if (isset($_POST['apply_coupon'])) {
        $code = $_POST['coupon_code'] ?? '';
        // Tính tổng tiền hiện tại để kiểm tra điều kiện
        $cart_items = $giohangModel->getCartItems();
        $total = $giohangModel->getTotalPrice($cart_items);
        
        $coupon = $couponModel->checkCoupon($code, $total);
        
        if ($coupon) {
            $_SESSION['coupon'] = [
                'code' => $coupon['code'],
                'percent' => $coupon['discount_percent']
            ];
            unset($_SESSION['coupon_error']);
            $_SESSION['coupon_success'] = "Áp dụng mã giảm giá thành công!";
        } else {
            unset($_SESSION['coupon']);
            $_SESSION['coupon_error'] = "Mã giảm giá không hợp lệ hoặc chưa đủ điều kiện!";
        }
    }

    // Xử lý hủy mã giảm giá
    if (isset($_POST['remove_coupon'])) {
        unset($_SESSION['coupon']);
        unset($_SESSION['coupon_error']);
        $_SESSION['coupon_success'] = "Đã hủy mã giảm giá.";
    }

    header('Location: ../View/giohang.php');
    exit();
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    if ($action == 'increase' || $action == 'decrease') {
        if (!$giohangModel->updateQuantity($id, $action)) {
            $_SESSION['swal_type'] = 'error';
            $_SESSION['swal_title'] = 'Lỗi!';
            $_SESSION['swal_message'] = 'Không thể cập nhật số lượng (Vượt quá tồn kho)!';
        }
        
        // Kiểm tra lại coupon sau khi thay đổi số lượng
        if (isset($_SESSION['coupon'])) {
            $cart_items = $giohangModel->getCartItems();
            $total = $giohangModel->getTotalPrice($cart_items);
            $coupon = $couponModel->checkCoupon($_SESSION['coupon']['code'], $total);
            if (!$coupon) {
                unset($_SESSION['coupon']);
                $_SESSION['coupon_error'] = "Mã giảm giá đã bị hủy do đơn hàng không còn đủ điều kiện!";
            }
        }
    } elseif ($action == 'remove') {
        $giohangModel->removeItem($id);
        
        // Check lại coupon sau khi xóa sp, lỡ tổng tiền giảm xuống dưới mức tối thiểu
        if (isset($_SESSION['coupon'])) {
            $cart_items = $giohangModel->getCartItems();
            $total = $giohangModel->getTotalPrice($cart_items);
            $coupon = $couponModel->checkCoupon($_SESSION['coupon']['code'], $total);
            if (!$coupon) {
                unset($_SESSION['coupon']);
                $_SESSION['coupon_error'] = "Mã giảm giá đã bị hủy do đơn hàng không còn đủ điều kiện!";
            }
        }
    }
    header('Location: ../View/giohang.php');
    exit();
}

function getCartData() {
    global $giohangModel;
    return $giohangModel->getCartItems();
}

function getSuggestedCoupons($total) {
    global $couponModel;
    return $couponModel->getAvailableCoupons($total);
}
?>