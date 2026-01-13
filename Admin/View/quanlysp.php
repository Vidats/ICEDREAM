<?php
require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/../../Model/db.php';

$edit_product = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $res = $conn->query("SELECT * FROM products WHERE id = $edit_id");
    $edit_product = $res->fetch_assoc();
}
?>

<div class="page-header">
    <h2 class="page-title">Quản Lý Sản Phẩm</h2>
    <a href="quanlysp.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-sync-alt"></i> Làm mới</a>
</div>

<div class="row">
    <!-- Form Section -->
    <div class="col-lg-4 mb-4">
        <div class="card p-4 <?= $edit_product ? 'border-primary' : '' ?>">
            <h5 class="card-title fw-bold mb-3">
                <?= $edit_product ? '<i class="fas fa-edit text-primary"></i> Cập Nhật Món' : '<i class="fas fa-plus-circle text-success"></i> Thêm Món Mới' ?>
            </h5>
            
            <form action="../controller/quanlysp.php?action=<?= $edit_product ? 'edit' : 'add' ?>" method="POST" enctype="multipart/form-data">
                <?php if ($edit_product): ?>
                    <input type="hidden" name="id" value="<?= $edit_product['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Tên món</label>
                    <input type="text" name="name" class="form-control" value="<?= $edit_product ? htmlspecialchars($edit_product['name']) : '' ?>" placeholder="VD: Kem dâu..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Giá bán (VNĐ)</label>
                    <input type="number" name="price" class="form-control" value="<?= $edit_product ? $edit_product['price'] : '' ?>" placeholder="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Phân loại</label>
                    <input type="text" name="category" class="form-control" value="<?= $edit_product ? htmlspecialchars($edit_product['category']) : '' ?>" placeholder="VD: Kem, Sinh tố, Cafe..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" min="0" value="<?= $edit_product ? intval($edit_product['quantity']) : 0 ?>" placeholder="Số lượng trong kho">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3"><?= $edit_product ? htmlspecialchars($edit_product['description']) : '' ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Hình ảnh</label>
                    <?php if ($edit_product): ?>
                        <div class="mb-2">
                            <img src="../image/<?= $edit_product['image'] ?>" width="100%" class="rounded border p-1" style="max-height: 150px; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" <?= $edit_product ? '' : 'required' ?>>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary-custom">
                        <?= $edit_product ? 'Lưu Thay Đổi' : 'Thêm Vào Menu' ?>
                    </button>
                    <?php if ($edit_product): ?>
                        <a href="quanlysp.php" class="btn btn-outline-secondary">Hủy bỏ</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="col-lg-8">
        <div class="card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Món Ăn</th>
                            <th>Giá</th>
                            <th>Danh Mục</th>
                            <th>Số lượng</th>
                            <th class="text-end pe-4">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
                        if ($result->num_rows > 0):
                        while($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="../image/<?= $row['image'] ?>" class="rounded" width="50" height="50" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-bold"><?= htmlspecialchars($row['name']) ?></div>
                                        <small class="text-muted">#<?= $row['id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-primary"><?= number_format($row['price']) ?>đ</td>
                            <td><span class="badge bg-info text-dark bg-opacity-10 border border-info px-3 py-2 rounded-pill"><?= ucfirst($row['category']) ?></span></td>
                            <td>
                                <span class="fw-bold"><?= intval($row['quantity']) ?></span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="quanlysp.php?edit_id=<?= $row['id'] ?>" class="btn btn-sm btn-light text-primary border me-1"><i class="fas fa-edit"></i></a>
                                <a href="../controller/quanlysp.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-light text-danger border" onclick="return confirm('Bạn chắc chắn muốn xóa món này?')"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="5" class="text-center p-5 text-muted">Chưa có sản phẩm nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>