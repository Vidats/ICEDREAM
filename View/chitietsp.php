<?php 
// Nạp Controller trước để chuẩn bị dữ liệu $product
require_once '../Controller/chitietsp.php'; 
include 'header.php'; 
?>

<link rel="stylesheet" href="../Content/chitietsp.css">

<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4">
            <img src="../image/<?php echo $product['image']; ?>" class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>

        <div class="col-md-6">
            <h1 class="fw-bold"><?php echo htmlspecialchars($product['name']); ?></h1>
            <h3 class="text-danger mb-4"><?php echo number_format($product['price']); ?>đ</h3>
            <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <hr>

            <form method="post" action="">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                <div class="d-flex align-items-center mb-4">
                    <label for="quantity" class="form-label me-3 mb-0 fw-bold">Số lượng:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control shadow-none" style="width: 80px; border-radius: 10px;">
                </div>

                <div class="d-grid gap-2 d-md-flex">
                    <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg px-4 shadow-sm" style="border-radius: 25px;">
                        <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AI Recommendation Section -->
<?php if (!empty($recommendations)): ?>
<div class="container py-5">
    <h3 class="fw-bold mb-4 text-center">Có thể bạn sẽ thích</h3>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php foreach ($recommendations as $item): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <a href="chitietsp.php?id=<?= $item['id'] ?>">
                    <img src="../image/<?= $item['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>" style="height: 250px; object-fit: contain; background-color: #f8f9fa;">
                </a>
                <div class="card-body text-center">
                    <h6 class="card-title fw-bold">
                        <a href="chitietsp.php?id=<?= $item['id'] ?>" class="text-decoration-none text-dark">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                    </h6>
                    <p class="card-text text-danger fw-bold"><?= number_format($item['price']) ?>đ</p>
                </div>
                <div class="card-footer bg-white border-0 text-center pb-3">
                    <a href="chitietsp.php?id=<?= $item['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>