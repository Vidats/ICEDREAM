<?php
require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/../../Model/db.php';
require_once __DIR__ . '/../model/CategoryModel.php';

$categoryModel = new CategoryModel();
$categoriesResult = $categoryModel->getAllCategories();
?>

<div class="page-header">
    <h2 class="page-title">Quản Lý Danh Mục</h2>
    <a href="quanlydanhmuc.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-sync-alt"></i> Làm mới</a>
</div>

<div class="row">
    <!-- Form Section -->
    <div class="col-lg-4 mb-4">
        <div class="card p-4">
            <h5 class="card-title fw-bold mb-3" id="form-title"><i class="fas fa-plus-circle text-success"></i> Thêm Danh Mục Mới</h5>
            
            <form action="../controller/quanlydanhmuc.php" method="POST">
                <input type="hidden" name="id" id="categoryId">

                <div class="mb-3">
                    <label for="categoryName" class="form-label small fw-bold text-muted">Tên danh mục</label>
                    <input type="text" name="name" id="categoryName" class="form-control" placeholder="VD: Kem ốc quế..." required>
                </div>

                <div class="mb-3">
                    <label for="categoryDescription" class="form-label small fw-bold text-muted">Mô tả</label>
                    <textarea name="description" id="categoryDescription" class="form-control" rows="3" placeholder="Mô tả ngắn về danh mục..."></textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="add_category" id="addButton" class="btn btn-primary-custom">
                        <i class="fas fa-plus"></i> Thêm Vào
                    </button>
                    <button type="submit" name="edit_category" id="editButton" class="btn btn-primary-custom" style="display:none;">
                        <i class="fas fa-save"></i> Lưu Thay Đổi
                    </button>
                    <button type="button" id="cancelButton" class="btn btn-outline-secondary" style="display:none;">
                        <i class="fas fa-times"></i> Hủy
                    </button>
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
                            <th class="ps-4">ID</th>
                            <th>Tên Danh Mục</th>
                            <th class="text-end pe-4">Thao Tác</th>
                        </tr>
                    </thead>
                   <tbody>
    <?php
    // Kiểm tra nếu là mảng và không rỗng
    if (is_array($categoriesResult) && count($categoriesResult) > 0):
        // Dùng foreach thay cho while vì dữ liệu đã là mảng
        foreach($categoriesResult as $row):
    ?>
    <tr>
        <td class="ps-4 fw-bold">#<?= $row['id'] ?></td>
        <td>
            <span class="badge bg-info text-dark bg-opacity-10 border border-info px-3 py-2 rounded-pill">
                <?= htmlspecialchars($row['name']) ?>
            </span>
        </td>
        <td class="text-end pe-4">
            <a href="#" class="btn btn-sm btn-light text-primary border me-1 edit-btn" 
               data-id="<?= $row['id'] ?>" 
               data-name="<?= htmlspecialchars($row['name']) ?>" 
               data-description="<?= htmlspecialchars($row['description'] ?? '') ?>">
                <i class="fas fa-edit"></i>
            </a>
            <a href="../controller/quanlydanhmuc.php?delete_category=<?= $row['id'] ?>" 
               class="btn btn-sm btn-light text-danger border" 
               onclick="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">
                <i class="fas fa-trash-alt"></i>
            </a>
        </td>
    </tr>
    <?php endforeach; else: ?>
        <tr><td colspan="3" class="text-center p-5 text-muted">Chưa có danh mục nào.</td></tr>
    <?php endif; ?>
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formTitle = document.getElementById('form-title');
    const categoryIdInput = document.getElementById('categoryId');
    const categoryNameInput = document.getElementById('categoryName');
    const categoryDescriptionInput = document.getElementById('categoryDescription');
    const addButton = document.getElementById('addButton');
    const editButton = document.getElementById('editButton');
    const cancelButton = document.getElementById('cancelButton');
    const editButtons = document.querySelectorAll('.edit-btn');

    const resetForm = () => {
        formTitle.innerHTML = '<i class="fas fa-plus-circle text-success"></i> Thêm Danh Mục Mới';
        categoryIdInput.value = '';
        categoryNameInput.value = '';
        categoryDescriptionInput.value = '';
        addButton.style.display = 'block';
        editButton.style.display = 'none';
        cancelButton.style.display = 'none';
    };

    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const description = this.getAttribute('data-description');
            
            formTitle.innerHTML = '<i class="fas fa-edit text-primary"></i> Cập Nhật Danh Mục';
            categoryIdInput.value = id;
            categoryNameInput.value = name;
            categoryDescriptionInput.value = description;
            categoryNameInput.focus();
            
            addButton.style.display = 'none';
            editButton.style.display = 'block';
            cancelButton.style.display = 'block';
        });
    });

    cancelButton.addEventListener('click', resetForm);
});
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
