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
        $sql = "UPDATE users SET otp_code = ?, otp_expiry = ? WHERE email = ?";
        return $this->queryPrepared($sql, [$otp, $expiry, $email], "sss");
    }

    /**
     * Kiểm tra mã OTP
     */
    public function checkOTP($email, $otp) {
        $sql = "SELECT * FROM users WHERE email = ? AND otp_code = ?";
        $result = $this->queryPrepared($sql, [$email, $otp], "ss");
        return $result->fetch_assoc();
    }

    /**
     * Cập nhật mật khẩu mới
     */
    public function updatePassword($email, $new_pass) {
        $sql = "UPDATE users SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE email = ?";
        return $this->queryPrepared($sql, [$new_pass, $email], "ss");
    }

    /**
     * Kiểm tra email tồn tại
     */
    public function checkEmailExists($email) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $res = $this->queryPrepared($sql, [$email], "s");
        return ($res->num_rows > 0);
    }

    /**
     * Lấy thông tin user theo ID
     */
    public function getUserById($user_id) {
        return $this->findById($user_id);
    }

    /**
     * Cập nhật thông tin tài khoản
     */
    public function updateInfo($user_id, $full_name, $email, $new_password = null) {
        if ($new_password) {
            $sql = "UPDATE users SET full_name = ?, email = ?, password = ? WHERE id = ?";
            return $this->queryPrepared($sql, [$full_name, $email, $new_password, $user_id], "sssi");
        } else {
            $sql = "UPDATE users SET full_name = ?, email = ? WHERE id = ?";
            return $this->queryPrepared($sql, [$full_name, $email, $user_id], "ssi");
        }
    }
}