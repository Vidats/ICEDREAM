<?php
include '../../Model/db.php';
include '../../Model/FeedbackModel.php'; // Reuse User model for simplicity, or create Admin specific one

$feedbackModel = new FeedbackModel($conn);

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($feedbackModel->deleteFeedback($id)) {
        $_SESSION['swal_type'] = 'success';
        $_SESSION['swal_title'] = 'Thành công!';
        $_SESSION['swal_message'] = 'Xóa đánh giá thành công!';
        header("Location: quanlyfeedback.php");
        exit();
    } else {
        $_SESSION['swal_type'] = 'error';
        $_SESSION['swal_title'] = 'Lỗi!';
        $_SESSION['swal_message'] = 'Lỗi khi xóa!';
        header("Location: quanlyfeedback.php");
        exit();
    }
}

$feedbacks = $feedbackModel->getAllFeedbacks();

// Load View
include '../View/quanlyfeedback.php';
?>