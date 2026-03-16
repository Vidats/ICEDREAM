<footer>
    <div class="container footer-grid">
        <div class="footer-column footer-about">
            <h3><i class="fas fa-ice-cream"></i> ICEDREAM</h3>
            <p>Mang lại niềm vui ngọt ngào cho bạn mỗi ngày. Chúng tôi tự hào cung cấp những loại kem thủ công tươi ngon nhất với nguyên liệu tự nhiên 100%.</p>
            <div class="social-icons">
                <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
                <a href="#" title="Youtube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <div class="footer-column footer-links">
            <h4>Khám Phá</h4>
            <ul>
                <li><a href="index.php">Trang Chủ</a></li>
                <li><a href="sanpham.php">Sản Phẩm</a></li>
                <li><a href="gioithieu.php">Về Chúng Tôi</a></li>
                <li><a href="lienhe.php">Liên Hệ</a></li>
            </ul>
        </div>

        <div class="footer-column footer-links">
            <h4>Chính Sách</h4>
            <ul>
                <li><a href="#">Chính Sách Giao Hàng</a></li>
                <li><a href="#">Chính Sách Đổi Trả</a></li>
                <li><a href="#">Bảo Mật Thông Tin</a></li>
                <li><a href="#">Điều Khoản Sử Dụng</a></li>
            </ul>
        </div>

        <div class="footer-column footer-contact">
            <h4>Liên Hệ</h4>
            <ul class="contact-info">
                <li><i class="fas fa-map-marker-alt"></i> 123 Đường Kem Bơ, Thành phố Long Xuyên, An Giang</li>
                <li><i class="fas fa-phone-alt"></i> 0123 456 789</li>
                <li><i class="fas fa-envelope"></i> contact@icedream.com</li>
                <li><i class="fas fa-clock"></i> Mở cửa: 08:00 - 22:00</li>
            </ul>
        </div>
    </div>
  
</footer><?php if (isset($_SESSION['swal_message'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Đổ dữ liệu từ PHP sang biến JS trước để trình soạn thảo không báo lỗi cú pháp
            const title = <?php echo json_encode($_SESSION['swal_title'] ?? 'Thông báo'); ?>;
            const message = <?php echo json_encode($_SESSION['swal_message']); ?>;
            const type = <?php echo json_encode($_SESSION['swal_type'] ?? 'info'); ?>;
            const redirect = <?php echo json_encode($_SESSION['swal_redirect'] ?? null); ?>;
            const isOrderSuccess = <?php echo isset($_SESSION['swal_order_success']) ? 'true' : 'false'; ?>;

            const config = {
                title: title,
                confirmButtonColor: '#ff85a2',
                confirmButtonText: 'Tuyệt vời!'
            };

            if (isOrderSuccess) {
                config.html = `
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <dotlottie-player 
                            src="../Content/icecream.json" 
                            background="transparent" 
                            speed="1" 
                            style="width: 200px; height: 200px;" 
                            loop 
                            autoplay>
                        </dotlottie-player>
                        <p style="margin-top: 15px; font-weight: bold;">${message}</p>
                    </div>
                `;
            } else {
                config.text = message;
                config.icon = type;
            }

            Swal.fire(config).then((result) => {
                if (redirect) {
                    window.location.href = redirect;
                }
            });
        });
    </script>
    <?php 
        unset($_SESSION['swal_title'], $_SESSION['swal_message'], $_SESSION['swal_type'], $_SESSION['swal_redirect'], $_SESSION['swal_order_success']);
    ?>
    <?php endif; ?>


</body>
</html>