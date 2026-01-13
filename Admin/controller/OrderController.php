<?php
require_once __DIR__ . '/../model/AdminOrderModel.php';

class OrderController {
    private $orderModel;
    private $conn;

    public function __construct($connection) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = $connection;
        $this->orderModel = new AdminOrderModel($connection);
    }

    /**
     * Xử lý cập nhật trạng thái khi nhấn nút "Lưu"
     */
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            $order_id = intval($_POST['order_id']);
            $status = mysqli_real_escape_string($this->conn, $_POST['status']);
            
            // Debug log
            file_put_contents('debug_order.txt', date('Y-m-d H:i:s') . " - Updating Order ID: $order_id to Status: $status\n", FILE_APPEND);

            $result = $this->orderModel->updateOrderStatus($order_id, $status);
            
            if ($result) {
                file_put_contents('debug_order.txt', date('Y-m-d H:i:s') . " - Success\n", FILE_APPEND);
                
                // SỬA TẠI ĐÂY: Dùng JavaScript để redirect thay cho header()
                echo "<script>
                    alert('Cập nhật trạng thái thành công!');
                    window.location.href = 'quanlydonhang.php?status=success';
                </script>";
            } else {
                file_put_contents('debug_order.txt', date('Y-m-d H:i:s') . " - Failed: " . $this->conn->error . "\n", FILE_APPEND);
                
                echo "<script>
                    alert('Lỗi: không thể cập nhật!');
                    window.location.href = 'quanlydonhang.php?status=error';
                </script>";
            }
            exit(); 
        }
    }

    /**
     * Lấy danh sách đơn hàng để hiển thị
     */
    public function getAllOrders() {
        return $this->orderModel->getAllOrders();
    }
}