<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Quản Lý Tour Du Lịch</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome from jsDelivr (alternative CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            padding: 15px;
        }

        .register-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .register-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }

        .register-header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .register-body {
            padding: 40px;
            background: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #2e3338;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-group input {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s;
            width: 100%;
        }

        .form-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 16px;
            pointer-events: none;
        }

        .input-icon input {
            padding-left: 45px;
        }

        .password-strength {
            font-size: 12px;
            margin-top: 5px;
        }

        .password-strength.weak {
            color: #dc3545;
        }

        .password-strength.medium {
            color: #ffc107;
        }

        .password-strength.strong {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Header -->
            <div class="register-header">
                <h1><i class="fas fa-user-plus"></i> Đăng Ký</h1>
                <p>Tạo tài khoản mới để bắt đầu</p>
            </div>

            <!-- Body -->
            <div class="register-body">
                <!-- Thông báo lỗi -->
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-error" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Thông báo thành công -->
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <!-- Form đăng ký -->
                <form action="<?= url('register_submit') ?>" method="POST" id="registerForm">
                    <!-- Họ và tên -->
                    <div class="form-group">
                        <label for="full_name">
                            <i class="fas fa-user"></i> Họ và tên <span style="color: red;">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                class="form-control"
                                placeholder="Nhập họ và tên của bạn"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email <span style="color: red;">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="Nhập email của bạn"
                                required
                            >
                        </div>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Số điện thoại
                        </label>
                        <div class="input-icon">
                            <i class="fas fa-phone"></i>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-control"
                                placeholder="Nhập số điện thoại (tùy chọn)"
                            >
                        </div>
                    </div>

                    <!-- Mật khẩu -->
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Mật khẩu <span style="color: red;">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
                                required
                                minlength="6"
                            >
                        </div>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>

                    <!-- Xác nhận mật khẩu -->
                    <div class="form-group">
                        <label for="password_confirm">
                            <i class="fas fa-lock"></i> Xác nhận mật khẩu <span style="color: red;">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input
                                type="password"
                                id="password_confirm"
                                name="password_confirm"
                                class="form-control"
                                placeholder="Nhập lại mật khẩu"
                                required
                            >
                        </div>
                        <div id="passwordMatch" style="font-size: 12px; margin-top: 5px;"></div>
                    </div>

                    <!-- Nút đăng ký -->
                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus"></i> Đăng Ký
                    </button>
                </form>

                <!-- Liên kết đăng nhập -->
                <div class="login-link">
                    Đã có tài khoản? <a href="<?= url('login') ?>">Đăng nhập ngay</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="text-align: center; color: white; margin-top: 30px; font-size: 12px;">
            <p>&copy; 2025 Travel Manager. All rights reserved.</p>
        </div>
    </div>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Kiểm tra độ mạnh của mật khẩu
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthDiv.textContent = '';
                return;
            }

            let strength = 'Yếu';
            let strengthClass = 'weak';

            if (password.length >= 6) {
                if (/[a-z]/.test(password) && /[A-Z]/.test(password) && /[0-9]/.test(password)) {
                    strength = 'Mạnh';
                    strengthClass = 'strong';
                } else if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
                    strength = 'Trung bình';
                    strengthClass = 'medium';
                } else if (password.length >= 8) {
                    strength = 'Trung bình';
                    strengthClass = 'medium';
                }
            }

            strengthDiv.textContent = 'Độ mạnh: ' + strength;
            strengthDiv.className = 'password-strength ' + strengthClass;
        });

        // Kiểm tra xác nhận mật khẩu
        document.getElementById('password_confirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const matchDiv = document.getElementById('passwordMatch');

            if (confirm === '') {
                matchDiv.textContent = '';
                return;
            }

            if (password === confirm) {
                matchDiv.textContent = '✓ Mật khẩu khớp';
                matchDiv.style.color = '#28a745';
            } else {
                matchDiv.textContent = '✗ Mật khẩu không khớp';
                matchDiv.style.color = '#dc3545';
            }
        });

        // Xác thực form trước khi submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Mật khẩu không khớp!');
                return false;
            }
        });
    </script>
</body>
</html>
