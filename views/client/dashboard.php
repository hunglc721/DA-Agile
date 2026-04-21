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

    <!-- Pending Bookings Confirmation Section -->
    <?php if (!empty($pendingBookings)): ?>
    <div class="pending-bookings-section" style="margin-bottom: 40px; background: linear-gradient(135deg, #fff7e6 0%, #ffe8cc 100%); border: 2px solid #ff6b35; border-radius: 12px; padding: 25px;">
        <h2 class="section-title" style="color: #ff6b35; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i> Xác Nhận Các Đơn Đặt Tour Chờ Phê Duyệt (<?= count($pendingBookings) ?>)
        </h2>

        <div class="pending-bookings-list">
            <?php foreach ($pendingBookings as $booking): ?>
            <div class="pending-booking-card" style="background: white; padding: 20px; margin-bottom: 15px; border-radius: 8px; border-left: 5px solid #ff6b35; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                    <div>
                        <p style="color: #999; font-size: 12px; margin-bottom: 5px; text-transform: uppercase;">Tour</p>
                        <p style="font-size: 15px; font-weight: 600; color: #333;">
                            <i class="fas fa-map-marker-alt" style="color: #ff6b35; margin-right: 8px;"></i>
                            <?= escape($booking['tour_name'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <p style="color: #999; font-size: 12px; margin-bottom: 5px; text-transform: uppercase;">Ngày Đặt / Số Người</p>
                        <p style="font-size: 15px; font-weight: 600; color: #333;">
                            📅 <?= date('d/m/Y', strtotime($booking['created_at'])) ?> / 👥 <?= $booking['number_of_people'] ?> người
                        </p>
                    </div>
                    <div>
                        <p style="color: #999; font-size: 12px; margin-bottom: 5px; text-transform: uppercase;">Tổng Tiền</p>
                        <p style="font-size: 15px; font-weight: 600; color: #ff6b35;">
                            💰 <?= number_format($booking['total_price'] ?? 0, 0, ',', '.') ?> đ
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; padding-top: 15px; border-top: 1px solid #eee;">
                    <!-- Xác nhận + Thanh toán Button -->
                    <button type="button" class="btn-confirm" onclick="openPaymentModal(<?= $booking['id'] ?>, '<?= escape($booking['tour_name']) ?>', <?= $booking['total_price'] ?>)" style="flex: 1; padding: 12px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 14px;">
                        <i class="fas fa-credit-card"></i> Xác Nhận & Thanh Toán
                    </button>

                    <!-- Chi Tiết Button -->
                    <a href="<?= url('client_tour') ?>" class="btn-detail" style="flex: 1; padding: 12px; background: #f0f0f0; color: #333; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; display: flex; align-items: center; justify-content: center; transition: all 0.3s; font-size: 14px;">
                        <i class="fas fa-info-circle"></i> Chi Tiết
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107;">
            <p style="margin: 0; color: #856404; font-size: 13px;">
                <i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong> Vui lòng xác nhận các đơn đặt tour sớm để chúng tôi có thể hoàn tất quy trình khác.
            </p>
        </div>
    </div>
    <?php endif; ?>

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

<!-- Payment Modal -->
<div id="paymentModal" class="payment-modal" style="display: none;">
    <div class="payment-modal-content">
        <!-- Close Button -->
        <button class="payment-modal-close" onclick="closePaymentModal()">&times;</button>

        <!-- Modal Header -->
        <div class="payment-modal-header">
            <h2>
                <i class="fas fa-credit-card"></i> Thanh Toán Đơn Đặt Tour
            </h2>
            <p class="payment-subtitle">Vui lòng hoàn tất thanh toán để xác nhận đơn đặt tour</p>
        </div>

        <!-- Order Summary -->
        <div class="payment-summary">
            <div class="summary-item">
                <span>Tour:</span>
                <strong id="paymentTourName">-</strong>
            </div>
            <div class="summary-item">
                <span>Tổng Tiền:</span>
                <strong id="paymentAmount" style="color: #ff6b35; font-size: 18px;">-</strong>
            </div>
        </div>

        <!-- Payment Method Selection -->
        <div class="payment-methods">
            <h3 style="margin-bottom: 20px; color: #333;">Chọn Phương Thức Thanh Toán</h3>

            <div class="method-option">
                <input type="radio" id="method-card" name="payment_method" value="card" checked>
                <label for="method-card">
                    <i class="fas fa-credit-card"></i>
                    <span>Thẻ Tín Dụng / Ghi Nợ</span>
                </label>
            </div>

            <div class="method-option">
                <input type="radio" id="method-bank" name="payment_method" value="bank">
                <label for="method-bank">
                    <i class="fas fa-university"></i>
                    <span>Chuyển Khoản Ngân Hàng</span>
                </label>
            </div>

            <div class="method-option">
                <input type="radio" id="method-wallet" name="payment_method" value="wallet">
                <label for="method-wallet">
                    <i class="fas fa-wallet"></i>
                    <span>Ví Điện Tử</span>
                </label>
            </div>
        </div>

        <!-- Card Form -->
        <div id="cardForm" class="payment-form" style="display: none; margin-top: 30px;">
            <h3 style="margin-bottom: 20px; color: #333;">Thông Tin Thẻ</h3>

            <div class="form-group">
                <label>Tên Chủ Thẻ</label>
                <input type="text" id="cardName" placeholder="Nhập tên trên thẻ..." class="form-control" style="padding: 12px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
            </div>

            <div class="form-group">
                <label>Số Thẻ</label>
                <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" class="form-control" style="padding: 12px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 14px;" oninput="formatCardNumber(this)">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Expiry (MM/YY)</label>
                    <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5" class="form-control" style="padding: 12px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 14px;" oninput="formatExpiry(this)">
                </div>
                <div class="form-group">
                    <label>CVV</label>
                    <input type="text" id="cardCVV" placeholder="123" maxlength="4" class="form-control" style="padding: 12px; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                </div>
            </div>
        </div>

        <!-- Bank Transfer Info -->
        <div id="bankForm" class="payment-form" style="display: none; margin-top: 30px; background: #f0f9ff; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6;">
            <h3 style="margin-bottom: 15px; color: #333;">Thông Tin Chuyển Khoản</h3>
            <p><strong>Ngân Hàng:</strong> Vietcombank (VCB)</p>
            <p><strong>Tên Tài Khoản:</strong> Du Lich Thong Minh Co</p>
            <p><strong>Số Tài Khoản:</strong> 1234567890</p>
            <p><strong>Chi Nhánh:</strong> TP.HCM</p>
            <p style="margin-top: 15px; background: white; padding: 12px; border-radius: 6px; border: 2px solid #dbeafe;">
                <strong>Nội Dung Chuyển Khoản:</strong><br>
                <code id="transferContent">BOOKING-</code>
            </p>
            <p style="color: #666; font-size: 13px; margin-top: 15px;">⏱️ Vui lòng chuyển khoản trong vòng 24 giờ. Chúng tôi sẽ xác nhận trong 1-2 giờ sau khi nhận tiền.</p>
        </div>

        <!-- Wallet Info -->
        <div id="walletForm" class="payment-form" style="display: none; margin-top: 30px; background: #f0fdf4; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981;">
            <h3 style="margin-bottom: 15px; color: #333;">Ví Điện Tử Hỗ Trợ</h3>
            <p>✅ Momo</p>
            <p>✅ ZaloPay</p>
            <p>✅ ViettelPay</p>
            <p style="margin-top: 15px; padding: 12px; background: white; border-radius: 6px; border: 2px solid #dcfce7;">
                <strong>SĐT nhận tiền:</strong> <code>0909 876 543</code>
            </p>
            <p style="color: #666; font-size: 13px; margin-top: 15px;">💬 Ghi rõ mã booking để chúng tôi xác nhận nhanh nhất.</p>
        </div>

        <!-- Action Buttons -->
        <div class="payment-actions" style="margin-top: 30px; display: flex; gap: 12px;">
            <button type="button" onclick="processPayment()" class="btn-payment-submit" style="flex: 1; padding: 14px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 16px; transition: all 0.3s;">
                <i class="fas fa-check-circle"></i> Thanh Toán & Xác Nhận
            </button>
            <button type="button" onclick="closePaymentModal()" class="btn-payment-cancel" style="flex: 0.5; padding: 14px; background: #f0f0f0; color: #333; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 16px;">
                Hủy
            </button>
        </div>

        <!-- Security Notice -->
        <div style="margin-top: 20px; padding: 12px; background: #f3f4f6; border-radius: 6px; border-left: 4px solid #f59e0b; font-size: 13px; color: #666;">
            <i class="fas fa-shield-alt"></i> <strong>Bảo Mật:</strong> Thông tin thanh toán của bạn được mã hóa đầy đủ theo tiêu chuẩn PCI-DSS
        </div>
    </div>
</div>

<style>
    /* Payment Modal Styles */
    .payment-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    }

    .payment-modal.active {
        display: flex;
    }

    .payment-modal-content {
        background: white;
        border-radius: 12px;
        padding: 40px;
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        position: relative;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .payment-modal-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 32px;
        cursor: pointer;
        color: #999;
        transition: color 0.3s;
    }

    .payment-modal-close:hover {
        color: #333;
    }

    .payment-modal-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .payment-modal-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin: 0 0 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .payment-modal-header h2 i {
        color: #ff6b35;
    }

    .payment-subtitle {
        color: #666;
        font-size: 14px;
        margin: 0;
    }

    .payment-summary {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 2px solid #bfdbfe;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }

    .summary-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .summary-item span {
        color: #666;
        font-size: 14px;
    }

    .method-option {
        margin-bottom: 15px;
    }

    .method-option input[type="radio"] {
        display: none;
    }

    .method-option label {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        background: #fafafa;
    }

    .method-option input[type="radio"]:checked + label {
        border-color: #ff6b35;
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.05) 0%, rgba(255, 140, 66, 0.05) 100%);
    }

    .method-option label i {
        font-size: 20px;
        color: #ff6b35;
    }

    .method-option label span {
        font-weight: 600;
        color: #333;
    }

    .form-control:focus {
        outline: none;
        border-color: #ff6b35 !important;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    @media (max-width: 600px) {
        .payment-modal-content {
            padding: 25px;
        }

        .payment-actions {
            flex-direction: column;
        }

        .btn-payment-cancel {
            flex: 1 !important;
        }
    }
