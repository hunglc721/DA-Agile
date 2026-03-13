<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="https://via.placeholder.com/100" class="rounded-circle" width="100" alt="Avatar">
                        <h5 class="mt-3"><?= $user['full_name'] ?? 'N/A' ?></h5>
                        <p class="text-muted"><?= $user['email'] ?? 'N/A' ?></p>
                    </div>
                    <hr>
                    <nav class="nav flex-column">
                        <a href="?action=guide_dashboard" class="nav-link active">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="?action=guide_assigned_tours" class="nav-link">
                            <i class="fas fa-map-location-dot"></i> Tour Được Phân Công
                        </a>
                        <a href="?action=guide_customer_feedback" class="nav-link">
                            <i class="fas fa-comments"></i> Phản Hồi Khách Hàng
                        </a>
                        <a href="?action=guide_notifications" class="nav-link">
                            <i class="fas fa-bell"></i> Thông Báo
                            <?php if ($notifications_count > 0): ?>
                                <span class="badge bg-danger"><?= $notifications_count ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="?action=guide_communication" class="nav-link">
                            <i class="fas fa-envelope"></i> Trao Đổi Với Admin
                        </a>
                        <hr>
                        <a href="?action=logout" class="nav-link text-danger">
                            <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Tour Được Phân Công</h5>
                            <h2><?= $assigned_tours ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Tour Chờ Xác Nhận</h5>
                            <h2><?= $pending_tours ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Báo Cáo Sự Cố Đã Gửi</h5>
                            <h2><?= $incidents ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Thông Báo Chưa Đọc</h5>
                            <h2><?= $notifications_count ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📋 Chức Năng Chính</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="?action=guide_assigned_tours" class="list-group-item list-group-item-action">
                            <i class="fas fa-eye"></i> Xem Tour Được Phân Công
                        </a>
                        <a href="?action=guide_customer_feedback" class="list-group-item list-group-item-action">
                            <i class="fas fa-star"></i> Xem Phản Hồi Khách Hàng
                        </a>
                        <a href="?action=guide_notifications" class="list-group-item list-group-item-action">
                            <i class="fas fa-bell"></i> Xem Thông Báo
                        </a>
                        <a href="?action=guide_communication" class="list-group-item list-group-item-action">
                            <i class="fas fa-comments"></i> Trao Đổi Với Admin
                        </a>
                    </div>
                </div>
            </div>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.nav-link {
    color: #333;
    padding: 0.5rem 0;
}

.nav-link:hover {
    color: #007bff;
}

.nav-link.active {
    color: #007bff;
    border-left: 3px solid #007bff;
    padding-left: 0.75rem;
}
</style>

