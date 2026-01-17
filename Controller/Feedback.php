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
        $_SESSION['swal_type'] = 'warning';
        $_SESSION['swal_title'] = 'Thông báo';
        $_SESSION['swal_message'] = 'Bạn đã đánh giá đơn hàng này rồi!';
        $_SESSION['swal_redirect'] = '../View/my_order.php';
        header("Location: ../View/my_order.php");
        exit();
    } else {
        if ($feedbackModel->addFeedback($user_id, $order_id, $rating, $comment)) {
            $_SESSION['swal_type'] = 'success';
            $_SESSION['swal_title'] = 'Thành công!';
            $_SESSION['swal_message'] = 'Cảm ơn bạn đã đánh giá!';
            $_SESSION['swal_redirect'] = '../View/my_order.php';
            header("Location: ../View/my_order.php");
            exit();
        } else {
            $_SESSION['swal_type'] = 'error';
            $_SESSION['swal_title'] = 'Lỗi!';
            $_SESSION['swal_message'] = 'Có lỗi xảy ra, vui lòng thử lại.';
            $_SESSION['swal_redirect'] = '../View/my_order.php';
            header("Location: ../View/my_order.php");
            exit();
        }
    }
} else {
    header("Location: ../View/my_order.php");
}
?>