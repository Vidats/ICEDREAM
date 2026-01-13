<?php
require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/../../Model/db.php';
?>

<div class="page-header">
    <h2 class="page-title">Quản Lý Người Dùng</h2>
    <div class="text-muted">Danh sách khách hàng đã đăng ký</div>
</div>

<?php if(isset($_GET['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_GET['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="ps-4 py-3">ID</th>
                    <th class="py-3">Thông Tin Người Dùng</th>
                    <th class="py-3">Liên Hệ</th>
                    <th class="py-3">Ngày Tham Gia</th>
                    <th class="py-3">Vai Trò</th>
                    <th class="py-3">Trạng Thái</th>
                    <th class="text-end pe-4 py-3">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
                if($result->num_rows > 0):
                while($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td class="ps-4 fw-bold text-muted">#<?= $row['id'] ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center fw-bold" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                <?= strtoupper(substr($row['full_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['full_name']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-muted small"><i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($row['email']) ?></div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border"><?= date('d/m/Y', strtotime($row['created_at'])) ?></span>
                    </td>
                    <td>
                        <?php if($row['role'] == 1): ?>
                            <span class="badge bg-primary">Quản trị viên</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Khách hàng</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($row['status'] == 1): ?>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">Hoạt động</span>
                        <?php else: ?>
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">Bị khóa</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end pe-4">
                        <?php if($row['role'] != 1): // Không cho phép thao tác admin khác ?>
                            <a href="../controller/quanlyuser.php?action=toggle_status&id=<?= $row['id'] ?>" 
                               class="btn btn-sm btn-light border me-1 <?= $row['status'] == 1 ? 'text-warning' : 'text-success' ?>" 
                               title="<?= $row['status'] == 1 ? 'Khóa tài khoản' : 'Mở khóa' ?>">
                                <i class="fas <?= $row['status'] == 1 ? 'fa-lock' : 'fa-unlock' ?>"></i>
                            </a>
                            <a href="../controller/quanlyuser.php?action=delete&id=<?= $row['id'] ?>" 
                               class="btn btn-sm btn-light border text-danger" 
                               onclick="return confirm('Hành động này không thể hoàn tác. Bạn chắc chắn muốn xóa user này?')"
                               title="Xóa người dùng">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        <?php else: ?>
                            <span class="text-muted small">Không khả dụng</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="7" class="text-center p-5 text-muted">Chưa có người dùng nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>