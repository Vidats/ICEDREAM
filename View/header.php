<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/giohang.php';
$giohangModel = new GiohangModel($conn);

// Lấy tên file hiện tại để bắt active (ví dụ: index.php)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiệm Kem Icedream</title>
    <link rel="stylesheet" href="../Content/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
</head>
<body>
    <header>
        <div class="container header-wrapper">
            <div class="logo">
                <a href="index.php" style="text-decoration: none; color: inherit;">
                    <i class="fas fa-ice-cream"></i> ICE<span>DREAM</span>
                </a>
            </div>

            <div class="menu-toggle" id="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>

            <nav id="nav-menu">
                <ul>
                    <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Trang Chủ</a></li>
                    <li><a href="sanpham.php" class="<?= $current_page == 'sanpham.php' ? 'active' : '' ?>">Sản Phẩm</a></li>
                    <li><a href="gioithieu.php" class="<?= $current_page == 'gioithieu.php' ? 'active' : '' ?>">Giới thiệu</a></li>
                    <li><a href="Lienhe.php" class="<?= $current_page == 'Lienhe.php' ? 'active' : '' ?>">Liên Hệ</a></li>
                </ul>
            </nav>

            <div class="header-icons">
                <a href="my_order.php" class="icon-link position-relative" title="Đơn hàng của tôi">
                    <i class="fas fa-bell"></i>
                    <span class="notification-dot"></span>
                </a>

                <a href="giohang.php" class="icon-link position-relative" title="Giỏ hàng">
                    <i class="fas fa-shopping-cart"></i>
                    <?php $count = $giohangModel->demLoaiSanPham(); if ($count > 0): ?>
                        <span class="cart-badge"><?= $count; ?></span>
                    <?php endif; ?>
                </a>

                <?php if (isset($_SESSION['full_name'])): ?>
                    <div class="user-info">
                        <span class="user-name">
                             Hi, <?= htmlspecialchars($_SESSION['full_name']) ?>
                        </span>
                        <a href="profile.php" title="Thông tin tài khoản" class="icon-link" style="margin-left: 10px;">
                            <i class="fas fa-user-circle"></i>
                        </a>
                        <a href="../Controller/logout.php" title="Đăng xuất" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="auth.php" class="icon-link" title="Đăng nhập"><i class="fas fa-user"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </header>
   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        const navMenu = document.getElementById('nav-menu');

        if (mobileMenu && navMenu) {
            mobileMenu.addEventListener('click', function() {
                navMenu.classList.toggle('active');
            });
        }
    });
</script>