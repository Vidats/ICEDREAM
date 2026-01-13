<?php
include '../../Model/db.php';
include '../../Model/FeedbackModel.php'; // Reuse User model for simplicity, or create Admin specific one

$feedbackModel = new FeedbackModel($conn);

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($feedbackModel->deleteFeedback($id)) {
        echo "<script>alert('Xóa đánh giá thành công!'); window.location='quanlyfeedback.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa!'); window.location='quanlyfeedback.php';</script>";
    }
}

$feedbacks = $feedbackModel->getAllFeedbacks();

// Load View
include '../View/quanlyfeedback.php';
?>