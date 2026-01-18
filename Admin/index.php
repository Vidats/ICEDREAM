<?php
require_once __DIR__ . '/View/layouts/header.php';
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/FeedbackModel.php';
require_once __DIR__ . '/model/AdminOrderModel.php';

// Fetch quick stats
$orderCount = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'] ?? 0;
$userCount = $conn->query("SELECT COUNT(*) as c FROM users WHERE role != 1")->fetch_assoc()['c'] ?? 0;
$productCount = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'] ?? 0;
$revenue = $conn->query("SELECT SUM(total_price) as s FROM orders WHERE status = 'Hoàn thành'")->fetch_assoc()['s'] ?? 0;

// --- 1. Line Chart: Doanh thu theo thời gian (12 tháng) ---
$sqlMonthly = "SELECT DATE_FORMAT(created_at, '%m/%Y') as month, SUM(total_price) as revenue 
               FROM orders 
               WHERE status = 'Hoàn thành' 
               GROUP BY month 
               ORDER BY created_at ASC 
               LIMIT 12";
$resMonthly = $conn->query($sqlMonthly);
$revLabels = [];
$revData = [];
if ($resMonthly) {
    while($row = $resMonthly->fetch_assoc()) {
        $revLabels[] = $row['month'];
        $revData[] = (int)$row['revenue'];
    }
}

$adminOrderModel = new AdminOrderModel($conn);

// --- 2. Bar Chart: Top 10 sản phẩm bán chạy ---
$topProducts = $adminOrderModel->getTopSellingProducts();
$prodLabels = [];
$prodData = [];
foreach ($topProducts as $prod) {
    $prodLabels[] = $prod['name'];
    $prodData[] = $prod['total_sold'];
}

// --- 3. Pie Chart: Trạng thái đơn hàng ---
$orderStats = $adminOrderModel->getOrderStatusStats();
$statusLabels = [];
$statusData = [];
$statusColors = [];
// Map màu cho từng trạng thái
$colorMap = [
    'Đang xử lý' => '#ffce56', // Vàng
    'Đang giao' => '#36a2eb', // Xanh dương
    'Hoàn thành' => '#2ecc71', // Xanh lá
    'Đã hủy' => '#e74c3c'      // Đỏ
];

foreach ($orderStats as $stat) {
    $statusLabels[] = $stat['status'];
    $statusData[] = $stat['count'];
    $statusColors[] = $colorMap[$stat['status']] ?? '#95a5a6';
}

// --- 4. Bar Chart: Doanh thu theo danh mục ---
$catStats = $adminOrderModel->getRevenueByCategory();
$catLabels = [];
$catData = [];
foreach ($catStats as $stat) {
    $catLabels[] = $stat['category_name'];
    $catData[] = $stat['revenue'];
}

// Fetch Feedback Stats (Giữ nguyên)
$feedbackModel = new FeedbackModel($conn);
$ratingStats = $feedbackModel->getRatingStatistics();

// --- 6. Bar Chart: Top 5 Khách hàng thân thiết ---
$topCustomers = $adminOrderModel->getTopCustomers();
$customerLabels = [];
$customerData = [];

foreach ($topCustomers as $customer) {
    // Sửa 'fullname' thành 'full_name' cho khớp với SQL
    $customerLabels[] = $customer['full_name']; 
    $customerData[] = (float)$customer['total_spent'];
}

// --- 7. Line Chart: Tăng trưởng người dùng ---
$userGrowth = $adminOrderModel->getUserGrowth();
$userGrowthLabels = [];
$userGrowthData = [];
foreach ($userGrowth as $growth) {
    $userGrowthLabels[] = $growth['registration_month'];
    $userGrowthData[] = (int)$growth['new_user_count'];
}
?>

<div class="page-header">
    <h2 class="page-title">Tổng Quan Hệ Thống</h2>
    <div class="text-muted"><?= date('l, d/m/Y') ?></div>
</div>

