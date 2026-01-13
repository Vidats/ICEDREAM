<?php
require_once '../Controller/giohang.php';
include 'header.php';

$cart_items = getCartData();
$giohangModel = new GiohangModel($GLOBALS['conn']);
?>

<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>
    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">Giỏ hàng của bạn đang trống. <a href="sanpham.php">Tiếp tục mua sắm</a></div>
    <?php else: ?>
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
            <tfoot class="table-light">
                <tr>
                    <th colspan="4" class="text-end">Tổng cộng:</th>
                    <th class="fs-5 text-danger"><?php echo number_format($giohangModel->getTotalPrice($cart_items), 0, ',', '.'); ?>đ</th>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <form action="../Controller/giohang.php" method="post">
                <button type="submit" name="clear_cart" class="btn btn-outline-danger" onclick="return confirm('Xóa toàn bộ giỏ hàng?')">Xóa giỏ hàng</button>
            </form>
            <a href="order.php" class="btn btn-primary px-5 shadow-sm" style="border-radius: 25px;">Tiến hành đặt hàng</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>