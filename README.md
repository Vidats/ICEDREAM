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

## Các tính năng mới được thêm vào

-   **Quản lý Coupon:** Cho phép quản trị viên tạo, quản lý và áp dụng mã giảm giá cho các đơn hàng.
-   **Chi tiết đơn hàng cho Admin:** Cung cấp giao diện chi tiết để quản trị viên xem thông tin từng đơn hàng cụ thể, bao gồm sản phẩm, số lượng và trạng thái.
-   **Tìm kiếm/Lọc sản phẩm nâng cao:** Bổ sung chức năng tìm kiếm và lọc sản phẩm linh hoạt trên giao diện người dùng.
-   **Quản lý phản hồi người dùng:** Cải thiện khả năng quản lý và hiển thị phản hồi từ khách hàng.

## Cấu trúc hướng đối tượng (OOP)

Dự án đã được tái cấu trúc theo mô hình lập trình hướng đối tượng (OOP) để tăng cường tính module hóa, khả năng tái sử dụng và dễ dàng bảo trì.


Việc áp dụng OOP giúp:
-   **Dễ dàng mở rộng:** Thêm các tính năng mới mà không ảnh hưởng nhiều đến cấu trúc hiện có.
-   **Bảo trì:** Mã nguồn rõ ràng, dễ hiểu và dễ sửa đổi hơn.
-   **Tái sử dụng mã:** Các thành phần có thể được sử dụng lại ở nhiều nơi khác nhau trong ứng dụng.



  1. Tính Kế thừa (Inheritance)
  Đây là phần quan trọng nhất trong code của bạn. Bạn có một lớp cha là BaseModel và các lớp con như CouponModel, GiohangModel, OrderModel.


   * Lớp Cha (`BaseModel`): Chứa các thuộc tính và phương thức dùng chung cho tất cả các Model (như kết nối cơ sở dữ liệu $conn, hàm all(), find(),
     escape()).
   * Lớp Con (`CouponModel`): Sử dụng từ khóa extends để thừa hưởng toàn bộ sức mạnh từ BaseModel.


   1     class CouponModel extends BaseModel {
   2         // Nó không cần viết lại hàm kết nối DB, vì đã lấy từ BaseModel
   3     }
      Lợi ích: Tránh lặp lại code. Nếu bạn muốn sửa logic kết nối DB, bạn chỉ cần sửa ở một nơi duy nhất là BaseModel.


  2. Tính Đóng gói (Encapsulation)
  Bạn bảo vệ dữ liệu và logic xử lý bên trong các hàm (methods) của lớp.


   * Các thuộc tính như $conn thường được để ở chế độ protected hoặc private để tránh việc các file bên ngoài can thiệp trực tiếp vào kết nối DB.
   * Ví dụ: Trong CouponModel, logic kiểm tra mã giảm giá được "đóng gói" hoàn toàn trong hàm checkCoupon. Controller chỉ việc gọi hàm và nhận kết quả, không
     cần biết bên trong truy vấn SQL như thế nào.


  3. Tính Đa hình (Polymorphism)
  Thể hiện qua việc các lớp con có thể sử dụng lại hoặc ghi đè (override) các phương thức của lớp cha.

   * Trong BaseModel, bạn có thể có một hàm getTableName().
   * Mỗi lớp con sẽ "định nghĩa lại" hàm này để trả về tên bảng tương ứng:


   1     // Trong CouponModel
   2     protected function getTableName() {
   3         return 'coupons';
   4     }
   5
   6     // Trong OrderModel
   7     protected function getTableName() {
   8         return 'orders';
   9     }


  4. Chia tách trách nhiệm (Separation of Concerns)
  Mặc dù không phải là một cột trụ của OOP nhưng OOP giúp thực hiện điều này cực tốt:


   * Model (`CouponModel.php`): Chỉ lo việc truy vấn dữ liệu (SQL, DB).
   * Controller (`giohang.php`): Chỉ lo việc điều hướng, nhận input từ người dùng và gọi Model.
   * View (`giohang.php` trong View): Chỉ lo việc hiển thị HTML cho người dùng.


  Ví dụ luồng chạy trong code của bạn:
   1. View: Người dùng nhấn nút "Áp dụng".
   2. Controller: Nhận mã SAVE10, gọi $couponModel->checkCoupon('SAVE10', $total).
   3. Model: Lớp CouponModel thực hiện câu lệnh SELECT * FROM coupons... và trả về một mảng dữ liệu.
   4. Controller: Nhận mảng dữ liệu, lưu vào $_SESSION và yêu cầu View hiển thị thông báo thành công.


  Tóm lại: Cách bạn viết code giúp hệ thống rất dễ mở rộng. Ví dụ, sau này bạn muốn thêm "Mã giảm giá cho khách hàng VIP", bạn chỉ cần thêm hàm vào
  CouponModel mà không làm ảnh hưởng đến code ở trang Giỏ hàng hay Thanh toán.
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
    Tài Khoản Admin: admin@gmail.com
    Mật khẩu: admin 

## Lưu ý
-   **Quyền Admin:** Thông thường, người dùng có `role = 1` trong bảng `users` được coi là Admin.
-   **Hình ảnh:** Đảm bảo thư mục `image/` có quyền ghi (writable) nếu hỗ trợ upload sản phẩm.