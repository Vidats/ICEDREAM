<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="card mx-auto shadow-sm" style="max-width: 400px; border-radius: 15px; border: none;">
        <div class="card-body p-4 text-center">
            <h3 style="color: #ff85a2;" class="mb-4">Xác thực mã OTP</h3>
            
            <form action="../Controller/forgot-password.php" method="POST">
                <input type="email" name="email" class="form-control mb-3 shadow-none" 
                       value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" 
                       placeholder="Nhập lại Email" required>
                
                <input type="text" name="otp" class="form-control mb-3 shadow-none text-center fw-bold" 
                       style="letter-spacing: 5px; font-size: 1.2rem;"
                       placeholder="MÃ OTP" required maxlength="6">
                
                <input type="password" name="new_password" class="form-control mb-4 shadow-none" 
                       placeholder="Mật khẩu mới" required>
                
                <button type="submit" name="verify" class="btn btn-lg w-100" 
                        style="background: #ff85a2; color: white; border-radius: 25px;">
                    Xác nhận đổi mật khẩu
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>