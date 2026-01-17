<?php
class DatabaseSetup {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Logic từ create_table.php
    public function createOrderDetailsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS order_details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            quantity INT NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id)
        )";

        if ($this->conn->query($sql) === TRUE) {
            return "Table 'order_details' created/checked successfully.";
        } else {
            return "Error creating table: " . $this->conn->error;
        }
    }

    public function createDanhMucTable() {
        $sql = "CREATE TABLE IF NOT EXISTS danhmuc (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        )";

        if ($this->conn->query($sql) === TRUE) {
            return "Table 'danhmuc' created/checked successfully.";
        } else {
            return "Error creating table: " . $this->conn->error;
        }
    }

    public function alterProductsTableForCategories() {
        $sql = "ALTER TABLE products ADD iddanhmuc INT NULL, ADD FOREIGN KEY (iddanhmuc) REFERENCES danhmuc(id)";
        if ($this->conn->query($sql) === TRUE) {
            return "Table 'products' altered for categories successfully.";
        } else {
            return "Error altering table: " . $this->conn->error;
        }
    }

    // Logic từ list_tables.php
    public function getAllTables() {
        $tables = [];
        $result = $this->conn->query("SHOW TABLES");
        if ($result) {
            while ($row = $result->fetch_row()) {
                $tables[] = $row[0];
            }
        }
        return $tables;
    }

    // Logic từ check_cart.php
    public function getCartStructure() {
        $columns = [];
        $result = $this->conn->query("SHOW COLUMNS FROM cart");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $columns[] = $row['Field'];
            }
        }
        return $columns;
    }
}
?>
