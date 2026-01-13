
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    // Determine path to login form relative to current file
    // Assuming structure: Admin/View/layouts/header.php included by Admin/View/file.php or Admin/index.php
    $loginPath = '../../View/form.php'; 
    if(basename(getcwd()) == 'Admin') {
        $loginPath = '../View/form.php';
    }
    header("Location: $loginPath?tab=login&status=error&message=Vui lòng đăng nhập quyền Admin!");
    exit();
}

// Determine current page for active menu
$currentPage = basename($_SERVER['PHP_SELF']);

// Determine base path for links
$baseAdminPath = '../'; // Default if inside Admin/View/
if(basename(getcwd()) == 'Admin') {
    $baseAdminPath = './';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Icedream</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #ff85a2;
            --secondary-color: #ffb7b2;
            --accent-color: #a2d2ff;
            --sidebar-bg: #ffffff;
            --sidebar-text: #555555;
            --sidebar-width: 260px;
            --bg-color: #f8f9fa;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--bg-color);
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-brand {
            padding: 25px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-color);
            font-weight: 800;
            font-size: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            text-decoration: none;
        }
        
        .sidebar-brand i {
            font-size: 1.8rem;
        }
        
        .sidebar-menu {
            padding: 20px 10px;
            list-style: none;
            flex-grow: 1;
        }
        
        .menu-item {
            margin-bottom: 5px;
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
            gap: 15px;
        }
        
        .menu-link:hover, .menu-link.active {
            background-color: #fff0f3;
            color: var(--primary-color);
        }
        
        .menu-link i {
            width: 25px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .user-profile {
            padding: 20px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        /* Components */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            background: white;
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-weight: 800;
            color: #333;
            margin: 0;
            font-size: 1.8rem;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 600;
        }
        
        .btn-primary-custom:hover {
            background-color: #ff6b8e;
            border-color: #ff6b8e;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .mobile-toggle {
                display: block !important;
            }
        }
        
        .mobile-toggle {
            display: none;
            font-size: 1.5rem;
            color: #333;
            cursor: pointer;
            margin-right: 15px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="<?= $baseAdminPath ?>index.php" class="sidebar-brand">
            <i class="fas fa-ice-cream"></i>
            <span>IceDream</span>
        </a>
        
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="<?= $baseAdminPath ?>index.php" class="menu-link <?= ($currentPage == 'index.php' || $currentPage == 'dashboard_home.php') ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Tổng Quan</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= $baseAdminPath ?>View/quanlysp.php" class="menu-link <?= ($currentPage == 'quanlysp.php') ? 'active' : '' ?>">
                    <i class="fas fa-box-open"></i>
                    <span>Sản Phẩm</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= $baseAdminPath ?>View/quanlydonhang.php" class="menu-link <?= ($currentPage == 'quanlydonhang.php') ? 'active' : '' ?>">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Đơn Hàng</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= $baseAdminPath ?>View/quanlyuser.php" class="menu-link <?= ($currentPage == 'quanlyuser.php') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Khách Hàng</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= $baseAdminPath ?>controller/quanlyfeedback.php" class="menu-link <?= ($currentPage == 'quanlyfeedback.php') ? 'active' : '' ?>">
                    <i class="fas fa-comments"></i>
                    <span>Đánh Giá</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= $baseAdminPath ?>View/tongdoanhthu.php" class="menu-link <?= ($currentPage == 'tongdoanhthu.php') ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Doanh Thu</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= $baseAdminPath ?>View/thongke_banchay.php" class="menu-link <?= ($currentPage == 'thongke_banchay.php') ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i>
                    <span>SP Bán Chạy</span>
                </a>
            </li>
            <li class="menu-item mt-3">
                <a href="<?= $baseAdminPath ?>../View/index.php" class="menu-link text-primary">
                    <i class="fas fa-home"></i>
                    <span>Về Trang Chủ</span>
                </a>
            </li>
        </ul>
        
        <div class="user-profile">
            <div class="user-avatar">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
            </div>
            <div style="flex-grow: 1;">
                <div style="font-weight: 700; font-size: 0.9rem;"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></div>
                <div style="font-size: 0.8rem; color: #888;">Quản trị viên</div>
            </div>
            <a href="<?= $baseAdminPath ?>../Controller/logout.php" title="Đăng xuất" style="color: #ff6b6b;">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-content">
        <!-- Top Mobile Header -->
        <div class="d-flex align-items-center d-md-none mb-4">
            <i class="fas fa-bars mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')"></i>
            <h4 class="m-0 font-weight-bold">Admin Panel</h4>
        </div>
