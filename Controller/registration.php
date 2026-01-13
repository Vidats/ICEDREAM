<?php
session_start();

// Kết nối DB từ thư mục Model
include '../Model/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra kết nối DB
    if (!$conn) {
        die("Kết nối database thất bại.");
    }

    // Lấy dữ liệu và dùng mysqli_real_escape_string để bảo mật cơ bản
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // LẤY MẬT KHẨU THƯỜNG
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    
    // 1. Kiểm tra email đã tồn tại chưa
    $check_email = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        // Nếu trùng email, quay lại trang form/auth
        header("Location: ../View/form.php?tab=register&status=error&message=Email đã tồn tại!");
        exit();
    } else {

        $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('$full_name', '$email', '$password', 0)";
        
        if ($conn->query($sql) === TRUE) {
            // Đăng ký xong, quay lại tab đăng nhập
            header("Location: ../View/form.php?tab=login&status=success&message=Đăng ký thành công!");
            exit();
        } else {
            header("Location: ../View/form.php?tab=register&status=error&message=Lỗi hệ thống: " . $conn->error);
            exit();
        }
    }
}
?>