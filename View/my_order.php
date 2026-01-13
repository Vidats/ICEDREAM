<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: form.php?tab=login");
    exit();
}

include 'header.php'; 
require_once '../Model/order.php';
require_once '../Model/db.php';
require_once '../Model/FeedbackModel.php';

$orderModel = new OrderModel($conn);
$feedbackModel = new FeedbackModel($conn);

if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    
    if ($orderModel->updateOrderStatus($order_id, 'Đã hủy')) {
        echo "<script>alert('Đã hủy đơn hàng thành công!'); window.location='my_order.php';</script>";
    }
}

$user_id = $_SESSION['user_id'];
$orders = $orderModel->getOrdersByUser($user_id);
?>

<div class="container py-5">
    <h2 class="mb-4 text-pink" style="color: #ff85a2;">
        <i class="fas fa-bell text-warning"></i> Đơn hàng của bạn
    </h2>
    
    <div class="table-responsive">
        <table class="table table-bordered align-middle shadow-sm">
            <thead class="text-white" style="background-color: #ff85a2;">
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Bạn chưa có đơn hàng nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td class="fw-bold">#<?= $order['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td class="text-danger fw-bold"><?= number_format($order['total_price'], 0, ',', '.') ?>đ</td>
                        <td>
                            <?php 
                                $status = $order['status'];
                                $badgeClass = 'bg-warning text-dark'; // Đang xử lý
                                if ($status == 'Đang giao') $badgeClass = 'bg-primary';
                                if ($status == 'Hoàn thành') $badgeClass = 'bg-success';
                                if ($status == 'Đã hủy') $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                        </td>
                        <td>
                            <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn btn-info btn-sm rounded-pill text-white me-1" title="Xem chi tiết">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <?php if ($status == 'Hoàn thành'): ?>
                                <?php if (!$feedbackModel->hasFeedback($order['id'])): ?>
                                    <a href="write_feedback.php?order_id=<?= $order['id'] ?>" class="btn btn-warning btn-sm rounded-pill text-white" title="Đánh giá">
                                        <i class="fas fa-star"></i> Đánh giá
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm rounded-pill" disabled>Đã đánh giá</button>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($status == 'Đang xử lý'): ?>
                                <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');" style="display:inline-block;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button name="cancel_order" class="btn btn-outline-danger btn-sm rounded-pill">
                                        <i class="fas fa-times-circle"></i> Hủy
                                    </button>
                                </form>
                            <?php else: ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>