<!-- Success/Error Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <div><?= escape($_SESSION['success']) ?></div>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <div><?= escape($_SESSION['error']) ?></div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Main Content -->
<div class="container container-main">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1>
                <i class="fas fa-user-circle"></i>
                Chào mừng, <?= escape($user['full_name'] ?? 'Bạn') ?>!
            </h1>
            <p>Quản lý các chuyến du lịch và hồ sơ cá nhân của bạn</p>
            <div class="quick-actions">
                <a href="<?= url('client_profile') ?>" class="btn-action primary">
                    <i class="fas fa-edit"></i> Chỉnh Sửa Hồ Sơ
                </a>
                <a href="<?= url('client_tour') ?>" class="btn-action secondary">
                    <i class="fas fa-search"></i> Tìm Tour Mới
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-number"><?= $stats['total_bookings'] ?? 0 ?></div>
            <p class="stat-label">Tổng Đơn Đặt</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-number"><?= $stats['pending_count'] ?? 0 ?></div>
            <p class="stat-label">Chờ Xác Nhận</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon confirmed">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number"><?= $stats['confirmed_count'] ?? 0 ?></div>
            <p class="stat-label">Đã Xác Nhận</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon completed">
                <i class="fas fa-flag-checkered"></i>
            </div>
            <div class="stat-number"><?= $stats['completed_count'] ?? 0 ?></div>
            <p class="stat-label">Hoàn Thành</p>
        </div>
    </div>

    <!-- Bookings Section -->
    <div class="bookings-section">
        <h2 class="section-title">
            <i class="fas fa-list"></i> Các Đơn Đặt Tour Gần Đây
        </h2>

        <?php if (empty($bookings)): ?>
            <div class="empty-message">
                <i class="fas fa-inbox"></i>
                <p><strong>Chưa có đơn đặt tour nào</strong></p>
                <p style="margin-top: 10px;">Hãy bắt đầu khám phá các tour du lịch tuyệt vời của chúng tôi</p>
                <a href="<?= url('client_tour') ?>" style="color: var(--primary); font-weight: 600; text-decoration: none; display: inline-block; margin-top: 15px;">
                    <i class="fas fa-arrow-right"></i> Xem Các Tour →
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Ngày Đặt</th>
                            <th>Số Người</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>
                                    <strong><?= escape($booking['tour_name'] ?? 'N/A') ?></strong>
                                </td>
                                <td><?= date('d/m/Y', strtotime($booking['created_at'])) ?></td>
                                <td class="text-center"><?= $booking['number_of_people'] ?> người</td>
                                <td><strong><?= number_format($booking['total_price'] ?? 0, 0, ',', '.') ?> đ</strong></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($booking['status'] ?? 'pending') ?>">
                                        <?php
                                            $statusMap = [
                                                'pending' => 'Chờ Xác Nhận',
                                                'confirmed' => 'Đã Xác Nhận',
                                                'completed' => 'Hoàn Thành',
                                                'cancelled' => 'Đã Hủy'
                                            ];
                                            echo $statusMap[$booking['status'] ?? 'pending'] ?? 'Không rõ';
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
