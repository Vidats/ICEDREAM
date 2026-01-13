<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4" style="color: #ff85a2;">Khôi phục mật khẩu</h3>
                    <p class="text-muted text-center small">Nhập email đã đăng ký, chúng tôi sẽ gửi mã OTP xác thực.</p>
                    
                    <form action="../Controller/forgot-password.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email của bạn</label>
                            <input type="email" name="email" class="form-control shadow-none" placeholder="example@gmail.com" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" name="send_otp" class="btn btn-primary btn-lg" 
                                    style="background-color: #ff85a2; border: none; border-radius: 25px;">
                                Gửi mã OTP
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="form.php" class="text-decoration-none small text-muted">Quay lại Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>