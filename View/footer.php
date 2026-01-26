<footer>
        <div class="container footer-grid">
            <div class="footer-about">
                <h3>ICEDREAM</h3>
                <p>Mang lại niềm vui ngọt ngào cho bạn mỗi ngày. Chất lượng và sự tươi mới là ưu tiên hàng đầu.</p>
            </div>
            <div class="footer-links">
                <h4>Liên Kết</h4>
                <ul>
                    <li><a href="#">Về chúng tôi</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h4>Theo Dõi</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Tiệm Kem Icedream</p>
        </div>
    </footer>
<?php if (isset($_SESSION['swal_message'])): ?>
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

    <!-- <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/694a54f31f0ade19740a3288/1jd55nmqn';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script> -->
</body>
</html>