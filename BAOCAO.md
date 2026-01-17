# BÁO CÁO ĐỒ ÁN WEB BÁN KEM ONLINE

## 1. Giới thiệu dự án

### 1.1. Mô tả tổng quan
Dự án **Web Bán Kem IceDream** là một hệ thống thương mại điện tử (E-commerce) chuyên cung cấp các sản phẩm kem và đồ uống giải nhiệt. Website cung cấp đầy đủ các tính năng cho người dùng (xem sản phẩm, đặt hàng, quản lý tài khoản) và quản trị viên (quản lý đơn hàng, sản phẩm, doanh thu).

Dự án tập trung vào trải nghiệm người dùng hiện đại, giao diện bắt mắt và áp dụng các kỹ thuật lập trình tiên tiến để đảm bảo hiệu năng và bảo mật.

### 1.2. Công nghệ sử dụng
*   **Ngôn ngữ lập trình:** PHP (8.0+)
*   **Cơ sở dữ liệu:** MySQL
*   **Frontend:** HTML5, CSS3, JavaScript (Vanilla JS), Bootstrap 5
*   **Mô hình kiến trúc:** MVC (Model-View-Controller)
*   **Web Server:** Apache (XAMPP)

---

## 2. Kiến trúc hệ thống

### 2.1. Mô hình MVC
Dự án được xây dựng theo mô hình MVC để tách biệt logic xử lý, dữ liệu và giao diện:
*   **Model:** Chứa logic nghiệp vụ và tương tác với Database (ví dụ: `SanphamModel.php`, `UserModel.php`).
*   **View:** Giao diện người dùng (HTML/CSS), hiển thị dữ liệu từ Controller (ví dụ: `chitietsp.php`, `giohang.php`).
*   **Controller:** Tiếp nhận yêu cầu từ người dùng, xử lý logic và gọi Model/View tương ứng (ví dụ: `Controller/chitietsp.php`).

### 2.2. Sơ đồ Class Diagram (Mô tả)
Hệ thống sử dụng kiến trúc kế thừa OOP mạnh mẽ:
*   **BaseModel (Abstract):** Lớp cha chứa kết nối DB và các hàm chung (`getAll`, `findById`, `queryPrepared`, `escape`).
*   **UserModel, SanphamModel, OrderModel, CouponModel:** Các lớp con kế thừa `BaseModel` và triển khai các nghiệp vụ riêng.

### 2.3. Sơ đồ ERD Database (Mô tả)
*   **users:** Lưu thông tin người dùng (id, name, email, password, role).
*   **products:** Lưu thông tin sản phẩm (id, name, price, description, quantity).
*   **orders:** Lưu đơn hàng (id, user_id, total_price, status).
*   **order_details:** Chi tiết đơn hàng (order_id, product_id, quantity, price).
*   **coupons:** Mã giảm giá (code, discount_percent, min_order_value).
*   **feedbacks:** Đánh giá sản phẩm.
*   **cart:** Giỏ hàng.



---

## 3. Lập trình hướng đối tượng (OOP)

### 3.1. Các class chính & Tính chất OOP
*   **Tính kế thừa (Inheritance):** Tất cả các Model (`UserModel`, `SanphamModel`...) đều kế thừa từ `BaseModel`. Nhờ đó, chúng tái sử dụng được mã nguồn kết nối và các truy vấn cơ bản.
*   **Tính đóng gói (Encapsulation):** Các thuộc tính như `$conn` được khai báo `protected`, chỉ cho phép truy cập từ bên trong class hoặc class con.
*   **Tính đa hình (Polymorphism):** Hàm `findById()` ở lớp cha có thể được sử dụng bởi mọi lớp con mà không cần viết lại, nhưng mỗi lớp con lại có thể định nghĩa `getTableName()` riêng để hàm cha hoạt động đúng ngữ cảnh.

### 3.2. Ví dụ Code minh họa
```php
// Tính trừu tượng & Kế thừa
abstract class BaseModel {
    protected $conn; // Đóng gói
    
    // Đa hình: Bắt buộc lớp con phải định nghĩa tên bảng
    abstract protected function getTableName(); 

    // Hàm chung sử dụng Prepared Statements
    protected function queryPrepared($sql, $params = [], $types = "") {
        // ... logic xử lý an toàn
    }
}

class SanphamModel extends BaseModel {
    protected function getTableName() {
        return 'products';
    }
}
```

