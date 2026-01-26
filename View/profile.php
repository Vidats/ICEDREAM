<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../Model/db.php';
require_once '../Model/UserModel.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?tab=login");
    exit();
}

$userModel = new UserModel($conn);
$user_id = $_SESSION['user_id'];
$user = $userModel->getUserById($user_id);

// Lấy thông báo từ URL nếu có
$message = $_GET['message'] ?? '';
$status = $_GET['status'] ?? '';

?>

<?php include 'header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="fas fa-user-circle"></i> Thông tin tài khoản</h4>
                </div>
                <div class="card-body">
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $status == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="../Controller/profile.php" method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới (Để trống nếu không muốn thay đổi)</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="********">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật thông tin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>