</style>

<script>
    let currentBookingId = null;
    let currentBookingAmount = null;

    function openPaymentModal(bookingId, tourName, amount) {
        currentBookingId = bookingId;
        currentBookingAmount = amount;

        document.getElementById('paymentTourName').textContent = tourName;
        document.getElementById('paymentAmount').textContent = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
        document.getElementById('transferContent').textContent = 'BOOKING-' + bookingId;
        document.getElementById('paymentModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.querySelectorAll('.payment-form').forEach(f => f.style.display = 'none');
    }

    // Payment method switching
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-form').forEach(f => f.style.display = 'none');
            if (this.value === 'card') {
                document.getElementById('cardForm').style.display = 'block';
            } else if (this.value === 'bank') {
                document.getElementById('bankForm').style.display = 'block';
            } else if (this.value === 'wallet') {
                document.getElementById('walletForm').style.display = 'block';
            }
        });
    });

    // Format card number
    function formatCardNumber(input) {
        let value = input.value.replace(/\s/g, '');
        let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
        input.value = formatted;
    }

    // Format expiry
    function formatExpiry(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        input.value = value;
    }

    // Process payment
    function processPayment() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        let isValid = true;

        if (method === 'card') {
            const cardName = document.getElementById('cardName').value.trim();
            const cardNumber = document.getElementById('cardNumber').value.trim();
            const cardExpiry = document.getElementById('cardExpiry').value.trim();
            const cardCVV = document.getElementById('cardCVV').value.trim();

            if (!cardName || cardName.length < 3) {
                alert('Vui lòng nhập tên chủ thẻ');
                isValid = false;
            } else if (!cardNumber || cardNumber.replace(/\s/g, '').length !== 16) {
                alert('Vui lòng nhập số thẻ hợp lệ (16 chữ số)');
                isValid = false;
            } else if (!cardExpiry || cardExpiry.length !== 5) {
                alert('Vui lòng nhập ngày hết hạn (MM/YY)');
                isValid = false;
            } else if (!cardCVV || cardCVV.length < 3) {
                alert('Vui lòng nhập CVV');
                isValid = false;
            }
        }

        if (isValid) {
            // Submit payment
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= url("client_confirm_booking") ?>';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'booking_id';
            input.value = currentBookingId;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = 'payment_method';
            methodInput.value = method;

            form.appendChild(input);
            form.appendChild(methodInput);
            form.submit();
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePaymentModal();
        }
    });
</script>

