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

<?php include 'footer.php'; ?>