<?php 
session_start();
include 'header.php'; 
$activeTab = $_GET['tab'] ?? 'login';
$status = $_GET['status'] ?? '';
$message = $_GET['message'] ?? '';
?>
<body>
    <?php if ($status && $message): ?>
        <div style="text-align: center; margin-top: 20px;">
            <p style="color: <?= $status == 'success' ? 'green' : 'red' ?>; border: 1px solid; padding: 10px; display: inline-block;">
                <?= htmlspecialchars($message) ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-switcher">
                <a href="?tab=login" class="switcher-btn <?= $activeTab === 'login' ? 'active' : '' ?>">ĐĂNG NHẬP</a>
                <a href="?tab=register" class="switcher-btn <?= $activeTab === 'register' ? 'active' : '' ?>">TẠO TÀI KHOẢN</a>
            </div>

            <div id="login-new-form" class="form-section <?= $activeTab === 'login' ? 'active' : '' ?>">
                <form method="POST" action="../Controller/login.php">
                    <label>Email*</label>
                    <input type="email" name="email" required />
                    <label>Mật khẩu*</label>
                    <input type="password" name="password" required />
                    <button type="submit" class="submit-btn">ĐĂNG NHẬP</button>
                </form>
            </div>

            <div id="register-new-form" class="form-section <?= $activeTab === 'register' ? 'active' : '' ?>">
                <form method="POST" action="../Controller/registration.php">
                    <label>Họ và Tên*</label>
                    <input type="text" name="full_name" required />
                    <label>Email*</label>
                    <input type="email" name="email" required />
                    <label>Mật khẩu*</label>
                    <input type="password" name="password" required />
                    <button type="submit" class="submit-btn">ĐĂNG KÝ</button>
                </form>
            </div>
        </div>
    </div>
</body>