<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/BaseModel.php';

class CouponModel extends BaseModel {
    
    protected function getTableName() {
        return 'coupons';
    }

    /**
     * Kiểm tra mã giảm giá có hợp lệ không
     */
    public function checkCoupon($code, $total_order_value = 0) {
        $code = $this->escape($code);
        $today = date('Y-m-d');

        $sql = "SELECT * FROM coupons 
                WHERE code = '$code' 
                AND min_order_value <= $total_order_value
                AND (expiration_date IS NULL OR expiration_date >= '$today')
                LIMIT 1";
        
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }

    /**
     * Lấy tất cả các coupon còn hạn và đủ điều kiện giá trị đơn hàng
     */
    public function getAvailableCoupons($total_order_value = 0) {
        $today = date('Y-m-d');
        
        $sql = "SELECT * FROM coupons 
                WHERE min_order_value <= $total_order_value
                AND (expiration_date IS NULL OR expiration_date >= '$today')";
        
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Tự động tìm coupon tốt nhất có thể áp dụng dựa trên tổng tiền
     */
    public function getAutoApplicableCoupon($total) {
        $today = date('Y-m-d');
        
        $sql = "SELECT code, discount_percent, min_order_value FROM coupons 
                WHERE ? >= min_order_value 
                AND (expiration_date IS NULL OR expiration_date >= ?)
                ORDER BY min_order_value DESC 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            // Handle error, e.g., log it
            return null;
        }
        
        $stmt->bind_param("ds", $total, $today);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        $stmt->close();
        return null;
    }
}