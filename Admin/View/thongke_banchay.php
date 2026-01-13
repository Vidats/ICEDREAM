<?php
require_once 'layouts/header.php'; 
require_once '../model/AdminOrderModel.php'; 

$adminOrderModel = new AdminOrderModel($conn);
$topProducts = $adminOrderModel->getTopSellingProducts();

// Chuyển đổi dữ liệu sang mảng JSON để JS xử lý
$labels = [];
$data = [];

foreach ($topProducts as $prod) {
    $labels[] = $prod['name'];
    $data[] = $prod['total_sold'];
}

// Nếu chưa có dữ liệu thì hiển thị mặc định (demo) để biểu đồ không trống
if (empty($labels)) {
    $isEmpty = true;
    $labels = ["Chưa có dữ liệu"];
    $data = [0];
} else {
    $isEmpty = false;
}
?>

<div class="container-fluid">
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-chart-pie me-2"></i>Thống kê sản phẩm bán chạy</h2>
    </div>

    <div class="row">
        <!-- Biểu đồ -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Biểu đồ Top 5 Sản Phẩm</h6>
                </div>
                <div class="card-body">
                    <?php if($isEmpty): ?>
                        <div class="alert alert-info text-center">
                            Hiện chưa có đơn hàng nào có dữ liệu chi tiết. Hãy đặt thử một đơn hàng!
                        </div>
                    <?php endif; ?>
                    <div class="chart-container" style="position: relative; height:40vh; width:100%">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng chi tiết -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chi tiết số liệu</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Đã bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!$isEmpty): ?>
                                    <?php foreach ($topProducts as $prod): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($prod['name']) ?></td>
                                            <td class="text-center font-weight-bold"><?= $prod['total_sold'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-center">Chưa có dữ liệu</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Lấy dữ liệu từ PHP
    const labels = <?= json_encode($labels) ?>;
    const dataPoints = <?= json_encode($data) ?>;

    const ctx = document.getElementById('topProductsChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar', // Loại biểu đồ: bar (cột), pie (tròn), line (đường)
        data: {
            labels: labels,
            datasets: [{
                label: 'Số lượng đã bán',
                data: dataPoints,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

<?php require_once 'layouts/footer.php'; ?>