<?php
session_start();
include '../Model/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Mật khẩu người dùng nhập

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 1. KIỂM TRA MẬT KHẨU
        if ($password == $user['password']) {
            
            // 2. KIỂM TRA TRẠNG THÁI TÀI KHOẢN (KHÓA/MỞ)
            // Nếu cột status trong DB của bạn bằng 0 nghĩa là đã bị Admin khóa
            if (isset($user['status']) && $user['status'] == 0) {
                header("Location: ../View/form.php?tab=login&status=error&message=Tài khoản của bạn đã bị khóa bởi quản trị viên!");
                exit();
            }

            // 3. THIẾT LẬP SESSION KHI ĐĂNG NHẬP THÀNH CÔNG
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role']; 
            
            // Lưu full_name để hiển thị lên Header ("Hi, Tên Người Dùng")
            $_SESSION['full_name'] = $user['full_name'];
            
            // Lưu thêm user_name nếu các trang Admin cũ của bạn đang dùng biến này
            $_SESSION['user_name'] = $user['full_name']; 

            // 4. PHÂN QUYỀN ĐIỀU HƯỚNG
            if ($user['role'] == 1) {
                // Nếu là Admin, vào trang quản trị
                header("Location: ../Admin/index.php"); 
            } else {
                // Nếu là khách hàng, vào trang chủ người dùng
                header("Location: ../View/index.php"); 
            }
            exit();

        } else {
            header("Location: ../View/form.php?tab=login&status=error&message=Mật khẩu không chính xác!");
            exit();
        }
    } else {
        header("Location: ../View/form.php?tab=login&status=error&message=Email không tồn tại!");
        exit();
    }
}
?>