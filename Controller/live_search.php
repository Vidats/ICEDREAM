<?php
require_once '../Model/db.php';
require_once '../Model/sanpham.php';

$category_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$sanphamModel = new SanphamModel($conn);
$result = $sanphamModel->getProducts($category_id, $q);
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
                    
                    <div class="d-flex justify-content-center gap-2 mt-auto">
                        <a href="chitietsp.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                            Xem chi tiết
                        </a>
                        <form method="POST" action="../Controller/giohang.php">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" name="add_to_cart" class="btn btn-outline-danger btn-sm rounded-circle" title="Thêm vào giỏ">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
} else {
    echo "<p class='text-center w-100'>Không tìm thấy sản phẩm nào phù hợp.</p>";
}
?>