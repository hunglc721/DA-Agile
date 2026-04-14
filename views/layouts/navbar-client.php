<?php
/**
 * Navbar component cho client pages
 * Kiểm tra session và hiển thị tên user hoặc nút đăng nhập
 */
?>
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="<?= url('client_tour') ?>">Du Lịch Thông Minh</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="<?= url('/') ?>" class="nav-link">Trang chủ</a></li>
                <li class="nav-item"><a href="<?= url('client_about') ?>" class="nav-link">Giới thiệu</a></li>
                <li class="nav-item"><a href="<?= url('client_tour') ?>" class="nav-link">Du lịch</a></li>
                <li class="nav-item"><a href="<?= url('client_hotel') ?>" class="nav-link">Khách sạn</a></li>
                <li class="nav-item"><a href="<?= url('client_contact') ?>" class="nav-link">Liên hệ</a></li>

                <?php if (isLoggedIn()): ?>
                    <!-- User logged in -->
                    <li class="nav-item dropdown" style="margin-left: 10px;">
                        <a class="nav-link" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; gap: 5px; font-size: 13px; padding: 5px 10px;">
                            <i class="icon-user"></i> <span style="max-width: 100px; overflow: hidden; text-overflow: ellipsis;"><?= escape(getCurrentUser()['full_name'] ?? 'Người dùng') ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="background-color: #333; border: none; border-radius: 4px;">
                            <a class="dropdown-item" href="<?= url('client_dashboard') ?>" style="color: #fff; padding: 10px 15px;">
                                <i class="icon-briefcase"></i> Trang Cá Nhân
                            </a>
                            <a class="dropdown-item" href="<?= url('client_profile') ?>" style="color: #fff; padding: 10px 15px;">
                                <i class="icon-edit"></i> Chỉnh Sửa Hồ Sơ
                            </a>
                            <div class="dropdown-divider" style="background-color: #555;"></div>
                            <a class="dropdown-item" href="<?= url('logout') ?>" style="color: #fff; padding: 10px 15px;">
                                <i class="icon-sign-out"></i> Đăng Xuất
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <!-- User not logged in -->
                    <li class="nav-item cta"><a href="<?= url('login') ?>" class="nav-link"><span><i class="icon-sign-in"></i> Đăng nhập</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

