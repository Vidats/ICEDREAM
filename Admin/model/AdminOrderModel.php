<?php
require_once __DIR__ . '/../../Model/db.php';
require_once __DIR__ . '/../../Model/BaseModel.php';

class AdminOrderModel extends BaseModel {
    
    protected function getTableName() {
        return 'orders';
    }

    /**
     * Lấy tất cả đơn hàng
     */
    public function getAllOrders() {
        // Tái sử dụng hàm chung của cha
        return $this->getAll();
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus($order_id, $status) {
        $order_id = intval($order_id);
        $status = $this->escape($status);
        $sql = "UPDATE orders SET status = '$status' WHERE id = $order_id";
        return $this->conn->query($sql);
    }

    /**
     * Thống kê sản phẩm bán chạy (Top 10)
     */
    public function getTopSellingProducts() {
        // Chỉ lấy từ đơn hàng không bị hủy
        $sql = "SELECT p.name, SUM(od.quantity) as total_sold
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                JOIN orders o ON od.order_id = o.id
                WHERE o.status != 'Đã hủy'
                GROUP BY od.product_id
                ORDER BY total_sold DESC
                LIMIT 10";
        
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Thống kê doanh thu theo danh mục
     */
/**
 * Thống kê doanh thu theo danh mục
 */
public function getRevenueByCategory() {
    // Đã đổi JOIN danhmuc thành JOIN categories để khớp với DB của bạn
    $sql = "SELECT c.name as category_name, SUM(od.quantity * od.price) as revenue
            FROM order_details od
            JOIN products p ON od.product_id = p.id
            JOIN categories c ON p.category_id = c.id
            JOIN orders o ON od.order_id = o.id
            WHERE o.status = 'Hoàn thành'
            GROUP BY c.name
            ORDER BY revenue DESC";
            
    $result = $this->conn->query($sql);
    
    if (!$result) {
        die("Lỗi truy vấn: " . $this->conn->error);
    }
    
    return $result->fetch_all(MYSQLI_ASSOC);
}
    /**
     * Thống kê trạng thái đơn hàng
     */
    public function getOrderStatusStats() {
        $sql = "SELECT status, COUNT(*) as count 
                FROM orders 
                GROUP BY status";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy thông tin đơn hàng theo ID
     */
    public function getOrderById($order_id) {
        // Tái sử dụng hàm của cha
        return $this->findById($order_id);
    }

    /**
     * Lấy chi tiết đơn hàng
     */
    public function getOrderDetails($order_id) {
        $order_id = intval($order_id);
        $sql = "SELECT od.*, p.name, p.image 
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = $order_id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Thống kê top 5 khách hàng chi tiêu nhiều nhất
     */
    /**
 * Thống kê top 5 khách hàng chi tiêu nhiều nhất
 */
public function getTopCustomers() {
    // Sửa u.fullname thành u.full_name
    $sql = "SELECT u.full_name, SUM(o.total_price) as total_spent
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.status = 'Hoàn thành'
            GROUP BY o.user_id, u.full_name
            ORDER BY total_spent DESC
            LIMIT 5";
            
    $result = $this->conn->query($sql);
    
    if (!$result) {
        die("Lỗi truy vấn: " . $this->conn->error);
    }
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

    /**
     * Thống kê tăng trưởng người dùng trong 12 tháng
     */
    public function getUserGrowth() {
        $sql = "SELECT DATE_FORMAT(created_at, '%m-%Y') as registration_month, COUNT(id) as new_user_count
                FROM users
                WHERE role != 1
                GROUP BY registration_month
                ORDER BY created_at ASC
                LIMIT 12";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}