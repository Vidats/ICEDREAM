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

// --- Fetch Revenue Chart Data (Line Chart) ---
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

// --- Fetch Top Selling Products Data (Doughnut Chart) ---
$adminOrderModel = new AdminOrderModel($conn);
$topProducts = $adminOrderModel->getTopSellingProducts();
$prodLabels = [];
$prodData = [];
foreach ($topProducts as $prod) {
    $prodLabels[] = $prod['name'];
    $prodData[] = $prod['total_sold'];
}

// Fetch Feedback Stats
$feedbackModel = new FeedbackModel($conn);
$ratingStats = $feedbackModel->getRatingStatistics();
?>

<div class="page-header">
    <h2 class="page-title">Tổng Quan Hệ Thống</h2>
    <div class="text-muted"><?= date('l, d/m/Y') ?></div>
</div>

<div class="row g-4 mb-5">
    <!-- Card 1 -->
    <div class="col-md-3">
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
    <div class="col-md-3">
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
    <div class="col-md-3">
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
    <div class="col-md-3">
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
</div>

<!-- Revenue & Top Products Charts Row -->
<div class="row mb-5">
    <div class="col-md-8">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Biểu Đồ Doanh Thu (12 Tháng)</h5>
            <canvas id="revenueChart" style="max-height: 350px;"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Top Sản Phẩm Bán Chạy</h5>
            <div style="position: relative; height: 300px; display: flex; justify-content: center;">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Rating Stats Row -->
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Tổng Quan Đánh Giá</h5>
            <div class="text-center py-3">
                <h1 class="display-1 fw-bold text-warning"><?= $ratingStats['average'] ?></h1>
                <div class="mb-2 text-warning fs-4">
                    <?php 
                    $stars = round($ratingStats['average']);
                    for($i=1; $i<=5; $i++) {
                        echo $i <= $stars ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                    }
                    ?>
                </div>
                <p class="text-muted">Dựa trên <?= $ratingStats['total'] ?> lượt đánh giá</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title mb-4 fw-bold">Phân Bố Sao</h5>
            <canvas id="ratingChart" style="max-height: 250px;"></canvas>
        </div>
    </div>
</div>

<!-- ChartJS Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // --- Rating Chart ---
    const ctxRating = document.getElementById('ratingChart').getContext('2d');
    new Chart(ctxRating, {
        type: 'bar',
        data: {
            labels: ['1 Sao', '2 Sao', '3 Sao', '4 Sao', '5 Sao'],
            datasets: [{
                label: 'Số lượng đánh giá',
                data: [
                    <?= $ratingStats['stars'][1] ?>, 
                    <?= $ratingStats['stars'][2] ?>, 
                    <?= $ratingStats['stars'][3] ?>, 
                    <?= $ratingStats['stars'][4] ?>, 
                    <?= $ratingStats['stars'][5] ?>
                ],
                backgroundColor: [
                    '#ff6b6b',
                    '#ffa502',
                    '#f1c40f',
                    '#7bed9f',
                    '#2ed573'
                ],
                borderWidth: 1,
                borderRadius: 5
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
                legend: { display: false }
            }
        }
    });

    // --- Revenue Chart (Line Chart) ---
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

    // --- Top Products Chart (Doughnut Chart) ---
    const ctxProducts = document.getElementById('topProductsChart').getContext('2d');
    new Chart(ctxProducts, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($prodLabels) ?>,
            datasets: [{
                data: <?= json_encode($prodData) ?>,
                backgroundColor: [
                    '#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff'
                ],
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
});
</script>


<?php require_once __DIR__ . '/View/layouts/footer.php'; ?>