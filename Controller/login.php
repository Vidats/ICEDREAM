<?php
session_start();
require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/db.php'; 

$userModel = new UserModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Sử dụng Prepared Statement để lấy thông tin user
    $sql = "SELECT * FROM users WHERE email = ? AND deleted_at IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 2. KIỂM TRA MẬT KHẨU BẰNG password_verify
        if (password_verify($password, $user['password']) || $password == $user['password']) {
            // Lưu ý: $password == $user['password'] là để hỗ trợ các tài khoản cũ chưa hash. 
            // Sau này bạn nên chạy script hash lại toàn bộ DB.
            
            if (isset($user['status']) && $user['status'] == 0) {
                header("Location: ../View/form.php?tab=login&status=error&message=Tài khoản đã bị khóa!");
                exit();
            }

            // Bảo mật Session
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role']; 
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_name'] = $user['full_name']; 

            if ($user['role'] == 1) {
                header("Location: ../Admin/index.php"); 
            } else {
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