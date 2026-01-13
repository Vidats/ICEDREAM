<?php
require_once 'layouts/header.php'; 
require_once '../model/AdminOrderModel.php'; 

$adminOrderModel = new AdminOrderModel($conn);
$order_id = $_GET['id'] ?? 0;

// Lấy thông tin đơn hàng
$order = $adminOrderModel->getOrderById($order_id);
$details = $adminOrderModel->getOrderDetails($order_id);

// Nếu không tìm thấy đơn hàng
if (!$order) {
    echo "<div class='container-fluid py-4'><div class='alert alert-danger'>Đơn hàng không tồn tại! <a href='quanlydonhang.php'>Quay lại</a></div></div>";
    require_once 'layouts/footer.php'; 
    exit();
}
?>

<div class="container-fluid">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title text-primary"><i class="fas fa-file-invoice me-2"></i>Chi Tiết Đơn Hàng #<?= $order['id'] ?></h2>
        <a href="quanlydonhang.php" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Quay lại danh sách</a>
    </div>

    <!-- Thông tin chung -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary-custom text-white" style="background-color: var(--primary-color);">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-user me-2 text-muted"></i>Người nhận:</strong> <?= htmlspecialchars($order['full_name']) ?></p>
                            <p><strong><i class="fas fa-envelope me-2 text-muted"></i>Email/SĐT:</strong> <?= htmlspecialchars($order['email']) ?></p>
                            <p><strong><i class="fas fa-map-marker-alt me-2 text-muted"></i>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-calendar-alt me-2 text-muted"></i>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                            <p><strong><i class="fas fa-dollar-sign me-2 text-muted"></i>Tổng tiền:</strong> <span class="text-danger fw-bold fs-5"><?= number_format($order['total_price'], 0, ',', '.') ?>đ</span></p>
                            <p><strong><i class="fas fa-tag me-2 text-muted"></i>Trạng thái:</strong> 
                                <?php 
                                    $status = $order['status'];
                                    $badgeClass = 'bg-secondary';
                                    if ($status == 'Đang xử lý') $badgeClass = 'bg-warning text-dark';
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($details) > 0): ?>
                                    <?php foreach ($details as $item): ?>
                                        <tr>
                                            <td class="text-center">
                                                <img src="../../image/<?= htmlspecialchars($item['image']) ?>" 
                                                     alt="<?= htmlspecialchars($item['name']) ?>" 
                                                     class="img-fluid rounded border" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td class="align-middle"><?= htmlspecialchars($item['name']) ?></td>
                                            <td class="align-middle text-center"><?= $item['quantity'] ?></td>
                                            <td class="align-middle text-end"><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                            <td class="align-middle text-end fw-bold"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <em>Không có dữ liệu chi tiết cho đơn hàng này.</em>
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
        </div>

        <!-- Cập nhật trạng thái nhanh -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cập nhật trạng thái</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="quanlydonhang.php">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Chọn trạng thái mới:</label>
                            <select name="status" class="form-select">
                                <option value="Đang xử lý" <?= $order['status'] == 'Đang xử lý' ? 'selected' : '' ?>>Đang xử lý</option>
                                <option value="Đang giao" <?= $order['status'] == 'Đang giao' ? 'selected' : '' ?>>Đang giao</option>
                                <option value="Hoàn thành" <?= $order['status'] == 'Hoàn thành' ? 'selected' : '' ?>>Hoàn thành</option>
                                <option value="Đã hủy" <?= $order['status'] == 'Đã hủy' ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="update_status" class="btn btn-primary-custom btn-block">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>