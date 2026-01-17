<?php
// Giữ nguyên các khai báo giống file quên mật khẩu của bạn
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

// Kiểm tra xem có phải bấm nút gửi từ form contact không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Lấy dữ liệu từ form liên hệ
    $customer_name = $_POST['name'];
    $customer_email = $_POST['email'];
    $customer_message = $_POST['message'];

    $mail = new PHPMailer(true);
    try {
        // 2. Cấu hình Server (Dùng lại cấu hình chạy tốt của bạn)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'vidat112296@gmail.com'; 
        $mail->Password   = 'gces dozw uztu fkpb';   
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // 3. Thiết lập gửi và nhận
        // Người gửi: Để email của bạn (tránh bị Gmail đánh dấu spam)
        $mail->setFrom('vidat112296@gmail.com', 'Hệ Thống Website ICEDREAM');
        
        // Người nhận: Là CHÍNH BẠN (Để bạn đọc được yêu cầu khách gửi)
        $mail->addAddress('vidat112296@gmail.com'); 
        
        // Trả lời cho: Email của khách (để khi bạn bấm "Reply" nó sẽ gửi thẳng cho khách)
        $mail->addReplyTo($customer_email, $customer_name);

        // 4. Nội dung Email
        $mail->isHTML(true);
        $mail->Subject = "LIÊN HỆ MỚI TỪ KHÁCH HÀNG: $customer_name";
        $mail->Body    = "
            <div style='border: 1px solid #ff85a2; padding: 20px; font-family: Arial, sans-serif;'>
                <h2 style='color: #d63384;'>Bạn có tin nhắn mới từ khách hàng</h2>
                <p><strong>Họ tên:</strong> $customer_name</p>
                <p><strong>Email khách:</strong> $customer_email</p>
                <p><strong>Nội dung:</strong></p>
                <div style='background: #f9f9f9; padding: 15px; border-radius: 5px;'>
                    " . nl2br($customer_message) . "
                </div>
                <hr style='border: 0; border-top: 1px solid #eee; margin-top: 20px;'>
                <p style='font-size: 12px; color: #888;'>Tin nhắn gửi từ website ICEDREAM</p>
            </div>";

        $mail->send();
        
        // Gửi xong thông báo và quay về trang contact
        $_SESSION['swal_type'] = 'success';
        $_SESSION['swal_title'] = 'Thành công!';
        $_SESSION['swal_message'] = 'Cảm ơn bạn! Tin nhắn đã được gửi thành công.';
        $_SESSION['swal_redirect'] = '../View/lienhe.php';
        header("Location: ../View/lienhe.php");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['swal_type'] = 'error';
        $_SESSION['swal_title'] = 'Lỗi!';
        $_SESSION['swal_message'] = "Lỗi gửi mail: {$mail->ErrorInfo}";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    header("Location: ../View/lienhe.php");
}
?>