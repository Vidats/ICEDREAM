<?php 
include 'header.php'; 

// GỌI CONTROLLER để xử lý dữ liệu trước khi hiển thị
require_once '../Controller/sanpham.php'; 
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="section-title d-inline-block">Menu Kem Icedream</h2>
        <p class="mt-3">Vị ngon – tan chảy mọi trái tim 💞</p>
    </div>

    <div class="category-menu mb-5">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="sanpham.php" class="cat-item <?= $cat == '' ? 'active' : '' ?>">
                <i class="fas fa-border-all"></i> <span>TẤT CẢ</span>
            </a>
            <a href="sanpham.php?cat=Kem ốc quế" class="cat-item <?= $cat == 'Kem ốc quế' ? 'active' : '' ?>">
                <i class="fas fa-ice-cream"></i> <span>KEM ỐC QUẾ</span>
            </a>
            <a href="sanpham.php?cat=kemtuoi" class="cat-item <?= $cat == 'kemtuoi' ? 'active' : '' ?>">
                <i class="fas fa-glass-whiskey"></i> <span>KEM TƯƠI</span>
            </a>
            <a href="sanpham.php?cat=cafe" class="cat-item <?= $cat == 'cafe' ? 'active' : '' ?>">
                <i class="fas fa-coffee"></i> <span>KEM QUE</span>
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-center">
            <form class="d-flex w-100 w-md-50" method="GET" action="sanpham.php">
                <input type="hidden" name="cat" value="<?= htmlspecialchars($cat) ?>">
                <input type="search" name="q" class="form-control me-2" placeholder="Tìm sản phẩm theo tên..." value="<?= isset($q) ? htmlspecialchars($q) : '' ?>">
                <button class="btn btn-outline-primary" type="submit">Tìm</button>
            </form>
        </div>
    </div>

    <div class="row g-4"> 
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col-6 col-md-4 col-lg-3 mb-4"> 
                    <div class="card h-100 product-card shadow-sm">
                        <div class="product-img-container">
                            <a href="chitietsp.php?id=<?= $row['id'] ?>">
                                <img src="../image/<?= $row['image'] ?>" class="card-img-top product-image" alt="<?= $row['name'] ?>">
                            </a>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                <a href="chitietsp.php?id=<?= $row['id'] ?>" class="text-decoration-none product-name">
                                    <?= htmlspecialchars($row['name']) ?>
                                </a>
                            </h5>
                            <p class="card-text price-tag"><?= number_format($row['price'], 0, ',', '.') ?>đ</p>
                            <a href="chitietsp.php?id=<?= $row['id'] ?>" class="btn btn-primary mt-auto">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='text-center w-100'>Hiện chưa có sản phẩm trong danh mục này.</p>";
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>