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
            <form action="../Controller/order.php" method="POST">
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
                <div class="alert alert-secondary text-end border-0" style="background: #f8f9fa;">
                    Tổng thanh toán: <b class="text-danger fs-4"><?= number_format($total, 0, ',', '.') ?>đ</b>
                </div>
                <button type="submit" name="place_order" class="btn btn-lg w-100" style="background: #ff85a2; color: white; border-radius: 25px; border: none;">
                    XÁC NHẬN ĐẶT HÀNG
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>