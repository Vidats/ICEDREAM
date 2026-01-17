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
            


            $result = $this->orderModel->updateOrderStatus($order_id, $status);
            
            if ($result) {

                
                $_SESSION['swal_type'] = 'success';
                $_SESSION['swal_title'] = 'Thành công!';
                $_SESSION['swal_message'] = 'Cập nhật trạng thái thành công!';
                header("Location: quanlydonhang.php");
                exit();
            } else {

                
                $_SESSION['swal_type'] = 'error';
                $_SESSION['swal_title'] = 'Lỗi!';
                $_SESSION['swal_message'] = 'Lỗi: không thể cập nhật!';
                header("Location: quanlydonhang.php");
                exit();
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