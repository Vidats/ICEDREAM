<?php
session_start();
require_once '../Model/db.php';
require_once '../Model/FeedbackModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $order_id = $_POST['order_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $feedbackModel = new FeedbackModel($conn);

    if ($feedbackModel->hasFeedback($order_id)) {
        echo "<script>alert('Bạn đã đánh giá đơn hàng này rồi!'); window.location='../View/my_order.php';</script>";
    } else {
        if ($feedbackModel->addFeedback($user_id, $order_id, $rating, $comment)) {
            echo "<script>alert('Cảm ơn bạn đã đánh giá!'); window.location='../View/my_order.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra, vui lòng thử lại.'); window.location='../View/my_order.php';</script>";
        }
    }
} else {
    header("Location: ../View/my_order.php");
}
?>