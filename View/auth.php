<?php 
session_start();
include 'header.php'; 
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <link rel="s.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <style>
        :root {
            --primary-color: #ff85a2;
            /* Hồng dâu */
            --secondary-color: #a2d2ff;
            /* Xanh pastel */
            --text-color: #444;
            --light-bg: #fff5f7;
            /* Nền hồng cực nhẹ */
            --ice-pink: #ff85a2;
            --ice-blue: #a2d2ff;
            --text-dark: #2d3436;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
        }

        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .auth-container {
            background: #fff;
            padding: 35px;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(255, 133, 162, 0.15);
            width: 100%;
            max-width: 750px;
            border: 4px solid #fff;
            outline: 2px solid var(--secondary-color);
        }

        /* Tab Switcher - Điều hướng qua lại */
        .auth-switcher {
            display: flex;
            margin-bottom: 30px;
            background: #f0f0f0;
            border-radius: 50px;
            padding: 5px;
        }

        .switcher-btn {
            flex: 1;
            padding: 12px 0;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            color: #888;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        /* Tab Đăng nhập chọn Hồng dâu */
        .switcher-btn.active[href*="login"] {
            background-color: var(--ice-pink);
            color: white;
        }

        /* Tab Đăng ký chọn Xanh Pastel */
        .switcher-btn.active[href*="register"] {
            background-color: var(--ice-blue);
            color: white;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        /* Label và Input */
        label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--ice-pink);
            background-color: #fffafa;
        }

        /* Nút bấm Đăng nhập */
        #login-new-form .submit-btn {
            background-color: var(--ice-pink);
            box-shadow: 0 5px 15px rgba(255, 133, 162, 0.4);
        }

        /* Nút bấm Đăng ký */
        #register-new-form .submit-btn {
            background-color: var(--ice-blue);
            box-shadow: 0 5px 15px rgba(162, 210, 255, 0.4);
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 50px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .row {
            display: flex;
            gap: 15px;
        }

        .col-md-6 {
            flex: 1;
        }

        .error-message {
            color: var(--ice-pink);
            font-size: 0.8rem;
        }

        /* Checkbox & Link */
        .form-check-label a {
            color: var(--ice-pink);
            font-weight: 600;
        }

        .forgot-password-link {
            color: var(--ice-blue);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <?php
    // Quyết định tab active bằng PHP (dựa vào query param hoặc mặc định)
    $activeTab = $_GET['tab'] ?? 'login';

    // Hiển thị thông báo nếu có
    $status = $_GET['status'] ?? '';
    $message = $_GET['message'] ?? '';
    
    $serverEmailError = '';
    if ($activeTab === 'register' && $status === 'error' && $message === 'Email xác nhận không khớp.') {
        $serverEmailError = $message;
        // Clear status and message so it doesn't show at the top for this specific error
        $status = ''; 
        $message = '';
    }

    if ($status && $message):
    ?>
        <div style="text-align: center; margin-top: 20px;">
            <p class="<?= $status == 'success' ? 'success-message' : 'error-message' ?>"
                style="font-size: 1rem; border: 1px solid; padding: 10px; display: inline-block;">
                <?= htmlspecialchars($message) ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-switcher">
                <a href="?tab=login" class="switcher-btn <?= $activeTab === 'login' ? 'active' : '' ?>">ĐĂNG NHẬP</a>
                <a href="?tab=register" class="switcher-btn <?= $activeTab === 'register' ? 'active' : '' ?>">TẠO TÀI KHOẢN</a>
            </div>

            <div id="login-new-form" class="form-section <?= $activeTab === 'login' ? 'active' : '' ?>">
                <h2 style="margin-bottom: 20px; font-size: 1.5rem; font-weight: 500; text-align: center;">Đăng Nhập</h2>
                <form id="loginForm" method="POST" action="../Controller/login.php"> <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label for="login-new-email">Email*</label>
                        <input type="email" id="login-new-email" name="email" placeholder="Nhập địa chỉ email của bạn" required />
                        <span class="error-message" id="login-email-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="login-new-password">Mật khẩu*</label>
                        <div class="password-input-group">
                            <input type="password" id="login-new-password" name="password" placeholder="Nhập mật khẩu" required />
                        </div>
                        <span class="error-message" id="login-password-error"></span>
                    </div>
                    <div class="form-check text-start mb-4">
                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                        <label class="form-check-label" for="remember-me" style="font-weight: normal; font-size: 0.95rem;">
                            Ghi nhớ đăng nhập
                        </label>
                        <a href="forgot-password-view.php" class="float-end forgot-password-link">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="submit-btn" style="background-color: var(--primary-color);">ĐĂNG NHẬP</button>
                </form>
            </div>

            <div id="register-new-form" class="form-section <?= $activeTab === 'register' ? 'active' : '' ?>">
                <h2 style="margin-bottom: 25px; font-size: 1.5rem; font-weight: 600; text-align: center;">Tạo tài khoản</h2>
                <p style="font-size: 0.95rem; margin-bottom: 25px; color: var(--secondary-color); text-align: center;">Tạo tài khoản để tận hưởng trải nghiệm được cá nhân hóa.</p>

                <form id="registerForm" method="POST" action="../Controller/registration.php" onsubmit="return validateRegistrationForm()"> <input type="hidden" name="action" value="register">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="register-email">Email*</label>
                            <input type="email" id="register-email" name="email" required />
                            <span class="error-message" id="reg-email-error"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="register-confirm-email">Email xác thực*</label>
                            <input type="email" id="register-confirm-email" name="confirm_email" required />
                            <span class="error-message" id="reg-confirm-email-error">
                                <?php if ($serverEmailError): ?>
                                    <?= htmlspecialchars($serverEmailError) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="register-title">Tiêu đề*</label>
                            <select class="form-control" id="register-title" name="title" required>
                                <option value="" disabled selected>Giới Tính </option>
                                <option value="mr">Nam</option>
                                <option value="mrs">Nữ</option>
                            </select>
                            <span class="error-message" id="reg-title-error"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="register-full-name">Họ và Tên*</label>
                            <input type="text" id="register-full-name" name="full_name" required />
                            <span class="error-message" id="reg-name-error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="register-password">Mật khẩu*</label>
                            <div class="password-input-group">
                                <input type="password" id="register-password" name="password" required />
                            </div>
                            <span class="error-message" id="reg-password-error"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="register-dob">Ngày sinh</label>
                            <input type="date" id="register-dob" name="dob" />
                            <span class="error-message" id="reg-dob-error"></span>
                        </div>
                    </div>

                    <div class="form-check text-start mb-4">
                        <input class="form-check-input" type="checkbox" id="privacy-policy" name="privacy_policy" required>
                        <label class="form-check-label" for="privacy-policy" style="font-weight: normal; font-size: 0.95rem; color:#000000">
                            Tôi đã đọc và đồng ý với <i>Chính sách quyền riêng tư</i>
                        </label>
                    </div>

                    <button type="submit" class="submit-btn" style="background-color: var(--primary-color);">ĐĂNG KÝ</button>
                </form>
            </div>
        </div>
    </div>

<script>
    function validateRegistrationForm() {
        const email = document.getElementById('register-email').value;
        const confirm_email = document.getElementById('register-confirm-email').value;
        const dob = document.getElementById('register-dob').value;
        const emailError = document.getElementById('reg-email-error');
        const confirmEmailError = document.getElementById('reg-confirm-email-error');
        const dobError = document.getElementById('reg-dob-error');

        let isValid = true;

        // Reset errors
        emailError.textContent = '';
        confirmEmailError.textContent = '';
        dobError.textContent = '';

        // 1. Kiểm tra email xác nhận
        if (email !== confirm_email) {
            confirmEmailError.textContent = 'Email xác nhận không khớp. Vui lòng kiểm tra lại.';
            isValid = false;
        }

        // 2. Kiểm tra tuổi
        if (dob) {
            const birthDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            if (age < 12) {
                dobError.textContent = 'Bạn phải đủ 12 tuổi trở lên để đăng ký.';
                isValid = false;
            }
        }

        return isValid;
    }
</script>
</body>

</html>

<?php include 'footer.php'; ?>