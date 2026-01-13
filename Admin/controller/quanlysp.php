<?php
session_start();
require_once '../../Model/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    exit("Access Denied");
}

$action = $_GET['action'] ?? '';

// Đảm bảo cột `quantity` tồn tại trong bảng `products`. Nếu chưa có, thêm cột với giá trị mặc định 0.
// Thực hiện tại runtime để tránh lỗi khi schema chưa được cập nhật.
$checkQtyCol = $conn->query("SHOW COLUMNS FROM products LIKE 'quantity'");
if ($checkQtyCol && $checkQtyCol->num_rows === 0) {
    $conn->query("ALTER TABLE products ADD COLUMN quantity INT DEFAULT 0");
}
// --- CHỨC NĂNG THÊM ---
if ($action == 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0; // Mặc định 0
    
    $image = $_FILES['image']['name'];
    $target = "../../image/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO products (name, price, category, image, description, quantity) 
                VALUES ('$name', '$price', '$category', '$image', '$description', $quantity)";
        $conn->query($sql);
    }
    header("Location: ../View/quanlysp.php");
    exit();
}

// --- CHỨC NĂNG SỬA ---
if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../../image/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        // Cập nhật kèm ảnh, mô tả và số lượng
        $sql = "UPDATE products SET name='$name', price='$price', category='$category', image='$image', description='$description', quantity=$quantity WHERE id=$id";
    } else {
        // Cập nhật mô tả, số lượng nhưng giữ ảnh cũ
        $sql = "UPDATE products SET name='$name', price='$price', category='$category', description='$description', quantity=$quantity WHERE id=$id";
    }

    $conn->query($sql);
    header("Location: ../View/quanlysp.php");
    exit();
}

// --- CHỨC NĂNG XÓA --- (Giữ nguyên)
if ($action == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT image FROM products WHERE id = $id");
    if ($product = $res->fetch_assoc()) {
        $file_path = "../../image/" . $product['image'];
        if (file_exists($file_path)) { unlink($file_path); }
    }
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: ../View/quanlysp.php");
    exit();
}