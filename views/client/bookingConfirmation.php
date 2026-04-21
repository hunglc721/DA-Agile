<?php
$booking = $booking ?? [];
$customerList = $customerList ?? [];
?>

<style>
    .confirmation-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .confirmation-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .confirmation-header .icon {
        font-size: 48px;
        color: #10b981;
        margin-bottom: 15px;
    }

    .confirmation-header h1 {
        font-size: 32px;
        color: #059669;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .confirmation-header p {
        color: #666;
        font-size: 16px;
    }

    .booking-number {
        background: white;
        border: 2px dashed #10b981;
        padding: 20px 30px;
        border-radius: 8px;
        text-align: center;
        margin: 20px 0;
        font-size: 18px;
        font-weight: 600;
        color: #059669;
    }

    .booking-number .label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    /* Sections */
    .confirm-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .confirm-section h3 {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 2px solid #f3f4f6;
        padding-bottom: 15px;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .info-item .label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .info-item .value {
        font-size: 16px;
        color: #333;
        font-weight: 600;
        word-break: break-word;
    }

    .info-item.highlight {
        border-left-color: #10b981;
        background: rgba(16, 185, 129, 0.05);
    }

    .info-item.highlight .value {
        color: #059669;
        font-size: 18px;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fed7aa;
        color: #92400e;
    }

    /* Highlights Section */
    .highlight-box {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
        border-left: 4px solid #10b981;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .highlight-box h4 {
        color: #059669;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .highlight-box p {
        color: #047857;
        font-size: 14px;
        margin: 5px 0;
    }

    /* Payment Section */
    .payment-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 30px;
        margin: 20px 0;
        text-align: center;
    }

    .payment-section h3 {
        border: none;
        color: white;
        margin-bottom: 20px;
    }

    .payment-section h3 i {
        color: rgba(255, 255, 255, 0.8);
    }

    .amount-large {
        font-size: 36px;
        font-weight: 700;
        margin: 15px 0;
    }

    .btn-payment {
        display: inline-block;
        background: white;
        color: #667eea;
        padding: 12px 30px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .btn-payment:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .payment-note {
        font-size: 12px;
        margin-top: 15px;
        opacity: 0.9;
    }

    /* Customer List */
    .customer-item {
        background: #f9fafb;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        border-left: 4px solid #667eea;
    }

    .customer-item h5 {
        margin: 0 0 10px 0;
        color: #333;
        font-weight: 600;
    }

    .customer-detail {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        font-size: 13px;
    }

    .customer-detail .detail-item {
        color: #666;
    }

    .customer-detail .label {
        color: #999;
        font-size: 11px;
        text-transform: uppercase;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #666;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .btn-outline {
        background: transparent;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-outline:hover {
        background: #667eea;
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="confirmation-container">
    <!-- Header -->
    <div class="confirmation-header">
        <div class="icon">✓</div>
        <h1>Đặt Tour Thành Công!</h1>
        <p>Cảm ơn bạn đã chọn dịch vụ của chúng tôi</p>
    </div>

    <!-- Booking Number -->
    <div class="booking-number">
        <div class="label">Mã Đặt Chỗ</div>
        <div>#<?php echo $booking['id']; ?></div>
    </div>

    <!-- Tour Details -->
    <div class="confirm-section">
        <h3><i class="fa fa-info-circle"></i> Thông Tin Tour</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="label">Tên Tour</div>
                <div class="value"><?php echo htmlspecialchars($booking['tour_name']); ?></div>
            </div>
            <div class="info-item">
                <div class="label">Ngày Khởi Hành</div>
                <div class="value"><?php echo $booking['departure_date'] ? date('d/m/Y', strtotime($booking['departure_date'])) : 'Sẽ thông báo'; ?></div>
            </div>
            <div class="info-item">
                <div class="label">Số Người Đi</div>
                <div class="value"><?php echo $booking['number_of_people']; ?> người</div>
            </div>
            <div class="info-item highlight">
                <div class="label">Tổng Tiền</div>
                <div class="value"><?php echo number_format($booking['total_price']); ?>đ</div>
            </div>
        </div>

        <!-- Status -->
        <div style="margin-top: 20px;">
            <strong>Trạng Thái:</strong>
            <span class="status-badge status-pending">✓ Chờ Xác Nhận</span>
        </div>
    </div>

    <!-- Customer List (if provided) -->
    <?php if (!empty($customerList)): ?>
        <div class="confirm-section">
            <h3><i class="fa fa-users"></i> Danh Sách Khách Hàng</h3>
            <?php foreach ($customerList as $idx => $customer): ?>
                <div class="customer-item">
                    <h5>#{$idx + 1}. <?php echo htmlspecialchars($customer['name']); ?></h5>
                    <div class="customer-detail">
                        <div class="detail-item">
                            <div class="label">Email</div>
                            <div><?php echo htmlspecialchars($customer['email']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="label">Điện Thoại</div>
                            <div><?php echo htmlspecialchars($customer['phone']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="label">Tuổi</div>
                            <div><?php echo $customer['age']; ?> tuổi</div>
                        </div>
                        <?php if (!empty($customer['id_number'])): ?>
                            <div class="detail-item">
                                <div class="label">CMND/Passport</div>
                                <div><?php echo htmlspecialchars($customer['id_number']); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Special Requests -->
    <?php if (!empty($booking['special_requests'])): ?>
        <div class="confirm-section">
            <h3><i class="fa fa-comment"></i> Ghi Chú & Yêu Cầu Đặc Biệt</h3>
            <div style="background: #f9fafb; padding: 15px; border-radius: 8px; color: #333; line-height: 1.6;">
                <?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Payment Section -->
    <div class="payment-section">
        <h3><i class="fa fa-credit-card"></i> Thanh Toán</h3>
        <div class="amount-large"><?php echo number_format($booking['total_price']); ?>đ</div>

        <div style="margin: 20px 0;">
            <strong style="opacity: 0.9;">Các Phương Thức Thanh Toán:</strong>
            <p style="font-size: 14px; margin: 10px 0;">💳 Chuyển khoản ngân hàng • 💳 Thẻ tín dụng • 💵 Tiền mặt</p>
        </div>

        <a href="#payment" class="btn-payment">→ Tiếp Tục Thanh Toán</a>

        <div class="payment-note">
            ℹ️ Thanh toán là tùy chọn tại bước này. Vui lòng hoàn thành thanh toán trước ngày khởi hành
        </div>
    </div>

    <!-- Important Info -->
    <div class="highlight-box">
        <h4><i class="fa fa-bell"></i> Thông Tin Quan Trọng</h4>
        <p><strong>✓ Email xác nhận:</strong> Chúng tôi sẽ gửi email xác nhận chi tiết cho bạn trong vòng 24 giờ</p>
        <p><strong>✓ Hủy đặt chỗ:</strong> Bạn có thể hủy đặt chỗ bất cứ lúc nào từ trang cá nhân</p>
        <p><strong>✓ Liên hệ:</strong> Nếu có câu hỏi, vui lòng gọi <strong><?php echo CONTACT_PHONE ?? '+84 28 3829 2910'; ?></strong> hoặc email <strong><?php echo CONTACT_EMAIL ?? 'info@dulichthongminh.vn'; ?></strong></p>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="<?php echo url('?action=client_dashboard'); ?>" class="btn btn-primary">📊 Xem Trang Cá Nhân</a>
        <a href="<?php echo url('?action=client_booking'); ?>" class="btn btn-outline">➕ Đặt Tour Khác</a>
        <a href="<?php echo url('?action=client'); ?>" class="btn btn-secondary">← Về Trang Chủ</a>
    </div>
</div>
