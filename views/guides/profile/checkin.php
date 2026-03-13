<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Page Header -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-clipboard-check"></i> Check-In & Điểm Danh
                    </h1>
                    <p class="text-muted mt-2">Tour: <strong><?= htmlspecialchars($tour['name']) ?></strong></p>
                </div>
                <a href="index.php?action=guide_assigned_tours" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay Lại
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-primary text-uppercase mb-1">
                                <small class="font-weight-bold">Tổng Khách</small>
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800"><?= $stats['total'] ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-success text-uppercase mb-1">
                                <small class="font-weight-bold">Đã Check-In</small>
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800"><?= $stats['checked_in'] ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-warning text-uppercase mb-1">
                                <small class="font-weight-bold">Chờ Check-In</small>
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800"><?= $stats['pending'] ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-danger text-uppercase mb-1">
                                <small class="font-weight-bold">Vắng Mặt</small>
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800"><?= $stats['absent'] ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkin Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-list"></i> Danh Sách Khách Hàng
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Chưa có khách hàng nào đăng ký tour này
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">STT</th>
                                        <th>Tên Khách Hàng</th>
                                        <th>Số Điện Thoại</th>
                                        <th>Email</th>
                                        <th style="width: 150px;">Số Người</th>
                                        <th style="width: 150px;">Trạng Thái</th>
                                        <th style="width: 120px;">Thời Gian</th>
                                        <th style="width: 150px;">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $index => $booking): ?>
                                        <?php 
                                            $status = $booking['checkin_status'] ?? 'pending';
                                            $checkinTime = $booking['checkin_time'];
                                            
                                            $statusBadge = [
                                                'checked_in' => '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Đã Check-In</span>',
                                                'absent' => '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Vắng Mặt</span>',
                                                'pending' => '<span class="badge badge-warning"><i class="fas fa-clock"></i> Chờ Check-In</span>'
                                            ];
                                        ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($booking['full_name']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($booking['phone']) ?></td>
                                            <td><?= htmlspecialchars($booking['email']) ?></td>
                                            <td class="text-center"><?= $booking['number_of_people'] ?> người</td>
                                            <td><?= $statusBadge[$status] ?? '<span class="badge badge-secondary">Không xác định</span>' ?></td>
                                            <td>
                                                <?php if ($checkinTime): ?>
                                                    <small class="text-muted"><?= date('H:i d/m', strtotime($checkinTime)) ?></small>
                                                <?php else: ?>
                                                    <small class="text-muted">-</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <form method="POST" action="index.php?action=guide_update_checkin" style="display: inline;">
                                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                                        <input type="hidden" name="status" value="checked_in">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Check-In">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="index.php?action=guide_update_checkin" style="display: inline;">
                                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                                        <input type="hidden" name="status" value="absent">
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Vắng Mặt">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                    <?php if ($status !== 'pending'): ?>
                                                        <form method="POST" action="index.php?action=guide_update_checkin" style="display: inline;">
                                                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                                            <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                                            <input type="hidden" name="status" value="pending">
                                                            <button type="submit" class="btn btn-sm btn-warning" title="Đặt lại">
                                                                <i class="fas fa-redo"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

.btn-group form {
    margin: 0 2px;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
