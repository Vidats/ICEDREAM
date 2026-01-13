<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shop";

$conn = new mysqli($host, $user, $pass, $dbname);

// Kiểm tra lỗi
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Không echo gì ở đây
?>
