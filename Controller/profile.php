<?php
session_start();
require_once '../Model/db.php';
require_once '../Model/UserModel.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/auth.php?tab=login");
    exit();
}

$userModel = new UserModel($conn);
$user_id = $_SESSION['user_id'];
$message = "";
$status = "";

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate cơ bản
    if (empty($full_name) || empty($email)) {
        $status = "error";
        $message = "Vui lòng điền đầy đủ họ tên và email.";
    } else {
        // Nếu người dùng nhập mật khẩu mới thì cập nhật, không thì thôi
        $new_pass = !empty($password) ? $password : null;
        
        if ($userModel->updateInfo($user_id, $full_name, $email, $new_pass)) {
            // Cập nhật lại session name nếu đổi tên
            $_SESSION['full_name'] = $full_name;
            $status = "success";
            $message = "Cập nhật thông tin thành công!";
        } else {
            $status = "error";
            $message = "Có lỗi xảy ra, vui lòng thử lại.";
        }
    }
    // Redirect về View profile kèm thông báo
    header("Location: ../View/profile.php?status=$status&message=" . urlencode($message));
    exit();
} else {
    // Nếu truy cập trực tiếp Controller mà không phải POST, redirect về View
    header("Location: ../View/profile.php");
    exit();
}
?>