<?php
require_once 'db.php';
require_once 'BaseModel.php';

class SanphamModel extends BaseModel {

    protected function getTableName() {
        return 'products';
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->conn->query($sql);
    }

    /**
     * Lấy danh sách sản phẩm có bộ lọc category
     */
    public function getProducts($category_id = 0, $search = '') {
        $sql = "SELECT * FROM products";
        $clauses = [];

        if (!empty($category_id)) {
            $category_id = intval($category_id);
            $clauses[] = "category_id = '$category_id'";
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

    /**
     * AI/Smart Recommendation System
     * 1. Ưu tiên: Lấy các sản phẩm thường được mua cùng nhau (Collaborative Filtering)
     * 2. Fallback: Lấy các sản phẩm cùng danh mục (Content-based)
     */
    public function getRecommendedProducts($current_product_id, $current_category_id, $limit = 4) {
        $current_product_id = intval($current_product_id);
        $limit = intval($limit);
        $recommendations = [];
        $exclude_ids = [$current_product_id];

        // 1. Tìm sản phẩm mua cùng (Association Rule Mining)
        $sql_collab = "
            SELECT p.*, COUNT(od2.product_id) as freq
            FROM order_details od1
            JOIN order_details od2 ON od1.order_id = od2.order_id
            JOIN products p ON od2.product_id = p.id
            WHERE od1.product_id = $current_product_id 
            AND od2.product_id != $current_product_id
            GROUP BY p.id
            ORDER BY freq DESC
            LIMIT $limit
        ";

        $result_collab = $this->conn->query($sql_collab);
        if ($result_collab) {
            while ($row = $result_collab->fetch_assoc()) {
                $recommendations[] = $row;
                $exclude_ids[] = $row['id'];
            }
        }

        // 2. Nếu chưa đủ limit, lấy thêm sản phẩm cùng category (Content-based)
        $slots_left = $limit - count($recommendations);
        if ($slots_left > 0 && !empty($current_category_id)) {
            $exclude_str = implode(',', $exclude_ids);
            $current_category_id = intval($current_category_id);
            
            $sql_content = "
                SELECT * FROM products 
                WHERE category_id = '$current_category_id' 
                AND id NOT IN ($exclude_str)
                ORDER BY RAND()
                LIMIT $slots_left
            ";
            
            $result_content = $this->conn->query($sql_content);
            if ($result_content) {
                while ($row = $result_content->fetch_assoc()) {
                    $recommendations[] = $row;
                    $exclude_ids[] = $row['id'];
                }
            }
        }

        // 3. Nếu vẫn chưa đủ (do category ít sp), lấy thêm sản phẩm random bất kỳ
        $slots_left = $limit - count($recommendations);
        if ($slots_left > 0) {
            $exclude_str = implode(',', $exclude_ids);
            
            $sql_random = "SELECT * FROM products WHERE id NOT IN ($exclude_str) ORDER BY RAND() LIMIT $slots_left";
            $result_random = $this->conn->query($sql_random);
            if ($result_random) {
                while ($row = $result_random->fetch_assoc()) {
                    $recommendations[] = $row;
                }
            }
        }

        return $recommendations;
    }
}