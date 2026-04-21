<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-4">
                <a href="?action=admin_payments" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay Lại
                </a>
                <h2 class="d-inline-block ml-3">
                    <i class="fas fa-receipt"></i> Chi Tiết Thanh Toán - Booking #<?= htmlspecialchars($booking['id']) ?>
                </h2>
            </div>

            <!-- Booking Info -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Thông Tin Đặt Tour</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        <strong>Tour:</strong>
                                        <?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?>
                                    </p>
                                    <p>
                                        <strong>Số Người:</strong>
                                        <?= htmlspecialchars($booking['number_of_people'] ?? 0) ?> người
                                    </p>
                                    <p>
                                        <strong>Ngày Đặt:</strong>
                                        <?= date('d/m/Y H:i', strtotime($booking['booking_date'] ?? now())) ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <strong>Trạng Thái Booking:</strong>
                                        <?php
                                        $status = $booking['status'] ?? 'pending';
                                        $badgeColor = $status === 'confirmed' ? 'success' : ($status === 'cancelled' ? 'danger' : 'warning');
                                        ?>
                                        <span class="badge badge-<?= $badgeColor ?>"><?= ucfirst($status) ?></span>
                                    </p>
                                    <p>
                                        <strong>Tổng Tiền:</strong>
                                        <span class="text-danger h5"><?= number_format($booking['total_price'] ?? 0) ?> ₫</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Thông Tin Khách Hàng</h5>
                        </div>
                        <div class="card-body">
                            <p>
                                <strong>Tên:</strong>
                                <?= htmlspecialchars($booking['user_name'] ?? 'N/A') ?>
                            </p>
                            <p>
                                <strong>Email:</strong>
                                <a href="mailto:<?= $booking['email'] ?? '' ?>">
                                    <?= htmlspecialchars($booking['email'] ?? 'N/A') ?>
                                </a>
                            </p>
                            <p>
                                <strong>Điện Thoại:</strong>
                                <a href="tel:<?= $booking['phone'] ?? '' ?>">
                                    <?= htmlspecialchars($booking['phone'] ?? 'N/A') ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Chi Tiết Thanh Toán</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="payment-stat">
                                <p class="text-muted">Tổng Tiền Phải Thanh Toán</p>
                                <h4 class="text-primary"><?= number_format($booking['total_price'] ?? 0) ?> ₫</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="payment-stat">
                                <p class="text-muted">Cọc (30%)</p>
                                <h4 class="text-warning"><?= number_format(($booking['total_price'] ?? 0) * 0.3) ?> ₫</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="payment-stat">
                                <p class="text-muted">Đã Thanh Toán</p>
                                <h4 class="text-info"><?= number_format($booking['paid_amount'] ?? 0) ?> ₫</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="payment-stat">
                                <p class="text-muted">Còn Phải Thanh Toán</p>
                                <h4 class="text-danger">
                                    <?= number_format(($booking['total_price'] ?? 0) - ($booking['paid_amount'] ?? 0)) ?> ₫
                                </h4>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                <strong>Trạng Thái Thanh Toán:</strong>
                                <?php
                                $paymentStatus = $booking['payment_status'] ?? 'unpaid';
                                $paymentBadge = '';
                                $paymentColor = '';
                                
                                switch ($paymentStatus) {
                                    case 'unpaid':
                                        $paymentBadge = 'Chưa Thanh Toán';
                                        $paymentColor = 'danger';
                                        break;
                                    case 'deposit_paid':
                                        $paymentBadge = 'Đã Cọc 30%';
                                        $paymentColor = 'warning';
                                        break;
                                    case 'partially_paid':
                                        $paymentBadge = 'Thanh Toán Một Phần';
                                        $paymentColor = 'info';
                                        break;
                                    case 'paid':
                                        $paymentBadge = 'Thanh Toán Đầy Đủ';
                                        $paymentColor = 'success';
                                        break;
                                    default:
                                        $paymentBadge = htmlspecialchars($paymentStatus);
                                        $paymentColor = 'secondary';
                                }
                                ?>
                                <span class="badge badge-<?= $paymentColor ?> badge-lg"><?= $paymentBadge ?></span>
                            </p>
                            <p>
                                <strong>Phương Thức Thanh Toán:</strong>
                                <?php
                                $method = $booking['payment_method'] ?? 'Chưa thanh toán';
                                $methodLabel = '';
                                switch ($method) {
                                    case 'card': $methodLabel = 'Thẻ Tín Dụng'; break;
                                    case 'bank': $methodLabel = 'Chuyển Khoản Ngân Hàng'; break;
                                    case 'wallet': $methodLabel = 'Ví Điện Tử'; break;
                                    default: $methodLabel = htmlspecialchars($method);
                                }
                                ?>
                                <?= $methodLabel ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Mã Giao Dịch:</strong>
                                <code><?= htmlspecialchars($booking['transaction_id'] ?? 'Chưa thanh toán') ?></code>
                            </p>
                            <p>
                                <strong>Ngày Thanh Toán:</strong>
                                <?= $booking['payment_date'] ? date('d/m/Y H:i', strtotime($booking['payment_date'])) : 'Chưa thanh toán' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Status Update -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Xác Nhận Thanh Toán</h5>
                </div>
                <div class="card-body">
                    <form id="confirmPaymentForm">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        
                        <div class="form-group">
                            <label for="payment_status">Cập Nhật Trạng Thái Thanh Toán:</label>
                            <select name="payment_status" id="payment_status" class="form-control" required>
                                <option value="">-- Chọn Trạng Thái --</option>
                                <option value="unpaid" <?= ($booking['payment_status'] ?? '') === 'unpaid' ? 'selected' : '' ?>>
                                    Chưa Thanh Toán
                                </option>
                                <option value="deposit_paid" <?= ($booking['payment_status'] ?? '') === 'deposit_paid' ? 'selected' : '' ?>>
                                    Đã Cọc 30%
                                </option>
                                <option value="partially_paid" <?= ($booking['payment_status'] ?? '') === 'partially_paid' ? 'selected' : '' ?>>
                                    Thanh Toán Một Phần
                                </option>
                                <option value="paid" <?= ($booking['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>
                                    Thanh Toán Đầy Đủ
                                </option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="submitPaymentConfirmation()">
                            <i class="fas fa-check"></i> Xác Nhận & Cập Nhật
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notes -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ghi Chú</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Khi xác nhận thanh toán đầy đủ (Paid), hệ thống sẽ tự động cập nhật trạng thái booking thành "Confirmed".
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function submitPaymentConfirmation() {
    const form = document.getElementById('confirmPaymentForm');
    const formData = new FormData(form);

    fetch('?action=admin_payments_confirm', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('✓ Cập nhật thành công!');
            // Reload page after 1 second
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert('✗ Lỗi: ' + (data.message || 'Không rõ'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi khi gửi request: ' + error.message);
    });
}
</script>

<style>
.payment-stat {
    border-left: 4px solid #007bff;
    padding-left: 15px;
}

.badge-lg {
    font-size: 1.1em;
    padding: 0.5em 1em;
}

.badge-danger { background-color: #dc3545; }
.badge-warning { background-color: #ffc107; color: #333; }
.badge-info { background-color: #17a2b8; }
.badge-success { background-color: #28a745; }
.badge-secondary { background-color: #6c757d; }
</style>
