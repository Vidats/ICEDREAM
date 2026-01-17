<?php
require_once '../Model/sanpham.php';
require_once '../Model/db.php';

$category_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$sanphamModel = new SanphamModel($conn);
$result = $sanphamModel->getProducts($category_id, $q);

$categories = $sanphamModel->getAllCategories();
?>