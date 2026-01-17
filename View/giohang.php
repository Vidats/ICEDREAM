<?php
require_once '../Controller/giohang.php';
include 'header.php';

$cart_items = getCartData();
$giohangModel = new GiohangModel($GLOBALS['conn']);

// Tính toán giá trị
$total_price = $giohangModel->getTotalPrice($cart_items);
$discount_amount = 0;
$final_price = $total_price;

// Check coupon trong session
if (isset($_SESSION['coupon'])) {
    $discount_percent = $_SESSION['coupon']['percent'];
    $discount_amount = $total_price * ($discount_percent / 100);
    $final_price = $total_price - $discount_amount;
}

// Lấy danh sách mã giảm giá phù hợp để gợi ý
$suggested_coupons = getSuggestedCoupons($total_price);
?>

<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>

    <!-- Thông báo lỗi/thành công của Coupon -->
    <?php if (isset($_SESSION['coupon_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['coupon_error']; unset($_SESSION['coupon_error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['coupon_success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['coupon_success']; unset($_SESSION['coupon_success']); ?></div>
    <?php endif; ?>

 

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">Giỏ hàng của bạn đang trống. <a href="sanpham.php">Tiếp tục mua sắm</a></div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><img src="../image/<?php echo $item['hinh']; ?>" width="70" class="rounded shadow-sm"></td>
                                <td><span class="fw-bold"><?php echo htmlspecialchars($item['tensp']); ?></span></td>
                                <td><?php echo number_format($item['gia'], 0, ',', '.'); ?>đ</td>
                                <td>
                                    <div class="input-group input-group-sm" style="max-width: 120px;">
                                        <a href="../Controller/giohang.php?action=decrease&id=<?php echo $item['id']; ?>" class="btn btn-outline-secondary">-</a>
                                        
                                        <input type="text" class="form-control text-center shadow-none" value="<?php echo $item['soluong']; ?>" readonly>
                                        
                                        <a href="../Controller/giohang.php?action=increase&id=<?php echo $item['id']; ?>" class="btn btn-outline-secondary">+</a>
                                    </div>
                                </td>
                                <td class="text-danger fw-bold"><?php echo number_format($item['gia'] * $item['soluong'], 0, ',', '.'); ?>đ</td>
                                <td class="text-center">
                                    <a href="../Controller/giohang.php?action=remove&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Tổng đơn hàng</h5>
                        
                        <!-- Form nhập mã giảm giá -->
                        <form action="../Controller/giohang.php" method="post" class="mb-3">
                            <label class="form-label text-muted small">Mã giảm giá</label>
                            <div class="input-group mb-2">
                                <input type="text" name="coupon_code" class="form-control" placeholder="Nhập mã..." value="<?= isset($_SESSION['coupon']) ? $_SESSION['coupon']['code'] : '' ?>" <?= isset($_SESSION['coupon']) ? 'readonly' : '' ?>>
                                <?php if (isset($_SESSION['coupon'])): ?>
                                    <button class="btn btn-danger" type="submit" name="remove_coupon">Hủy</button>
                                <?php else: ?>
                                    <button class="btn btn-primary" type="submit" name="apply_coupon">Áp dụng</button>
                                <?php endif; ?>
                            </div>
                        </form>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span class="fw-bold"><?php echo number_format($total_price, 0, ',', '.'); ?>đ</span>
                        </div>

                        <?php if ($discount_amount > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Giảm giá (<?= $_SESSION['coupon']['percent'] ?>%):</span>
                            <span class="fw-bold">-<?php echo number_format($discount_amount, 0, ',', '.'); ?>đ</span>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold">Tổng cộng:</span>
                            <span class="fs-5 text-danger fw-bold"><?php echo number_format($final_price, 0, ',', '.'); ?>đ</span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="order.php" class="btn btn-primary btn-lg shadow-sm" style="border-radius: 25px;">Tiến hành đặt hàng</a>
                            
                            <form action="../Controller/giohang.php" method="post" class="d-grid">
                                <button type="submit" name="clear_cart" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Xóa toàn bộ giỏ hàng?')">Xóa giỏ hàng</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>