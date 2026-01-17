# Website Bán Kem (Đồ Án PHP)

Đây là nền tảng thương mại điện tử (website) để bán các sản phẩm kem, bao gồm giao diện cửa hàng dành cho khách hàng và trang quản trị toàn diện dành cho người quản lý. Dự án được xây dựng bằng PHP thuần (Native PHP).

## Tính năng

### Người dùng (Frontend)
- **Xem sản phẩm:** Duyệt qua các hương vị kem và sản phẩm có sẵn (`View/index.php`, `View/sanpham.php`).
- **Chi tiết sản phẩm:** Xem thông tin chi tiết của từng sản phẩm (`View/chitietsp.php`).
- **Giỏ hàng:** Thêm sản phẩm vào giỏ và xem tóm tắt đơn hàng (`View/giohang.php`).
- **Quản lý đơn hàng:** Đặt hàng và theo dõi lịch sử đơn hàng (`View/my_order.php`).
- **Tài khoản người dùng:**
    - Đăng ký và Đăng nhập (`View/auth.php`).
    - Quên mật khẩu với xác thực OTP qua Email (`View/forgot-password-view.php`).
    - Quản lý thông tin cá nhân (`View/profile.php`).
- **Phản hồi:** Người dùng có thể gửi đánh giá/phản hồi (`View/write_feedback.php`).

### Quản trị (Backend - Admin)
- **Dashboard:** Tổng quan về doanh số, thống kê đơn hàng và biểu đồ doanh thu (`Admin/index.php`).
- **Quản lý đơn hàng:** Xem và cập nhật trạng thái đơn hàng (`Admin/quanlydonhang.php`).
- **Quản lý sản phẩm:** Thêm, sửa và xóa sản phẩm (`Admin/quanlysp.php`).
- **Quản lý người dùng:** Quản lý các tài khoản người dùng (`Admin/quanlyuser.php`).
- **Quản lý phản hồi:** Xem phản hồi từ khách hàng (`Admin/quanlyfeedback.php`).

## Công nghệ sử dụng

- **Backend:** PHP (Native/Thuần)
- **Cơ sở dữ liệu:** MySQL
- **Frontend:** HTML, CSS, JavaScript (sử dụng Bootstrap)
- **Dịch vụ Email:** PHPMailer
- **Server:** Apache (thông qua XAMPP/WAMP)

## Cấu trúc dự án

```
C:\xampp\htdocs\doanphp\
├── Admin\              # Các file trang quản trị (Controllers, Views, Assets)
├── config\             # Các file cấu hình (Kết nối Database)
├── Content\            # Tài nguyên Public CSS/JS cho Frontend
├── Controller\         # Controllers cho Frontend (Xử lý logic)
├── image\              # Hình ảnh sản phẩm và giao diện
├── Model\              # Data Access Objects (Tương tác cơ sở dữ liệu)
├── PHPMailer-master\   # Thư viện gửi mail PHPMailer
├── View\               # Views cho Frontend (Giao diện người dùng)
└── ...
```

## Cài đặt & Thiết lập

1.  **Clone mã nguồn** vào thư mục root của web server (ví dụ: `C:\xampp\htdocs\doanphp`).

2.  **Cấu hình Cơ sở dữ liệu:**
    -   Tạo một cơ sở dữ liệu MySQL mới tên là `shop` (hoặc cập nhật tên trong `Model/db.php`).
    -   Import schema cơ sở dữ liệu (file .sql) nếu có. Nếu không, hãy đảm bảo các bảng sau tồn tại:
        -   `users` (id, full_name, email, password, role, otp_code, otp_expiry, ...)
        -   `products` (id, name, price, image, description, ...)
        -   `orders` (id, user_id, total_price, status, created_at, ...)
        -   `order_details` (id, order_id, product_id, price, quantity)
        -   `feedback` (id, user_id, content, ...)
        -   `cart` (nếu dùng giỏ hàng lưu trong DB)
    -   Kiểm tra `Model/db.php` và `config/database.php` để đảm bảo thông tin đăng nhập khớp với MySQL của bạn:
        ```php
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "shop";
        ```

3.  **Cấu hình Email:**
    -   Nếu sử dụng tính năng "Quên mật khẩu", hãy đảm bảo `Controller/forgot-password.php` (hoặc file gửi mail liên quan) có thông tin SMTP hợp lệ để gửi email.

## Hướng dẫn sử dụng

-   **Trang chủ (Shop):** Truy cập `http://localhost/doanphp/View/index.php`
-   **Trang quản trị (Admin):** Truy cập `http://localhost/doanphp/Admin/index.php`

## Lưu ý
-   **Quyền Admin:** Thông thường, người dùng có `role = 1` trong bảng `users` được coi là Admin.
-   **Hình ảnh:** Đảm bảo thư mục `image/` có quyền ghi (writable) nếu hỗ trợ upload sản phẩm.