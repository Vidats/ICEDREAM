<?php
require_once 'BaseModel.php';

class FeedbackModel extends BaseModel {

    protected function getTableName() {
        return 'feedbacks';
    }

    // Thêm đánh giá mới
    public function addFeedback($user_id, $order_id, $rating, $comment) {
        $sql = "INSERT INTO feedbacks (user_id, order_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiis", $user_id, $order_id, $rating, $comment);
        return $stmt->execute();
    }

    // Kiểm tra xem đơn hàng đã được đánh giá chưa
    public function hasFeedback($order_id) {
        $sql = "SELECT id FROM feedbacks WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Lấy tất cả đánh giá (Cho Admin) - Kèm tên user
  public function getAllFeedbacks() {
    // Sửa u.username thành u.full_name và dùng AS username để không phải sửa file View
    $sql = "SELECT f.*, u.full_name AS username 
            FROM feedbacks f 
            JOIN users u ON f.user_id = u.id 
            ORDER BY f.created_at DESC";
            
    $result = $this->conn->query($sql);
    $feedbacks = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $feedbacks[] = $row;
        }
    }
    return $feedbacks;
}

    // Xóa đánh giá (Cho Admin)
    public function deleteFeedback($id) {
        $sql = "DELETE FROM feedbacks WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Thống kê đánh giá (Cho Admin Dashboard)
    public function getRatingStatistics() {
        $stats = [
            'total' => 0,
            'average' => 0,
            'stars' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]
        ];

        // Lấy tổng số và trung bình
        $sql = "SELECT COUNT(*) as total, AVG(rating) as avg_rating FROM feedbacks";
        $result = $this->conn->query($sql);
        if ($row = $result->fetch_assoc()) {
            $stats['total'] = $row['total'];
            $stats['average'] = round($row['avg_rating'], 1);
        }

        // Lấy số lượng từng sao
        $sql_stars = "SELECT rating, COUNT(*) as count FROM feedbacks GROUP BY rating";
        $result_stars = $this->conn->query($sql_stars);
        while ($row = $result_stars->fetch_assoc()) {
            $stats['stars'][$row['rating']] = $row['count'];
        }

        return $stats;
    }
}
?>