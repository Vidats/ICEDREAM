<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/BaseModel.php';

class GiohangModel extends BaseModel {
    
    protected function getTableName() {
        return 'cart';
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */

    
public function addToCart($id, $soluong) {
    $user_id = $_SESSION['user_id'];
    $id = intval($id);
    $soluong = intval($soluong);
    
    // Kiểm tra quantity sản phẩm trong kho
    $product = $this->conn->query("SELECT quantity FROM products WHERE id = $id");
    if (!$product || $product->num_rows === 0) {
        return false; // Sản phẩm không tồn tại
    }
    $prod_row = $product->fetch_assoc();
    $available_qty = $prod_row['quantity'];
    
    $check = $this->conn->query("SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $id");
    
    if ($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $new_qty = $row['quantity'] + $soluong;
        
        // Không cho vượt quá quantity kho
        if ($new_qty > $available_qty) {
            return false;
        }
        
        return $this->conn->query("UPDATE cart SET quantity = $new_qty WHERE id = " . $row['id']);
    } else {
        // Không cho vượt quá quantity kho
        if ($soluong > $available_qty) {
            return false;
        }
        
        return $this->conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $id, $soluong)");
    }
}

    /**
     * Đếm số loại sản phẩm trong giỏ hàng
     */
    public function demLoaiSanPham() {
        if (!isset($_SESSION['user_id'])) return 0;
        
        $user_id = $_SESSION['user_id'];
        $res = $this->conn->query("SELECT COUNT(*) as total FROM cart WHERE user_id = $user_id");
        $row = $res->fetch_assoc();
        return $row['total'] ?? 0;
    }

    /**
     * Cập nhật số lượng sản phẩm
     */
   
public function updateQuantity($id, $action) {
    $user_id = $_SESSION['user_id'];
    $id = intval($id);
    
    // Lấy quantity sản phẩm trong kho
    $product = $this->conn->query("SELECT quantity FROM products WHERE id = $id");
    if (!$product || $product->num_rows === 0) {
        return false;
    }
    $prod_row = $product->fetch_assoc();
    $available_qty = $prod_row['quantity'];
    
    if ($action == 'increase') {
        // Lấy số lượng hiện tại trong giỏ
        $current = $this->conn->query("SELECT quantity FROM cart WHERE user_id = $user_id AND product_id = $id");
        if ($current && $current->num_rows > 0) {
            $curr_row = $current->fetch_assoc();
            $new_qty = $curr_row['quantity'] + 1;
            
            // Kiểm tra không vượt quá quantity kho
            if ($new_qty > $available_qty) {
                return false;
            }
        }
        
        return $this->conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $id");
    } elseif ($action == 'decrease') {
        $res = $this->conn->query("SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $id");
        $row = $res->fetch_assoc();
        if ($row['quantity'] > 1) {
            return $this->conn->query("UPDATE cart SET quantity = quantity - 1 WHERE id = " . $row['id']);
        } else {
            return $this->conn->query("DELETE FROM cart WHERE id = " . $row['id']);
        }
    }
}

    /**
     * Xóa một sản phẩm khỏi giỏ hàng
     */
    public function removeItem($id) {
        $user_id = $_SESSION['user_id'];
        $id = intval($id);
        return $this->conn->query("DELETE FROM cart WHERE user_id = $user_id AND product_id = $id");
    }

    /**
     * Lấy danh sách sản phẩm trong giỏ hàng
     */
    public function getCartItems() {
        if (!isset($_SESSION['user_id'])) return [];
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT p.id, p.name as tensp, p.price as gia, p.image as hinh, c.quantity as soluong 
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = $user_id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clearCart() {
        $user_id = $_SESSION['user_id'];
        return $this->conn->query("DELETE FROM cart WHERE user_id = $user_id");
    }

    /**
     * Tính tổng giá tiền
     */
    public function getTotalPrice($items) {
        $tong = 0;
        if (is_array($items)) {
            foreach ($items as $item) {
                $tong += $item['gia'] * $item['soluong'];
            }
        }
        return $tong;
    }
}