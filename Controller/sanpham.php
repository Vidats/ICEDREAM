<?php
require_once '../Model/sanpham.php';
require_once '../Model/db.php';

$category_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Phân trang
$limit = 8;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$sanphamModel = new SanphamModel($conn);

$total_products = $sanphamModel->getProductsCount($category_id, $q);
$total_pages = ceil($total_products / $limit);

$result = $sanphamModel->getProducts($category_id, $q, $limit, $offset);

$categories = $sanphamModel->getAllCategories();
?>