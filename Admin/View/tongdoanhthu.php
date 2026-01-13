<?php
require_once __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/../../Model/db.php';

// 1. Tổng doanh thu (All time)
$sqlTotal = "SELECT SUM(total_price) as total, COUNT(*) as count FROM orders WHERE status = 'Hoàn thành'";
$resTotal = $conn->query($sqlTotal)->fetch_assoc();
$totalRevenue = $resTotal['total'] ?? 0;
$completedOrders = $resTotal['count'] ?? 0;

// 2. Doanh thu theo tháng (12 tháng gần nhất)
$sqlMonthly = "SELECT DATE_FORMAT(created_at, '%m/%Y') as month, SUM(total_price) as revenue 
               FROM orders 
               WHERE status = 'Hoàn thành' 
               GROUP BY month 
               ORDER BY created_at ASC 
               LIMIT 12";
$resMonthly = $conn->query($sqlMonthly);
$chartLabels = [];
$chartData = [];
if ($resMonthly) {
    while($row = $resMonthly->fetch_assoc()) {
        $chartLabels[] = $row['month'];
        $chartData[] = (int)$row['revenue'];
    }
}
?>

<div class="page-header">
    <h2 class="page-title">Báo Cáo Doanh Thu</h2>
    <div class="text-muted">Thống kê hiệu quả kinh doanh</div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card p-4 h-100 border-0 shadow-sm bg-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="opacity-75">Tổng Doanh Thu Thực Tế</h5>
            <div class="d-flex align-items-baseline gap-2">
                <h2 class="fw-bold display-5 mb-0"><?= number_format($totalRevenue) ?></h2>
                <span class="h4">VNĐ</span>
            </div>
            <div class="mt-3 opacity-75">
                <i class="fas fa-check-circle me-1"></i> Tính trên <?= $completedOrders ?> đơn hàng đã hoàn thành
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <h5 class="card-title text-muted">Tăng trưởng (Dự kiến)</h5>
            <div class="d-flex align-items-center h-100 justify-content-center text-muted">
                <div class="text-center">
                    <i class="fas fa-chart-line fa-3x mb-2 text-success"></i>
                    <p>Hệ thống đang thu thập thêm dữ liệu để dự báo.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm p-4 mb-4">
    <h5 class="card-title fw-bold mb-4">Biểu Đồ Doanh Thu Theo Tháng</h5>
    <div style="height: 400px;">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'bar', // Hoặc 'line'
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: <?= json_encode($chartData) ?>,
                backgroundColor: 'rgba(255, 133, 162, 0.6)',
                borderColor: 'rgba(255, 133, 162, 1)',
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
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {

                    
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
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
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>