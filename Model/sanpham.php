<?php
require_once 'db.php';
require_once 'BaseModel.php';

class SanphamModel extends BaseModel {

    protected function getTableName() {
        return 'products';
    }

    /**
     * Lấy danh sách sản phẩm có bộ lọc category
     */
    public function getProducts($category = '', $search = '') {
        $sql = "SELECT * FROM products";
        $clauses = [];

        if (!empty($category)) {
            $category = $this->escape($category);
            $clauses[] = "category = '$category'";
        }

        if (!empty($search)) {
            $searchEsc = $this->escape($search);
            $clauses[] = "name LIKE '%$searchEsc%'";
        }

        if (!empty($clauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }

        $sql .= " ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    /**
     * Lấy sản phẩm hot/mới
     */
    public function getHotProducts($limit = 8) {
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $limit";
        return $this->conn->query($sql);
    }

    /**
     * Lấy sản phẩm theo ID
     */
    public function getProductById($id) {
        // Tái sử dụng hàm chung của cha
        return $this->findById($id);
    }
}