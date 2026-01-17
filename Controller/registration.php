<?php
session_start();
require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/db.php'; 

$userModel = new UserModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    
    // 1. Kiểm tra email đã tồn tại chưa (Dùng Prepared Statement thông qua Model)
    if ($userModel->checkEmailExists($email)) {
        header("Location: ../View/form.php?tab=register&status=error&message=Email đã tồn tại!");
        exit();
    } else {
        // 2. Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Sử dụng Prepared Statement để Insert
        $sql = "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);
        
        if ($stmt->execute()) {
            header("Location: ../View/form.php?tab=login&status=success&message=Đăng ký thành công!");
            exit();
        } else {
            header("Location: ../View/form.php?tab=register&status=error&message=Lỗi hệ thống.");
            exit();
        }
    }
}
?>