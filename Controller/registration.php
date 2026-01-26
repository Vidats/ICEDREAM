<?php
session_start();
require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/db.php'; 

$userModel = new UserModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm_email'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];

    // 1. Kiểm tra email xác nhận
    if ($email !== $confirm_email) {
        header("Location: ../View/auth.php?tab=register&status=error&message=Email xác nhận không khớp.");
        exit();
    }

    // 2. Kiểm tra tuổi (phải đủ 12 tuổi)
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;

    if ($age < 12) {
        header("Location: ../View/auth.php?tab=register&status=error&message=Bạn phải đủ 12 tuổi trở lên để đăng ký.");
        exit();
    }
    
    // 3. Kiểm tra email đã tồn tại chưa
    if ($userModel->checkEmailExists($email)) {
        header("Location: ../View/auth.php?tab=register&status=error&message=Email đã tồn tại!");
        exit();
    } else {
        // 4. Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 5. Sử dụng Prepared Statement để Insert
        $sql = "INSERT INTO users (full_name, email, password, dob, role) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        // Bind 'dob' as a string, MySQL will handle the conversion from 'YYYY-MM-DD'
        $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $dob);
        
        if ($stmt->execute()) {
            header("Location: ../View/auth.php?tab=login&status=success&message=Đăng ký thành công!");
            exit();
        } else {
            header("Location: ../View/auth.php?tab=register&status=error&message=Lỗi hệ thống.");
            exit();
        }
    }
}
?>