<?php
require_once '../Model/sanpham.php';
require_once '../Model/db.php';

$cat = isset($_GET['cat']) ? $_GET['cat'] : '';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$sanphamModel = new SanphamModel($conn);
$result = $sanphamModel->getProducts($cat, $q);
?>