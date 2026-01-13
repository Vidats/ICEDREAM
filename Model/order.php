<?php
require_once 'db.php';
require_once 'BaseModel.php';

class OrderModel extends BaseModel {
    
    protected function getTableName() {
        return 'orders';
    }

    /**
     * Lưu đơn hàng mới
     */
    public function createOrder($user_id, $full_name, $email, $address, $total_price) {
        $full_name = $this->escape($full_name);
        $email = $this->escape($email);
        $address = $this->escape($address);
        $user_id = intval($user_id);
        
        $sql = "INSERT INTO orders (user_id, full_name, email, address, total_price, status, created_at) 
                VALUES ('$user_id', '$full_name', '$email', '$address', '$total_price', 'Đang xử lý', NOW())";
        
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        }
        return false;
    }

    /**
     * Lấy danh sách đơn hàng của một người dùng cụ thể
     */
    public function getOrdersByUser($user_id) {
        $user_id = intval($user_id);
        $sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy TẤT CẢ đơn hàng (Dùng cho trang Admin)
     */
    public function getAllOrders() {
        // Tái sử dụng hàm của cha để thể hiện tính kế thừa/đa hình
        return $this->getAll();
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus($order_id, $new_status) {
        $order_id = intval($order_id);
        $new_status = $this->escape($new_status);
        $sql = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
        return $this->conn->query($sql);
    }

    /**
     * Giảm số lượng sản phẩm sau khi bán
     */
    public function decreaseProductQuantity($product_id, $quantity) {
        $product_id = intval($product_id);
        $quantity = intval($quantity);
        
        // Kiểm tra xem quantity có đủ không
        $check = $this->conn->query("SELECT quantity FROM products WHERE id = $product_id");
        if ($check && $check->num_rows > 0) {
            $row = $check->fetch_assoc();
            if ($row['quantity'] >= $quantity) {
                return $this->conn->query("UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id");
            }
        }
        return false;
    }

    /**
     * Thống kê doanh thu: Tính tổng tiền các đơn hàng "Hoàn thành"
     */
    public function getRevenueStatistics() {
        $sql = "SELECT SUM(total_price) as total_revenue FROM orders WHERE status = 'Hoàn thành'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total_revenue'] ?? 0;
    }

    /**
     * Thêm chi tiết đơn hàng
     */
    public function addOrderDetail($order_id, $product_id, $price, $quantity) {
        $order_id = intval($order_id);
        $product_id = intval($product_id);
        $price = floatval($price);
        $quantity = intval($quantity);

        $sql = "INSERT INTO order_details (order_id, product_id, price, quantity) 
                VALUES ('$order_id', '$product_id', '$price', '$quantity')";
        return $this->conn->query($sql);
    }

    /**
     * Lấy chi tiết đơn hàng (các sản phẩm trong đơn)
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
     * Lấy thông tin một đơn hàng theo ID
     */
    public function getOrderById($order_id) {
        return $this->findById($order_id);
    }
}