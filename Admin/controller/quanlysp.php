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
    $category_id = intval($_POST['category_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = __DIR__ . "/../../image/" . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "INSERT INTO products (name, price, category_id, image, description, quantity) 
                    VALUES ('$name', '$price', '$category_id', '$image', '$description', $quantity)";
            if ($conn->query($sql)) {
                header("Location: ../View/quanlysp.php?message=Thêm món mới thành công");
            } else {
                header("Location: ../View/quanlysp.php?error=Lỗi database: " . $conn->error);
            }
        } else {
            header("Location: ../View/quanlysp.php?error=Lỗi tải ảnh lên thư mục image.");
        }
    } else {
        header("Location: ../View/quanlysp.php?error=Vui lòng chọn hình ảnh sản phẩm.");
    }
    exit();
}

// --- CHỨC NĂNG SỬA ---
if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category_id = intval($_POST['category_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = __DIR__ . "/../../image/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $sql = "UPDATE products SET name='$name', price='$price', category_id='$category_id', image='$image', description='$description', quantity=$quantity WHERE id=$id";
    } else {
        $sql = "UPDATE products SET name='$name', price='$price', category_id='$category_id', description='$description', quantity=$quantity WHERE id=$id";
    }

    if ($conn->query($sql)) {
        header("Location: ../View/quanlysp.php?message=Cập nhật sản phẩm thành công");
    } else {
        header("Location: ../View/quanlysp.php?error=Lỗi database: " . $conn->error);
    }
    exit();
}

// --- CHỨC NĂNG XÓA ---
if ($action == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // 1. Kiểm tra xem sản phẩm có trong đơn hàng nào không (Foreign Key constraint check)
    $check_orders = $conn->query("SELECT id FROM order_details WHERE product_id = $id LIMIT 1");
    if ($check_orders && $check_orders->num_rows > 0) {
        header("Location: ../View/quanlysp.php?error=Không thể xóa món này vì đã có khách hàng đặt mua. Bạn nên ẩn nó hoặc cập nhật số lượng về 0.");
        exit();
    }
    
    // 2. Lấy thông tin ảnh trước khi xóa bản ghi
    $res = $conn->query("SELECT image FROM products WHERE id = $id");
    $product = $res->fetch_assoc();
    
    // 3. Xóa các tham chiếu trong giỏ hàng (Cart records are transient and don't have FK in some versions)
    $conn->query("DELETE FROM cart WHERE product_id = $id");
    
    // 4. Xóa bản ghi trong database
    if ($conn->query("DELETE FROM products WHERE id = $id")) {
        // 5. Chỉ xóa file ảnh nếu đã xóa database thành công
        if ($product && !empty($product['image'])) {
            $file_path = __DIR__ . "/../../image/" . $product['image'];
            if (file_exists($file_path)) { 
                unlink($file_path); 
            }
        }
        header("Location: ../View/quanlysp.php?message=Đã xóa món ăn thành công");
    } else {
        header("Location: ../View/quanlysp.php?error=Lỗi khi xóa món: " . $conn->error);
    }
    exit();
}