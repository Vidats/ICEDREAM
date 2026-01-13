<?php 
include 'header.php';
require_once '../Model/sanpham.php';
require_once '../Model/FeedbackModel.php';
require_once '../Model/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sanphamModel = new SanphamModel($conn);
$result = $sanphamModel->getHotProducts(8);

$feedbackModel = new FeedbackModel($conn);
$feedbacks = $feedbackModel->getAllFeedbacks();
$feedbacks = array_slice($feedbacks, 0, 4); // Lấy 4 đánh giá mới nhất
?>
<div id="heroBanner" class="hero-banner">
    <div class="container position-relative text-center">

        <img id="bannerImage" src="../image/baner.jpg"
              class="img-fluid hero-image"
              alt="Món kem mùa hè mát lạnh">

        <h1 class="display-3">Hương Vị Mùa Hè</h1>
        <p class="fs-4">Khám phá bộ sưu tập kem tươi mát lạnh, giải nhiệt ngày hè.</p>
        <a href="sanpham.php" class="btn btn-hero ">Xem Sản Phẩm</a>
    </div>
</div>

<div class="container py-5">
    <h2 class="section-title text-center mb-5">🍦 Sản Phẩm Nổi Bật</h2>

    <div class="row g-4"> 
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="col-6 col-md-4 col-lg-3 mb-4"> <div class="card h-100 product-card shadow-sm">
                        <div class="product-img-container">
                            <a href="chitietsp.php?id='.$row['id'].'">
                                <img src="../image/'.$row['image'].'" 
                                     class="card-img-top product-image" 
                                     alt="'.$row['name'].'">
                            </a>
                            <div class="badge-new">New</div>
                        </div>

                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title">
                                <a href="chitietsp.php?id='.$row['id'].'" class="text-decoration-none product-name">
                                    '.$row['name'].'
                                </a>
                            </h5>
                            <p class="card-text price-tag">'.number_format($row['price'], 0, ',', '.').'đ</p>
                            <a href="chitietsp.php?id='.$row['id'].'" 
                               class="btn btn-add-to-cart mt-auto">
                               Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                ';
            }
        } else {
            echo "<p class='text-center'>Không có sản phẩm nào để hiển thị.</p>";
        }
        ?>
    </div>
</div>

<!-- FEEDBACK SECTION -->
<div class="feedback-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">💬 Khách Hàng Nói Gì?</h2>
        <div class="row g-4">
            <?php if (!empty($feedbacks)): ?>
                <?php foreach ($feedbacks as $fb): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="feedback-card h-100">
                            <div class="user-info mb-3">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($fb['username'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h5 class="mb-0 user-name-fb"><?= htmlspecialchars($fb['username']) ?></h5>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($fb['created_at'])) ?></small>
                                </div>
                            </div>
                            <div class="star-rating mb-2">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $fb['rating'] ? 'filled' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="feedback-comment">
                                "<?= htmlspecialchars($fb['comment']) ?>"
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">Chưa có đánh giá nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const banners = [
    "../image/baner.jpg",
    "../image/nenkem.jpg",
    "../image/banner1.png"
];

let current = 0;
const bannerImg = document.getElementById("bannerImage");

// Đảm bảo phần tử tồn tại trước khi chạy setInterval
if (bannerImg) {
    setInterval(() => {
        current = (current + 1) % banners.length;
        bannerImg.src = banners[current];
    }, 4000); // 4 giây đổi 1 lần
}
</script>

<?php include 'footer.php'; ?>