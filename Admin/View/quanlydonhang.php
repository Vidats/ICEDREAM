<?php
require_once __DIR__ . '/../../Model/db.php';
require_once __DIR__ . '/../controller/OrderController.php';

$orderController = new OrderController($conn);

// Handle Status Update (POST) - Must be before any output
$orderController->updateStatus(); 

require_once __DIR__ . '/layouts/header.php';

// Fetch Orders
$orders = $orderController->getAllOrders();
?>

<div class="page-header">
    <h2 class="page-title">Quản Lý Đơn Hàng</h2>
    <div class="text-muted">Theo dõi và xử lý đơn đặt hàng</div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="ps-4 py-3">Mã Đơn</th>
                    <th class="py-3">Khách Hàng / Địa Chỉ</th>
                    <th class="py-3">Ngày Đặt</th>
                    <th class="py-3">Tổng Tiền</th>
                    <th class="py-3">Trạng Thái Hiện Tại</th>
                    <th class="py-3 text-center">Chi Tiết</th>
                    <th class="py-3" width="250">Cập Nhật Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="ps-4 fw-bold text-primary">#<?= $order['id'] ?></td>
                    <td>
                        <div class="fw-bold"><?= htmlspecialchars($order['full_name']) ?></div>
                        <div class="small text-muted text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($order['address']) ?>">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($order['address']) ?>
                        </div>
                    </td>
                    <td class="text-muted small">
                        <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                    </td>
                    <td class="fw-bold text-danger">
                        <?= number_format($order['total_price']) ?>đ
                    </td>
                    <td>
                        <?php 
                            $badgeClass = 'bg-secondary';
                            $icon = 'fa-clock';
                            if($order['status'] == 'Đang xử lý') { $badgeClass = 'bg-warning text-dark'; $icon = 'fa-clipboard-list'; }
                            if($order['status'] == 'Đang giao') { $badgeClass = 'bg-primary'; $icon = 'fa-shipping-fast'; }
                            if($order['status'] == 'Hoàn thành') { $badgeClass = 'bg-success'; $icon = 'fa-check'; }
                            if($order['status'] == 'Đã hủy') { $badgeClass = 'bg-danger'; $icon = 'fa-times'; }
                        ?>
                        <span class="badge rounded-pill <?= $badgeClass ?> px-3 py-2">
                            <i class="fas <?= $icon ?> me-1"></i> <?= $order['status'] ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info text-white rounded-circle shadow-sm" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                    <td>
                        <form method="POST" action="" class="d-flex gap-2">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" class="form-select form-select-sm" style="border-radius: 8px;">
                                <option value="Đang xử lý" <?= $order['status'] == 'Đang xử lý' ? 'selected' : '' ?>>Đang xử lý</option>
                                <option value="Đang giao" <?= $order['status'] == 'Đang giao' ? 'selected' : '' ?>>Đang giao</option>
                                <option value="Hoàn thành" <?= $order['status'] == 'Hoàn thành' ? 'selected' : '' ?>>Hoàn thành</option>
                                <option value="Đã hủy" <?= $order['status'] == 'Đã hủy' ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-primary-custom" title="Lưu">
                                <i class="fas fa-save"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted mb-2"><i class="fas fa-inbox fa-3x"></i></div>
                        <div>Chưa có đơn hàng nào</div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>