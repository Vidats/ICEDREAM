<?php
require_once 'db.php';
require_once 'BaseModel.php';

class UserModel extends BaseModel {
    
    // ĐỊNH NGHĨA TÊN BẢNG CHO BASEMODEL
    protected function getTableName() {
        return 'users';
    }

    /**
     * Lưu mã OTP và thời gian hết hạn
     */
    public function saveOTP($email, $otp, $expiry) {
        $email = $this->escape($email);
        $sql = "UPDATE users SET otp_code = '$otp', otp_expiry = '$expiry' WHERE email = '$email'";
        return $this->conn->query($sql);
    }

    /**
     * Kiểm tra mã OTP
     */
    public function checkOTP($email, $otp) {
        $email = $this->escape($email);
        $otp = $this->escape($otp);
        $sql = "SELECT * FROM users WHERE email='$email' AND otp_code='$otp'";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    /**
     * Cập nhật mật khẩu mới
     */
    public function updatePassword($email, $new_pass) {
        $email = $this->escape($email);
        $sql = "UPDATE users SET password='$new_pass', otp_code=NULL, otp_expiry=NULL WHERE email='$email'";
        return $this->conn->query($sql);
    }

    /**
     * Kiểm tra email tồn tại
     */
    public function checkEmailExists($email) {
        $email = $this->escape($email);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = $this->conn->query($sql);
        return ($res->num_rows > 0);
    }

    /**
     * Lấy thông tin user theo ID
     */
    public function getUserById($user_id) {
        // Có thể dùng $this->findById($user_id) của cha, nhưng giữ lại để tương thích code cũ
        return $this->findById($user_id);
    }

    /**
     * Cập nhật thông tin tài khoản
     */
    public function updateInfo($user_id, $full_name, $email, $new_password = null) {
        $user_id = intval($user_id);
        $full_name = $this->escape($full_name);
        $email = $this->escape($email);

        if ($new_password) {
            // Nếu có đổi mật khẩu
            $sql = "UPDATE users SET full_name='$full_name', email='$email', password='$new_password' WHERE id=$user_id";
        } else {
            // Nếu không đổi mật khẩu
            $sql = "UPDATE users SET full_name='$full_name', email='$email' WHERE id=$user_id";
        }
        return $this->conn->query($sql);
    }
}