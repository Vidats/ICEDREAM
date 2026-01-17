<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/BaseModel.php';

class CouponModel extends BaseModel {
    
    protected function getTableName() {
        return 'coupons';
    }

    /**
     * Kiểm tra mã giảm giá có hợp lệ không (ĐÃ BỎ CHECK GIÁ TỐI THIỂU)
     */
    public function checkCoupon($code, $total_order_value = 0) {
        $code = $this->escape($code);
        $today = date('Y-m-d');

        // Đã xóa điều kiện: AND min_order_value <= $total_order_value
        $sql = "SELECT * FROM coupons 
                WHERE code = '$code' 
                AND (expiration_date IS NULL OR expiration_date >= '$today')
                LIMIT 1";
        
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }

    /**
     * Lấy tất cả các coupon còn hạn (Gợi ý cho mọi đơn hàng)
     */
    public function getAvailableCoupons($total_order_value = 0) {
        $today = date('Y-m-d');
        
        // Chỉ kiểm tra ngày hết hạn, không kiểm tra giá tiền tối thiểu nữa
        $sql = "SELECT * FROM coupons 
                WHERE (expiration_date IS NULL OR expiration_date >= '$today')";
        
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}