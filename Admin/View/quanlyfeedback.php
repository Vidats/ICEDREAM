<?php
require_once __DIR__ . '/layouts/header.php';
?>

<div class="page-header">
    <h2 class="page-title">Quản Lý Đánh Giá</h2>
    <div class="text-muted">Danh sách feedback từ khách hàng</div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="ps-4 py-3">ID</th>
                    <th class="py-3">Khách hàng</th>
                    <th class="py-3">Mã đơn hàng</th>
                    <th class="py-3">Đánh giá</th>
                    <th class="py-3">Nội dung</th>
                    <th class="py-3">Ngày gửi</th>
                    <th class="text-end pe-4 py-3">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($feedbacks)): ?>
                    <?php foreach ($feedbacks as $item): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#<?= $item['id'] ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($item['username']) ?></div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">Order #<?= $item['order_id'] ?></span>
                        </td>
                        <td>
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $item['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                            <?php endfor; ?>
                        </td>
                        <td>
                            <div class="text-wrap" style="max-width: 300px; font-size: 0.9rem;">
                                <?= htmlspecialchars($item['comment']) ?>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted small"><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="quanlyfeedback.php?action=delete&id=<?= $item['id'] ?>" 
                               class="btn btn-sm btn-light border text-danger" 
                               onclick="return confirm('Bạn chắc chắn muốn xóa đánh giá này?')"
                               title="Xóa đánh giá">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center p-5 text-muted">Chưa có đánh giá nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>