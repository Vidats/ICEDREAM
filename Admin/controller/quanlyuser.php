<?php
session_start();
require_once '../../Model/db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    exit("Access Denied");
}

$action = $_GET['action'] ?? '';

// --- XỬ LÝ KHÓA / MỞ KHÓA ---
if ($action == 'toggle_status' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Lấy trạng thái hiện tại
    $res = $conn->query("SELECT status FROM users WHERE id = $id");
    $user = $res->fetch_assoc();
    
    // Đảo ngược trạng thái
    $new_status = ($user['status'] == 1) ? 0 : 1;
    
    $conn->query("UPDATE users SET status = $new_status WHERE id = $id");
    header("Location: ../View/quanlyuser.php?message=Cập nhật trạng thái thành công");
    exit();
}

// --- XỬ LÝ XÓA NGƯỜI DÙNG ---
if ($action == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Không cho phép Admin tự xóa chính mình
    if ($id == $_SESSION['user_id']) {
        header("Location: ../View/quanlyuser.php?error=Bạn không thể tự xóa chính mình");
        exit();
    }
    
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: ../View/quanlyuser.php?message=Đã xóa người dùng");
    exit();
}