---

## 4. Chức năng chính

### 4.1. Khách hàng (User)
*   **Đăng ký/Đăng nhập:** Xác thực người dùng, bảo mật mật khẩu.
*   **Xem sản phẩm:** Danh sách sản phẩm, chi tiết sản phẩm, lọc theo danh mục.
*   **Gợi ý sản phẩm (AI Recommendation):** Hệ thống tự động gợi ý sản phẩm thường mua cùng nhau hoặc cùng loại.
*   **Giỏ hàng:** Thêm/sửa/xóa sản phẩm, tự động tính tổng tiền.
*   **Mã giảm giá (Coupon):** Tự động gợi ý mã giảm giá khi đủ điều kiện đơn hàng.
*   **Đặt hàng & Thanh toán:** Lưu thông tin đơn hàng, gửi email xác nhận.
*   **Quản lý đơn hàng:** Xem lịch sử mua hàng, trạng thái đơn.

### 4.2. Quản trị viên (Admin)
*   **Dashboard:** Thống kê doanh thu, số lượng đơn hàng mới.
*   **Quản lý sản phẩm:** Thêm, sửa, xóa, cập nhật hình ảnh.
*   **Quản lý đơn hàng:** Duyệt đơn, cập nhật trạng thái (Đang xử lý -> Hoàn thành).
*   **Quản lý người dùng:** Xem danh sách, khóa tài khoản vi phạm.

---

## 5. Bảo mật (Security)

### 5.1. Các biện pháp áp dụng
Dự án tuân thủ các chuẩn bảo mật OWASP:
1.  **SQL Injection Prevention:** Sử dụng 100% **Prepared Statements** trong các Model.
2.  **Password Hashing:** Sử dụng `password_hash()` (Bcrypt) thay vì MD5 hay lưu text trần.
3.  **XSS Protection:** Sử dụng `htmlspecialchars()` khi hiển thị dữ liệu ra View.
4.  **Session Security:** Sử dụng `session_regenerate_id()` để chống tấn công chiếm phiên (Session Fixation).

### 5.2. Code minh họa bảo mật
```php
// Controller/registration.php
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashed_password);
```

---

## 6. Tính năng phát triển (Advanced Features)

### 6.1. AI Recommendation System (Hệ thống gợi ý thông minh)
*   **Mô tả:** Hệ thống phân tích lịch sử mua hàng (`order_details`) để tìm ra các cặp sản phẩm thường được mua cùng nhau (Association Rules). Nếu chưa đủ dữ liệu, hệ thống tự động chuyển sang gợi ý theo danh mục (Content-based).
*   **Lợi ích:** Tăng tỷ lệ chuyển đổi và giá trị đơn hàng trung bình (AOV).

### 6.2. Smart Coupon (Mã giảm giá thông minh)
*   **Mô tả:** Hệ thống tự động kiểm tra giá trị giỏ hàng. Nếu đạt ngưỡng (ví dụ: 200k), một thông báo popup sẽ xuất hiện gợi ý mã giảm giá phù hợp (ví dụ: GIAM10).

---

## 7. Hướng dẫn cài đặt

### 7.1. Yêu cầu hệ thống
*   XAMPP / WAMP (PHP 8.0+, MySQL 5.7+)
*   Trình duyệt web hiện đại (Chrome, Firefox, Edge)

### 7.2. Các bước cài đặt
1.  Copy thư mục dự án vào `C:\xampp\htdocs\doanphp`.
2.  Mở phpMyAdmin, tạo database tên `shop`.
3.  Import file `shop.sql` (hoặc chạy script tạo bảng trong báo cáo).
4.  Truy cập website: `http://localhost/doanphp/View/index.php`.

---

## 8. Kết luận

### 8.1. Đánh giá dự án
Dự án đã hoàn thành tốt các yêu cầu của một website bán hàng cơ bản và nâng cao. Cấu trúc code rõ ràng, dễ bảo trì nhờ mô hình MVC và OOP. Các tính năng nâng cao như AI Recommendation và Bảo mật giúp dự án có tính thực tế cao.

### 8.2. Hướng phát triển
*   Tích hợp thanh toán online (VNPAY, MoMo).
*   Nâng cấp thuật toán gợi ý sử dụng Machine Learning sâu hơn.
*   Phát triển App Mobile sử dụng API từ hệ thống này.
