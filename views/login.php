<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập / Đăng Ký - Quản Lý Tour Du Lịch</title>
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
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }

        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .login-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }

        .login-header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .login-body {
            padding: 30px;
            background: white;
        }

        .nav-tabs {
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 20px;
        }

        .nav-link {
            color: #667eea;
            border: none;
            padding: 12px 20px;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            cursor: pointer;
        }

        .nav-link:hover {
            color: #764ba2;
            border-bottom-color: #667eea;
        }

        .nav-link.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background-color: transparent;
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
        }

        .form-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .btn-login {
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

        .btn-login:hover {
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

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-forgot input[type="checkbox"] {
            margin-right: 5px;
        }

        .remember-forgot a {
            color: #667eea;
            text-decoration: none;
        }

        .remember-forgot a:hover {
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
        }

        .input-icon input {
            padding-left: 45px;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 12px;
            height: 4px;
            border-radius: 2px;
            background-color: #e0e0e0;
            overflow: hidden;
        }

        .password-strength .bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }

        .password-strength .bar.weak {
            width: 33%;
            background-color: #dc3545;
        }

        .password-strength .bar.medium {
            width: 66%;
            background-color: #ffc107;
        }

        .password-strength .bar.strong {
            width: 100%;
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <h1><i class="fas fa-globe"></i> Travel Manager</h1>
                <p>Quản Lý Tour Du Lịch</p>
            </div>

            <!-- Body -->
            <div class="login-body">
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

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-form" type="button" role="tab">
                            <i class="fas fa-sign-in-alt"></i> Đăng Nhập
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-form" type="button" role="tab">
                            <i class="fas fa-user-plus"></i> Đăng Ký
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Login Form -->
                    <div class="tab-pane fade show active" id="login-form" role="tabpanel">
                        <form id="loginForm" name="loginForm" action="index.php?action=login_submit" method="POST" enctype="application/x-www-form-urlencoded">
                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i> Email
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
                                        autofocus
                                    >
                                </div>
                            </div>

                            <!-- Mật khẩu -->
                            <div class="form-group">
                                <label for="password">
                                    <i class="fas fa-lock"></i> Mật khẩu
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control"
                                        placeholder="Nhập mật khẩu"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Nhớ mật khẩu & Quên mật khẩu -->
                            <div class="remember-forgot">
                                <label>
                                    <input type="checkbox" name="remember" value="1">
                                    Nhớ mật khẩu
                                </label>
                                <a href="#">Quên mật khẩu?</a>
                            </div>

                            <!-- Nút đăng nhập -->
                            <button type="submit" class="btn-login" id="loginBtn">
                                <i class="fas fa-sign-in-alt"></i> Đăng Nhập
                            </button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div class="tab-pane fade" id="register-form" role="tabpanel">
                        <form id="registerForm" name="registerForm" action="index.php?action=register_submit" method="POST" enctype="application/x-www-form-urlencoded">
                            <!-- Họ và tên -->
                            <div class="form-group">
                                <label for="reg-fullname">
                                    <i class="fas fa-user"></i> Họ và tên
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                    <input
                                        type="text"
                                        id="reg-fullname"
                                        name="full_name"
                                        class="form-control"
                                        placeholder="Nhập họ và tên"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="reg-email">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input
                                        type="email"
                                        id="reg-email"
                                        name="email"
                                        class="form-control"
                                        placeholder="Nhập email của bạn"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Số điện thoại -->
                            <div class="form-group">
                                <label for="reg-phone">
                                    <i class="fas fa-phone"></i> Số điện thoại
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-phone"></i>
                                    <input
                                        type="tel"
                                        id="reg-phone"
                                        name="phone"
                                        class="form-control"
                                        placeholder="Nhập số điện thoại"
                                    >
                                </div>
                            </div>

                            <!-- Mật khẩu -->
                            <div class="form-group">
                                <label for="reg-password">
                                    <i class="fas fa-lock"></i> Mật khẩu
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input
                                        type="password"
                                        id="reg-password"
                                        name="password"
                                        class="form-control"
                                        placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
                                        required
                                        minlength="6"
                                        onkeyup="checkPasswordStrength(this.value)"
                                    >
                                </div>
                                <div class="password-strength">
                                    <div class="bar" id="strength-bar"></div>
                                </div>
                            </div>

                            <!-- Nhập lại mật khẩu -->
                            <div class="form-group">
                                <label for="reg-password-confirm">
                                    <i class="fas fa-lock"></i> Xác nhận mật khẩu
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input
                                        type="password"
                                        id="reg-password-confirm"
                                        name="password_confirm"
                                        class="form-control"
                                        placeholder="Nhập lại mật khẩu"
                                        required
                                        minlength="6"
                                    >
                                </div>
                            </div>

                            <!-- Nút đăng ký -->
                            <button type="submit" class="btn-login">
                                <i class="fas fa-user-plus"></i> Đăng Ký
                            </button>
                        </form>
                    </div>
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
        function checkPasswordStrength(password) {
            const bar = document.getElementById('strength-bar');
            if (password.length < 6) {
                bar.className = 'bar';
            } else if (password.length < 8) {
                bar.className = 'bar weak';
            } else if (password.length < 12) {
                bar.className = 'bar medium';
            } else {
                bar.className = 'bar strong';
            }
        }

        // Debug form submission
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== Login Form Debug ===');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');

            if (loginForm) {
                console.log('Login Form Action:', loginForm.action);
                console.log('Login Form Method:', loginForm.method);

                loginForm.addEventListener('submit', function(e) {
                    console.log('Login form submitted!');
                    console.log('Email:', document.getElementById('email').value);
                    console.log('Password length:', document.getElementById('password').value.length);
                });
            }

            if (registerForm) {
                console.log('Register Form Action:', registerForm.action);
                console.log('Register Form Method:', registerForm.method);
            }
        });
    </script>
</body>
</html>
