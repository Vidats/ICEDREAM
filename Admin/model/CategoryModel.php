<?php
// 1. Nhúng file kết nối và lớp cha
require_once __DIR__ . '/../../Model/db.php';
require_once __DIR__ . '/../../Model/BaseModel.php';

class CategoryModel extends BaseModel {

    public function __construct() {
        // Vì db.php của bạn tạo ra biến toàn cục $conn
        // Chúng ta lấy biến đó và truyền vào lớp cha (BaseModel)
        global $conn; 
        parent::__construct($conn);
    }

    // Định nghĩa tên bảng cho BaseModel sử dụng
    protected function getTableName() {
        // Nếu trong database của bạn bảng tên là 'categories' thì để nguyên
        // Nếu tên là 'danhmuc' thì sửa lại thành 'danhmuc'
        return 'categories'; 
    }

    /**
     * Lấy tất cả danh mục
     */
    public function getAllCategories() {
        return $this->getAll(); // Sử dụng hàm getAll() của BaseModel
    }

    /**
     * Thêm danh mục mới (Sử dụng Prepared Statement từ BaseModel để bảo mật)
     */
    public function addCategory($name, $description = '') {
        $table = $this->getTableName();
        $sql = "INSERT INTO $table (name, description) VALUES (?, ?)";
        return $this->queryPrepared($sql, [$name, $description], "ss");
    }

    /**
     * Cập nhật danh mục
     */
    public function updateCategory($id, $name, $description = '') {
        $table = $this->getTableName();
        $sql = "UPDATE $table SET name = ?, description = ? WHERE id = ?";
        return $this->queryPrepared($sql, [$name, $description, $id], "ssi");
    }

    /**
     * Xóa danh mục
     */
    public function deleteCategory($id) {
        $table = $this->getTableName();
        $sql = "DELETE FROM $table WHERE id = ?";
        return $this->queryPrepared($sql, [$id], "i");
    }
}
?>