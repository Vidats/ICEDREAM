<?php
require_once '../Model/db.php';
require_once '../Model/UserModel.php';
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

$userModel = new UserModel($conn);

// XỬ LÝ GỬI OTP
if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];
    
    if ($userModel->checkEmailExists($email)) {
        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $userModel->saveOTP($email, $otp, $expiry);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vidat112296@gmail.com'; 
            $mail->Password   = 'gces dozw uztu fkpb';   
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('vidat112296@gmail.com', 'Tiệm Kem ICEDREAM');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'MÃ OTP KHÔI PHỤC MẬT KHẨU - ICEDREAM';
            $mail->Body    = "<div style='border: 1px solid #ff85a2; padding: 20px;'>
                                <h2>Mã OTP của bạn là: <span style='color: #d63384;'>$otp</span></h2>
                                <p>Hiệu lực trong 5 phút.</p>
                              </div>";

            $mail->send();
            header("Location: ../View/verify-otp.php?email=" . $email);
            exit();
        } catch (Exception $e) {
            $_SESSION['swal_type'] = 'error';
            $_SESSION['swal_title'] = 'Lỗi!';
            $_SESSION['swal_message'] = 'Lỗi gửi mail';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        $_SESSION['swal_type'] = 'error';
        $_SESSION['swal_title'] = 'Lỗi!';
        $_SESSION['swal_message'] = 'Email không tồn tại!';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

// XỬ LÝ XÁC THỰC OTP VÀ ĐỔI MẬT KHẨU
if (isset($_POST['verify'])) {
    $email = trim($_POST['email']);
    $otp = trim($_POST['otp']);
    $new_pass = $_POST['new_password'];

    $user = $userModel->checkOTP($email, $otp);

    if ($user) {
        $now = time();
        $expiry = strtotime($user['otp_expiry']);

        if ($now <= $expiry) {
            $userModel->updatePassword($email, $new_pass);
            $_SESSION['swal_type'] = 'success';
            $_SESSION['swal_title'] = 'Thành công!';
            $_SESSION['swal_message'] = 'Đổi mật khẩu thành công!';
            auth.php;
            header("Location: ../View/form.php");
            exit();
        } else {
            $_SESSION['swal_type'] = 'error';
            $_SESSION['swal_title'] = 'Lỗi!';
            $_SESSION['swal_message'] = 'Mã OTP này đã hết hạn!';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        $_SESSION['swal_type'] = 'error';
        $_SESSION['swal_title'] = 'Lỗi!';
        $_SESSION['swal_message'] = 'Mã OTP không chính xác!';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>