<!-- Quick Stats Cards -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4 mb-5">
    <!-- Card 1 -->
    <div class="col">
        <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #ff85a2 0%, #ffb7b2 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div style="font-size: 3rem; opacity: 0.8;"><i class="fas fa-shopping-cart"></i></div>
                <div class="text-end">
                    <h5 class="mb-0">Đơn Hàng</h5>
                    <small>Tổng số đơn</small>
                </div>
            </div>
            <h2 class="mb-0 fw-bold"><?= number_format($orderCount) ?></h2>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="col">
        <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #a2d2ff 0%, #bde0fe 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div style="font-size: 3rem; opacity: 0.8;"><i class="fas fa-box-open"></i></div>
                <div class="text-end">
                    <h5 class="mb-0">Sản Phẩm</h5>
                    <small>Đang bán</small>
                </div>
            </div>
            <h2 class="mb-0 fw-bold"><?= number_format($productCount) ?></h2>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="col">
        <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #ffc8dd 0%, #ffafcc 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div style="font-size: 3rem; opacity: 0.8;"><i class="fas fa-users"></i></div>
                <div class="text-end">
                    <h5 class="mb-0">Khách Hàng</h5>
                    <small>Đã đăng ký</small>
                </div>
            </div>
            <h2 class="mb-0 fw-bold"><?= number_format($userCount) ?></h2>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="col">
        <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #cdb4db 0%, #bde0fe 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div style="font-size: 3rem; opacity: 0.8;"><i class="fas fa-dollar-sign"></i></div>
                <div class="text-end">
                    <h5 class="mb-0">Doanh Thu</h5>
                    <small>Đã hoàn thành</small>
                </div>
            </div>
            <h2 class="mb-0 fw-bold"><?= number_format($revenue) ?>đ</h2>
        </div>
    </div>

    <!-- Card 5 - Rating -->
    <div class="col">
        <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #f1c40f 0%, #f39c12 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div style="font-size: 3rem; opacity: 0.8;"><i class="fas fa-star"></i></div>
                <div class="text-end">
                    <h5 class="mb-0">Đánh Giá</h5>
                    <small>Trung bình</small>
                </div>
            </div>
            <h2 class="mb-0 fw-bold"><?= $ratingStats['average'] ?> / 5</h2>
        </div>
    </div>
</div>

<!-- Row 1: Revenue Line Chart & Order Status Pie Chart -->
<div class="row mb-5">
    <div class="col-md-8">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Biểu Đồ Doanh Thu (12 Tháng)</h5>
            <canvas id="revenueChart" style="max-height: 350px;"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Trạng Thái Đơn Hàng</h5>
            <div style="position: relative; height: 300px; display: flex; justify-content: center;">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Revenue by Category & Top Products -->
<div class="row mb-5">
    <div class="col-md-6">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Doanh Thu Theo Danh Mục</h5>
            <canvas id="categoryRevenueChart" style="max-height: 350px;"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Top 10 Sản Phẩm Bán Chạy</h5>
            <canvas id="topProductsChart" style="max-height: 350px;"></canvas>
        </div>
    </div>
</div>

<!-- Row 4: Top Customers & User Growth -->
<div class="row mb-5">
    <div class="col-md-6">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Top 5 Khách Hàng Thân Thiết</h5>
            <canvas id="topCustomersChart" style="max-height: 350px;"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Tăng Trưởng Người Dùng (12 Tháng)</h5>
            <canvas id="userGrowthChart" style="max-height: 350px;"></canvas>
        </div>
    </div>
</div>

<!-- ChartJS Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. Revenue Chart (Line) ---
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: <?= json_encode($revLabels) ?>,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: <?= json_encode($revData) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return value.toLocaleString('vi-VN') + ' đ'; }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });

    // --- 2. Order Status Chart (Pie) ---
    const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'pie',
        data: {
            labels: <?= json_encode($statusLabels) ?>,
            datasets: [{
                data: <?= json_encode($statusData) ?>,
                backgroundColor: <?= json_encode($statusColors) ?>,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // --- 3. Category Revenue Chart (Bar) ---
    const ctxCategory = document.getElementById('categoryRevenueChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'bar',
        data: {
            labels: <?= json_encode($catLabels) ?>,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: <?= json_encode($catData) ?>,
                backgroundColor: '#9b59b6',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return value.toLocaleString('vi-VN') + ' đ'; }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                        }
                    }
                }
            }
        }
    });

    // --- 4. Top Products Chart (Bar) ---
    const ctxProducts = document.getElementById('topProductsChart').getContext('2d');
    new Chart(ctxProducts, {
        type: 'bar',
        data: {
            labels: <?= json_encode($prodLabels) ?>,
            datasets: [{
                label: 'Số lượng đã bán',
                data: <?= json_encode($prodData) ?>,
                backgroundColor: '#ff9f43',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y', // Biểu đồ ngang
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // --- 6. Top Customers Chart (Bar) ---
    const ctxCustomers = document.getElementById('topCustomersChart').getContext('2d');
    new Chart(ctxCustomers, {
        type: 'bar',
        data: {
            labels: <?= json_encode($customerLabels) ?>,
            datasets: [{
                label: 'Tổng chi tiêu (VNĐ)',
                data: <?= json_encode($customerData) ?>,
                backgroundColor: '#28a745', // Green
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y', // Horizontal bar chart
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return value.toLocaleString('vi-VN') + ' đ'; }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.x);
                        }
                    }
                }
            }
        }
    });

    // --- 7. User Growth Chart (Line) ---
    const ctxUserGrowth = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(ctxUserGrowth, {
        type: 'line',
        data: {
            labels: <?= json_encode($userGrowthLabels) ?>,
            datasets: [{
                label: 'Số người dùng mới',
                data: <?= json_encode($userGrowthData) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y.toLocaleString('vi-VN');
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/View/layouts/footer.php'; ?>