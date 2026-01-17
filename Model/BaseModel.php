<?php
// Model/BaseModel.php

abstract class BaseModel {
    // Protected để các class con có thể truy cập
    protected $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    // --- TÍNH ĐA HÌNH (Polymorphism) ---
    // Buộc các class con phải định nghĩa tên bảng của riêng nó
    abstract protected function getTableName();

    // --- CÁC HÀM CHUNG (Inheritance) ---

    // Hàm escape chuỗi an toàn
    public function escape($value) {
        return mysqli_real_escape_string($this->conn, $value);
    }

    // Hàm thực thi Prepared Statement chung
    protected function queryPrepared($sql, $params = [], $types = "") {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Lỗi Prepare Statement: " . $this->conn->error);
        }

        if (!empty($params)) {
            if (empty($types)) {
                $types = str_repeat("s", count($params)); // Mặc định là string nếu không chỉ định
            }
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        
        // Nếu là câu lệnh SELECT
        if ($result) {
            return $result;
        }
        
        // Nếu là INSERT/UPDATE/DELETE
        return $stmt;
    }

    // Lấy tất cả dữ liệu (Hàm chung dùng cho mọi bảng)
    public function getAll() {
        $table = $this->getTableName();
        $sql = "SELECT * FROM $table ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Tìm theo ID (Hàm chung) - Đã nâng cấp Prepared Statement
    public function findById($id) {
        $table = $this->getTableName();
        $sql = "SELECT * FROM $table WHERE id = ?";
        $result = $this->queryPrepared($sql, [$id], "i");
        return $result->fetch_assoc();
    }

    // Đếm tổng số bản ghi (Hàm chung)
    public function countAll() {
        $table = $this->getTableName();
        $sql = "SELECT COUNT(*) as total FROM $table";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
?>