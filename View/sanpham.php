<?php 
include 'header.php'; 

// GỌI CONTROLLER để xử lý dữ liệu trước khi hiển thị
require_once '../Controller/sanpham.php'; 
?>
<link rel="stylesheet" href="../Content/sanpham.css">

<div class="container py-5">
  <div class="text-center mb-5">
    <h2 class="section-title d-inline-block pastel-text">Menu Kem Icedream</h2>
    <p class="mt-3 sub-title">Vị ngon – tan chảy mọi trái tim 💞</p>
</div>

    <div class="category-menu mb-5">
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="sanpham.php" class="cat-item <?= $category_id == 0 ? 'active' : '' ?>">
            <i class="fas fa-store"></i> <span>TẤT CẢ</span>
        </a>
        <?php if ($categories): ?>
            <?php while($row = $categories->fetch_assoc()): ?>
                <a href="sanpham.php?cat_id=<?= $row['id'] ?>" class="cat-item <?= $category_id == $row['id'] ? 'active' : '' ?>">
                    <i class="fas fa-ice-cream"></i> <span><?= htmlspecialchars(strtoupper($row['name'])) ?></span>
                </a>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-center">
            <form class="d-flex w-100 w-md-50" method="GET" action="sanpham.php">
                <input type="hidden" name="cat_id" value="<?= htmlspecialchars($category_id) ?>">
                <input type="search" id="search-input" name="q" class="form-control me-2" placeholder="Tìm sản phẩm theo tên..." value="<?= isset($q) ? htmlspecialchars($q) : '' ?>">
                <button class="btn btn-outline-primary" type="submit">Tìm</button>
            </form>
        </div>
    </div>

    <div class="row g-4" id="product-list"> 
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
            echo "<p class='text-center w-100'>Hiện chưa có sản phẩm trong danh mục này.</p>";
        }
        ?>
    </div>

    <!-- Phân trang -->
    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-5">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?cat_id=<?= $category_id ?>&q=<?= urlencode($q) ?>&page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?cat_id=<?= $category_id ?>&q=<?= urlencode($q) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?cat_id=<?= $category_id ?>&q=<?= urlencode($q) ?>&page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const productList = document.getElementById('product-list');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;
        const urlParams = new URLSearchParams(window.location.search);
        const cat_id = urlParams.get('cat_id') || '0';

        timeout = setTimeout(() => {
            fetch(`../Controller/live_search.php?q=${encodeURIComponent(query)}&cat_id=${encodeURIComponent(cat_id)}`)
                .then(response => response.text())
                .then(html => {
                    productList.innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
        }, 300); // Debounce for 300ms
    });
});
</script>

<?php include 'footer.php'; ?>