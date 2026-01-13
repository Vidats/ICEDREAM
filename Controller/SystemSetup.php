<?php
require_once '../Model/db.php';
require_once '../Model/DatabaseSetup.php';

$dbSetup = new DatabaseSetup($conn);
$message = "";
$tables = [];
$cartColumns = [];

// Xử lý logic
// 1. Tạo bảng order_details
$message = $dbSetup->createOrderDetailsTable();

// 2. Lấy danh sách tables
$tables = $dbSetup->getAllTables();

// 3. Lấy cấu trúc bảng cart
$cartColumns = $dbSetup->getCartStructure();

// --- Phần hiển thị (View đơn giản ngay tại Controller để tiện debug) ---
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>System Setup & Debug</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .box { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        h2 { margin-top: 0; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Database System Check</h1>

    <div class="box">
        <h2>1. Migration Status</h2>
        <p class="<?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </p>
    </div>

    <div class="box">
        <h2>2. Current Tables</h2>
        <ul>
            <?php foreach ($tables as $table): ?>
                <li><?php echo $table; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="box">
        <h2>3. 'Cart' Table Structure</h2>
        <ul>
            <?php foreach ($cartColumns as $col): ?>
                <li><?php echo $col; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
