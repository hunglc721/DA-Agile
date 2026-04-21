<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">
                <i class="fas fa-credit-card"></i> Quản Lý Thanh Toán
            </h2>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Bộ Lọc</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="form-inline">
                        <input type="hidden" name="action" value="admin_payments">
                        
                        <div class="form-group mr-3">
                            <label for="status" class="mr-2">Trạng Thái Thanh Toán:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">-- Tất Cả --</option>
                                <option value="unpaid" <?= $status === 'unpaid' ? 'selected' : '' ?>>Chưa Thanh Toán</option>
                                <option value="deposit_paid" <?= $status === 'deposit_paid' ? 'selected' : '' ?>>Đã Cọc 30%</option>
                                <option value="partially_paid" <?= $status === 'partially_paid' ? 'selected' : '' ?>>Thanh Toán Một Phần</option>
                                <option value="paid" <?= $status === 'paid' ? 'selected' : '' ?>>Thanh Toán Đầy Đủ</option>
                            </select>
                        </div>

                        <div class="form-group mr-3">
                            <label for="search" class="mr-2">Tìm Kiếm:</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Mã booking, tên tour, tên khách..." value="<?= htmlspecialchars($search ?? '') ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm Kiếm
                        </button>

                        <?php if ($status || $search): ?>
                            <a href="?action=admin_payments" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Xóa Bộ Lọc
                            </a>
                        <?php endif; ?>

                        <a href="?action=admin_payments_export<?= $status ? '&status=' . $status : '' ?>" class="btn btn-success ml-2">
                            <i class="fas fa-download"></i> Xuất Excel
                        </a>
                    </form>
                </div>
            </div>

            <!-- Payment Statistics -->
            <div class="row mb-4" id="stats">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Chưa Thanh Toán</h6>
                            <p class="card-text text-danger">
                                <strong id="unpaid-count">0</strong> đơn
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Đã Cọc 30%</h6>
                            <p class="card-text text-warning">
                                <strong id="deposit-count">0</strong> đơn
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Thanh Toán Một Phần</h6>
                            <p class="card-text text-info">
                                <strong id="partial-count">0</strong> đơn
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Thanh Toán Đầy Đủ</h6>
                            <p class="card-text text-success">
                                <strong id="paid-count">0</strong> đơn
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Danh Sách Booking</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Không có booking nào.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Mã Booking</th>
                                        <th>Tour</th>
                                        <th>Khách Hàng</th>
                                        <th>Số Người</th>
                                        <th>Tổng Tiền</th>
                                        <th>Đã Thanh Toán</th>
                                        <th>Trạng Thái</th>
                                        <th>Ngày Booking</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td>
                                                <strong>#<?= htmlspecialchars($booking['id']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?></td>
                                            <td>
                                                <?= htmlspecialchars($booking['user_name'] ?? 'N/A') ?>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($booking['email'] ?? '') ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($booking['number_of_people'] ?? 0) ?></td>
                                            <td><?= number_format($booking['total_price'] ?? 0) ?> ₫</td>
                                            <td><?= number_format($booking['paid_amount'] ?? 0) ?> ₫</td>
                                            <td>
                                                <?php
                                                $status = $booking['payment_status'] ?? 'unpaid';
                                                $badge = '';
                                                $color = '';
                                                
                                                switch ($status) {
                                                    case 'unpaid':
                                                        $badge = 'Chưa Thanh Toán';
                                                        $color = 'danger';
                                                        break;
                                                    case 'deposit_paid':
                                                        $badge = 'Đã Cọc 30%';
                                                        $color = 'warning';
                                                        break;
                                                    case 'partially_paid':
                                                        $badge = 'Thanh Toán Một Phần';
                                                        $color = 'info';
                                                        break;
                                                    case 'paid':
                                                        $badge = 'Thanh Toán Đầy Đủ';
                                                        $color = 'success';
                                                        break;
                                                    default:
                                                        $badge = htmlspecialchars($status);
                                                        $color = 'secondary';
                                                }
                                                ?>
                                                <span class="badge badge-<?= $color ?>"><?= $badge ?></span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($booking['booking_date'] ?? now())) ?></td>
                                            <td>
                                                <a href="?action=admin_payments_show&id=<?= $booking['id'] ?>" class="btn btn-sm btn-info" title="Xem Chi Tiết">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load payment statistics
    fetch('?action=admin_payments_stats')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('unpaid-count').textContent = data.data.unpaid.count;
                document.getElementById('deposit-count').textContent = data.data.deposit_paid.count;
                document.getElementById('partial-count').textContent = data.data.partially_paid.count;
                document.getElementById('paid-count').textContent = data.data.paid.count;
            }
        })
        .catch(error => console.error('Error:', error));
});
</script>

<style>
.badge-danger { background-color: #dc3545; }
.badge-warning { background-color: #ffc107; color: #333; }
.badge-info { background-color: #17a2b8; }
.badge-success { background-color: #28a745; }
.badge-secondary { background-color: #6c757d; }
</style>
