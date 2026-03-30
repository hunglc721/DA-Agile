<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> Danh Sách Khách Hàng Tham Gia Tour: <?= htmlspecialchars($tour['name']) ?>
                        </h5>
                        <span class="badge badge-light">Tổng: <?= count($customers) ?> khách</span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($customers)): ?>
                        <!-- Thống kê nhanh -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted small">Tổng Khách</h6>
                                        <h3 class="text-primary font-weight-bold"><?= count($customers) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted small">Đã Xác Nhận</h6>
                                        <h3 class="text-success font-weight-bold">
                                            <?php
                                                $confirmed_count = 0;
                                                foreach ($customers as $c) {
                                                    if ($c['booking_status'] === 'confirmed') $confirmed_count++;
                                                }
                                                echo $confirmed_count;
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted small">Đã Check-In</h6>
                                        <h3 class="text-info font-weight-bold">
                                            <?php
                                                $checkin_count = 0;
                                                foreach ($customers as $c) {
                                                    if ($c['checkin_status'] === 'checked_in') $checkin_count++;
                                                }
                                                echo $checkin_count;
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted small">Tổng Số Người</h6>
                                        <h3 class="text-warning font-weight-bold">
                                            <?php
                                                $total_people = 0;
                                                foreach ($customers as $c) {
                                                    $total_people += (int)$c['number_of_people'];
                                                }
                                                echo $total_people;
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Danh sách khách hàng chi tiết -->
                        <div class="customer-details-list">
                            <?php foreach ($customers as $idx => $customer): ?>
                                <?php
                                    $statusBadgeClass = $customer['booking_status'] === 'confirmed' ? 'success' : 'warning';
                                    $checkinBadgeClass = 'secondary';
                                    $checkinText = '⏳ Chưa Check-In';

                                    if ($customer['checkin_status'] === 'checked_in') {
                                        $checkinBadgeClass = 'success';
                                        $checkinText = '✓ Đã Check-In';
                                    } elseif ($customer['checkin_status'] === 'absent') {
                                        $checkinBadgeClass = 'danger';
                                        $checkinText = '✗ Vắng Mặt';
                                    }
                                ?>
                                <div class="card mb-3 border-left-info customer-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-0"><i class="fas fa-user-circle text-primary"></i> <?= htmlspecialchars($customer['full_name']) ?></h6>
                                                <small class="text-muted">Khách #<?= $idx + 1 ?></small>
                                            </div>
                                            <div class="badge-group">
                                                <span class="badge badge-<?= $statusBadgeClass ?>">
                                                    <i class="fas fa-<?= $customer['booking_status'] === 'confirmed' ? 'check-circle' : 'hourglass-half' ?>"></i>
                                                    <?= $customer['booking_status'] === 'confirmed' ? 'Đã Xác Nhận' : 'Chờ Xác Nhận' ?>
                                                </span>
                                                <span class="badge badge-<?= $checkinBadgeClass ?>">
                                                    <?= $checkinText ?>
                                                </span>
                                            </div>
                                        </div>
                                        <hr class="my-2">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="d-block text-muted mb-2">
                                                    <i class="fas fa-phone text-info"></i> <strong>Điện Thoại:</strong>
                                                    <br><span class="ml-3"><?= htmlspecialchars($customer['phone'] ?? 'N/A') ?></span>
                                                </small>
                                                <small class="d-block text-muted mb-2">
                                                    <i class="fas fa-envelope text-info"></i> <strong>Email:</strong>
                                                    <br><span class="ml-3"><?= htmlspecialchars($customer['email'] ?? 'N/A') ?></span>
                                                </small>
                                                <small class="d-block text-muted">
                                                    <i class="fas fa-map-marker-alt text-info"></i> <strong>Địa Chỉ:</strong>
                                                    <br><span class="ml-3"><?= htmlspecialchars($customer['address'] ?? 'N/A') ?></span>
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="d-block text-muted mb-2">
                                                    <i class="fas fa-users text-warning"></i> <strong>Số Người:</strong>
                                                    <br><span class="ml-3"><?= $customer['number_of_people'] ?> người</span>
                                                </small>
                                                <small class="d-block text-muted mb-2">
                                                    <i class="fas fa-credit-card text-success"></i> <strong>Tổng Tiền:</strong>
                                                    <br><span class="ml-3 text-success font-weight-bold"><?= number_format($customer['total_price'] ?? 0, 0, ',', '.') ?>₫</span>
                                                </small>
                                                <small class="d-block text-muted">
                                                    <i class="fas fa-calendar text-danger"></i> <strong>Ngày Đặt:</strong>
                                                    <br><span class="ml-3"><?= date('d/m/Y H:i', strtotime($customer['booking_date'] ?? date('Y-m-d H:i:s'))) ?></span>
                                                </small>
                                            </div>
                                        </div>

                                        <?php if (!empty($customer['special_requests'])): ?>
                                            <div class="mt-2 alert alert-warning alert-sm p-2 mb-0">
                                                <small>
                                                    <i class="fas fa-lightbulb"></i> <strong>Yêu Cầu Đặc Biệt:</strong>
                                                    <br><span class="ml-3"><?= htmlspecialchars($customer['special_requests']) ?></span>
                                                </small>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($customer['checkin_status'] === 'checked_in' && !empty($customer['checkin_time'])): ?>
                                            <div class="mt-2 alert alert-info alert-sm p-2 mb-0">
                                                <small>
                                                    <i class="fas fa-check-circle"></i> <strong>Thời gian Check-In:</strong>
                                                    <br><span class="ml-3"><?= date('d/m/Y H:i:s', strtotime($customer['checkin_time'])) ?></span>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Chưa có khách hàng nào được xác nhận cho tour này.
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="?action=guide_assigned_tours" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay Lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.customer-card {
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.customer-card:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    background: #fff;
}

.customer-card hr {
    margin: 0.75rem 0;
}

.customer-card small {
    font-size: 0.85rem;
}

.badge-group {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.alert-sm {
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
    margin-top: 0.5rem;
}

.ml-3 {
    margin-left: 0.5rem;
}

@media (max-width: 768px) {
    .row > .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .customer-details-list .row {
        flex-direction: column;
    }

    .customer-details-list .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>
