<!-- Success/Error Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <div><?= escape($_SESSION['success']) ?></div>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- Profile Container -->
<div class="container container-main" style="margin-top: 20px;">
    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div class="profile-icon-wrapper">
                <div class="profile-icon-large">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <h1>Hồ Sơ Cá Nhân</h1>
            <p><?= escape($user['email'] ?? 'Tài khoản của bạn') ?></p>
        </div>

        <!-- Body -->
        <div class="profile-body">
            <!-- Error Messages -->
            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="form-errors">
                    <strong><i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra:</strong>
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $field => $error): ?>
                            <li><?= escape($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <!-- Profile Form -->
            <form method="POST" action="<?= url('client_update_profile') ?>" novalidate>
                <!-- Personal Information Section -->
                <div class="profile-section">
                    <div class="section-label">
                        <i class="fas fa-user-circle"></i> Thông Tin Cá Nhân
                    </div>

                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="full_name">
                            <i class="fas fa-user"></i> Tên Đầy Đủ
                        </label>
                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            value="<?= escape($user['full_name'] ?? '') ?>"
                            placeholder="Nhập tên đầy đủ của bạn"
                            required
                        >
                        <div class="form-control-info">
                            <i class="fas fa-info-circle"></i> Tên sẽ hiển thị trên hộ sơ công khai
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Địa Chỉ Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= escape($user['email'] ?? '') ?>"
                            placeholder="Nhập email"
                            required
                        >
                        <div class="form-control-info">
                            <i class="fas fa-info-circle"></i> Email được dùng để đăng nhập tài khoản
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Số Điện Thoại
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="<?= escape($user['phone'] ?? '') ?>"
                            placeholder="Nhập số điện thoại (10-11 chữ số)"
                            pattern="[0-9]{10,11}"
                        >
                        <div class="form-control-info">
                            <i class="fas fa-info-circle"></i> Số điện thoại dùng để liên hệ về các tour của bạn
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="profile-section">
                    <div class="section-label">
                        <i class="fas fa-lock"></i> Bảo Mật
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-key"></i> Mật Khẩu Mới
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Để trống nếu không muốn thay đổi"
                            minlength="6"
                        >
                        <div class="password-info">
                            <i class="fas fa-shield-alt"></i>
                            Mật khẩu phải có ít nhất 6 ký tự. Tạo mật khẩu mạnh với chữ cái, số và ký tự đặc biệt.
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Lưu Thay Đổi
                    </button>
                    <a href="<?= url('client_dashboard') ?>" class="btn-cancel">
                        <i class="fas fa-arrow-left"></i> Quay Lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
