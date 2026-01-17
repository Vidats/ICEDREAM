<?php
// Simple script to set up database tables as per user's request.
// It's recommended to use a more robust migration system for larger projects.

include_once __DIR__ . '/../Model/db.php';

$db = new db();
$conn = $db->link;

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

echo "<h1>Database Setup Script</h1>";

// 1. Create 'categories' table
$sql_categories = "
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

echo "Executing: CREATE TABLE 'categories'...<br>";
if ($conn->query($sql_categories) === TRUE) {
    echo "<strong style='color:green;'>Table 'categories' created successfully or already exists.</strong><br><br>";
} else {
    echo "<strong style='color:red;'>Error creating table 'categories': " . $conn->error . "</strong><br><br>";
}

// 2. Modify 'products' table
// First, check if 'category_id' column exists. If not, add it.
$check_col_sql = "SHOW COLUMNS FROM `products` LIKE 'category_id'";
$result = $conn->query($check_col_sql);
$col_exists = $result->num_rows > 0;

if (!$col_exists) {
    $sql_alter_products = "
    ALTER TABLE `products`
    ADD COLUMN `category_id` INT(11) DEFAULT NULL AFTER `image`,
    ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;
    ";
    echo "Executing: ALTER TABLE 'products'...<br>";
    if ($conn->query($sql_alter_products) === TRUE) {
        echo "<strong style='color:green;'>Table 'products' altered successfully.</strong><br><br>";
    } else {
        echo "<strong style='color:red;'>Error altering table 'products': " . $conn->error . "</strong><br><br>";
        // If altering fails, it might be due to old conflicting columns.
        // As a fallback, let's try just adding the column without the FK if the error is about that.
    }
} else {
    echo "<strong style='color:blue;'>Column 'category_id' already exists in 'products' table. No changes made.</strong><br><br>";
}

// Drop old columns if they exist
$check_old_cat_col_sql = "SHOW COLUMNS FROM `products` LIKE 'category'";
$result_old_cat = $conn->query($check_old_cat_col_sql);
if ($result_old_cat->num_rows > 0) {
    echo "Executing: Dropping old 'category' column...<br>";
    if ($conn->query("ALTER TABLE `products` DROP COLUMN `category`") === TRUE) {
        echo "<strong style='color:green;'>Old column 'category' dropped successfully.</strong><br><br>";
    } else {
        echo "<strong style='color:red;'>Error dropping old 'category' column: " . $conn->error . "</strong><br><br>";
    }
}

$check_old_iddanhmuc_col_sql = "SHOW COLUMNS FROM `products` LIKE 'iddanhmuc'";
$result_old_iddanhmuc = $conn->query($check_old_iddanhmuc_col_sql);
if ($result_old_iddanhmuc->num_rows > 0) {
    echo "Executing: Dropping old 'iddanhmuc' column...<br>";
    if ($conn->query("ALTER TABLE `products` DROP COLUMN `iddanhmuc`") === TRUE) {
        echo "<strong style='color:green;'>Old column 'iddanhmuc' dropped successfully.</strong><br><br>";
    } else {
        echo "<strong style='color:red;'>Error dropping old 'iddanhmuc' column: " . $conn->error . "</strong><br><br>";
    }
}


// Drop old table if it exists
$check_old_table_sql = "SHOW TABLES LIKE 'danhmuc'";
$result_old_table = $conn->query($check_old_table_sql);
if ($result_old_table->num_rows > 0) {
    echo "Executing: Dropping old 'danhmuc' table...<br>";
    if ($conn->query("DROP TABLE `danhmuc`") === TRUE) {
        echo "<strong style='color:green;'>Old table 'danhmuc' dropped successfully.</strong><br><br>";
    } else {
        echo "<strong style='color:red;'>Error dropping old table 'danhmuc': " . $conn->error . "</strong><br><br>";
    }
}


echo "<h2>Setup Complete!</h2>";

$conn->close();
