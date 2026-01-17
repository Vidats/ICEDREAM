<?php
session_start();
require_once 'header.php';
require_once '../Model/db.php';
require_once '../Model/order.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['swal_type'] = 'warning';
    $_SESSION['swal_title'] = 'Thông báo';
    $_SESSION['swal_message'] = 'Vui lòng đăng nhập!';
    $_SESSION['swal_redirect'] = 'form.php';
    header("Location: form.php");
    exit();
}

$order_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];
$orderModel = new OrderModel($conn);

// Lấy thông tin đơn hàng
$order = $orderModel->getOrderById($order_id);

// Kiểm tra quyền xem đơn hàng (chỉ xem đơn của chính mình)
if (!$order || $order['user_id'] != $user_id) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Đơn hàng không tồn tại hoặc bạn không có quyền xem.</div></div>";
    include 'footer.php';
    exit();
}

// Lấy chi tiết sản phẩm trong đơn
$details = $orderModel->getOrderDetails($order_id);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-pink" style="color: #ff85a2;">
            <i class="fas fa-file-invoice"></i> Chi tiết đơn hàng #<?= $order_id ?>
        </h2>
        <a href="my_order.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>

    <!-- Thông tin chung -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Thông tin giao hàng</h5>
                    <p class="mb-1"><strong>Người nhận:</strong> <?= htmlspecialchars($order['full_name']) ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($order['email']) // Lưu ý: Database dùng cột email để lưu SDT hoặc Email tuỳ form? Trong form order.php có vẻ lưu cả 2 hoặc dùng chung. Tạm hiển thị cột email ?></p>
                    <p class="mb-1"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="card-title">Trạng thái đơn hàng</h5>
                    <p class="mb-1"><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                    <p class="mb-1"><strong>Trạng thái:</strong> 
                        <?php 
                            $status = $order['status'];
                            $badgeClass = 'bg-warning text-dark';
                            if ($status == 'Đang giao') $badgeClass = 'bg-primary';
                            if ($status == 'Hoàn thành') $badgeClass = 'bg-success';
                            if ($status == 'Đã hủy') $badgeClass = 'bg-danger';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Danh sách sản phẩm</h5>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 100px;">Hình ảnh</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col" class="text-center">Số lượng</th>
                        <th scope="col" class="text-end">Đơn giá</th>
                        <th scope="col" class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($details) > 0): ?>
                        <?php foreach ($details as $item): ?>
                            <tr>
                                <td>
                                    <img src="../image/<?= htmlspecialchars($item['image']) ?>" 
                                         alt="<?= htmlspecialchars($item['name']) ?>" 
                                         class="img-fluid rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                </td>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td class="text-center"><?= $item['quantity'] ?></td>
                                <td class="text-end"><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                <td class="text-end fw-bold"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <em>Không tìm thấy chi tiết sản phẩm cho đơn hàng này (có thể là đơn hàng cũ trước khi cập nhật hệ thống).</em>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                        <td class="text-end fw-bold text-danger fs-5"><?= number_format($order['total_price'], 0, ',', '.') ?>đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>