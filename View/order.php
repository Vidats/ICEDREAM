<?php 
require_once '../Controller/giohang.php'; 
include 'header.php'; 

$cart_items = getCartData();
$giohangModel = new GiohangModel($GLOBALS['conn']);
$total = $giohangModel->getTotalPrice($cart_items);
?>

<div class="container py-5">
    <div class="card mx-auto shadow-sm" style="max-width: 600px; border-radius: 15px;">
        <div class="card-body p-4">
            <h2 class="text-center mb-4" style="color: #ff85a2;">Thông tin giao hàng</h2>
            <form id="orderForm" action="../Controller/order.php" method="POST">
                <div class="mb-3">
                    <label class="fw-bold">Họ và tên</label>
                    <input type="text" name="full_name" class="form-control shadow-none" 
                           value="<?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Email</label>
                    <input type="email" name="email" class="form-control shadow-none" placeholder="Địa chỉ email nhận hóa đơn" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Địa chỉ nhận hàng</label>
                    <textarea name="address" class="form-control shadow-none" rows="3" placeholder="Số nhà, tên đường, phường/xã..." required></textarea>
                </div>
                
                <!-- Hidden total amount -->
                <input type="hidden" name="total_amount" value="<?= $total ?>">

                <!-- Payment Method Selection -->
                <h4 class="mt-4 mb-3 fw-bold fs-5">Chọn phương thức thanh toán</h4>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked>
                    <label class="form-check-label" for="payment_cod">
                        Thanh toán khi nhận hàng (COD)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_vnpay" value="vnpay">
                    <label class="form-check-label" for="payment_vnpay">
                        Thanh toán qua VNPay
                    </label>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_momo" value="momo">
                    <label class="form-check-label" for="payment_momo">
                        Thanh toán qua MoMo
                    </label>
                </div>

                <div class="alert alert-secondary text-end border-0" style="background: #f8f9fa;">
                    Tổng thanh toán: <b class="text-danger fs-4"><?= number_format($total, 0, ',', '.') ?>đ</b>
                </div>

                <button type="submit" name="place_order" class="btn btn-lg w-100" style="background: #ff85a2; color: white; border-radius: 25px; border: none;">
                    HOÀN TẤT ĐƠN HÀNG
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderForm = document.getElementById('orderForm');
        
        orderForm.addEventListener('submit', function(e) {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (selectedMethod === 'vnpay') {
                orderForm.action = '../Controller/payment/vnpay_create_payment.php';
            } else if (selectedMethod === 'momo') {
                orderForm.action = '../Controller/payment/momo_create_payment.php';
            } 
            else {
                orderForm.action = '../Controller/order.php';
            }
        });
    });
</script>

<?php include 'footer.php'; ?>