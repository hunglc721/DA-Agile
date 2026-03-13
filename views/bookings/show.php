<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi Tiết Đơn Hàng #<?= $booking['id'] ?></h1>
        <a href="index.php?action=bookings" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- THÔNG TIN CHÍNH -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <?php if ($booking['booking_type'] === 'group'): ?>
                            <i class="fas fa-users"></i> ĐƠN ĐẶT ĐOÀN
                        <?php else: ?>
                            <i class="fas fa-user"></i> ĐƠN ĐẶT KHÁCH LẺ
                        <?php endif; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-map-marker-alt"></i> Thông Tin Tour</h5>
                            <p class="mb-1"><strong>Tên Tour:</strong> <?= htmlspecialchars($booking['tour_name']) ?></p>
                            <p class="mb-1"><strong>Mã Tour:</strong> <span class="badge badge-info"><?= htmlspecialchars($booking['tour_code']) ?></span></p>
                            <p class="mb-1"><strong>Ngày Đặt:</strong> <?= date('d/m/Y H:i', strtotime($booking['created_at'] ?? $booking['booking_date'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-user-tie"></i> Thông Tin Liên Hệ</h5>
                            <p class="mb-1"><strong>Họ Tên:</strong> <?= htmlspecialchars($booking['full_name'] ?? 'Khách vãng lai') ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($booking['email'] ?? 'N/A') ?></p>
                            <p class="mb-1"><strong>Điện Thoại:</strong> <?= htmlspecialchars($booking['phone'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- CHI TIẾT KHÁCH -->
                    <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-list"></i> Chi Tiết Đặt Hàng</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Loại Đặt</th>
                                <td>
                                    <?php if ($booking['booking_type'] === 'group'): ?>
                                        <span class="badge badge-warning" style="font-size: 12px; padding: 8px;">ĐOÀN (GIT)</span>
                                    <?php else: ?>
                                        <span class="badge badge-info" style="font-size: 12px; padding: 8px;">KHÁCH LẺ (FIT)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Số Lượng Khách</th>
                                <td><strong><?= $booking['number_of_people'] ?> người</strong></td>
                            </tr>
                            <?php if ($booking['booking_type'] === 'group' && !empty($booking['special_requests']) && strpos($booking['special_requests'], 'Tên đoàn:') === 0): ?>
                            <tr>
                                <th>Tên Đoàn</th>
                                <td><?= htmlspecialchars(str_replace('Tên đoàn: ', '', explode("\n", $booking['special_requests'])[0])) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr class="table-success">
                                <th class="font-weight-bold">TỔNG TIỀN</th>
                                <td class="font-weight-bold text-success" style="font-size: 1.3rem;">
                                    <?= number_format($booking['total_price']) ?> VNĐ
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- GHI CHÚ -->
                    <?php if (!empty($booking['special_requests'])): ?>
                    <hr>
                    <h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-sticky-note"></i> Ghi Chú / Yêu Cầu Đặc Biệt</h6>
                    <div class="alert alert-light border-left-primary">
                        <?php 
                            $notes = $booking['special_requests'];
                            // Nếu có "Tên đoàn:" ở đầu, cắt bỏ
                            if (strpos($notes, 'Tên đoàn:') === 0) {
                                $notes = trim(substr($notes, strpos($notes, "\n") + 1));
                            }
                            echo htmlspecialchars($notes) ?: '(Không có ghi chú)';
                        ?>
                    </div>
                    <?php endif; ?>

                    <!-- DANH SÁCH KHÁCH (NẾU CÓ) -->
                    <?php if ($booking['booking_type'] === 'group' && !empty($booking['customer_list'])): ?>
                    <hr>
                    <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-address-card"></i> Danh Sách Khách Đoàn</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Họ Tên</th>
                                    <th>Điện Thoại</th>
                                    <th>Ghi Chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    try {
                                        $customers = json_decode($booking['customer_list'], true) ?: [];
                                        foreach ($customers as $idx => $cust): ?>
                                        <tr>
                                            <td><?= $idx + 1 ?></td>
                                            <td><?= htmlspecialchars($cust['name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($cust['phone'] ?? '') ?></td>
                                            <td><small><?= htmlspecialchars($cust['note'] ?? '') ?></small></td>
                                        </tr>
                                        <?php endforeach;
                                    } catch (Exception $e) {
                                        echo '<tr><td colspan="4" class="text-muted">Dữ liệu danh sách không hợp lệ</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SIDEBAR TRẠNG THÁI -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Trạng Thái Đơn Hàng</h6>
                </div>
                <div class="card-body text-center">
                    <?php 
                        $statusMap = [
                            'pending' => ['warning', 'Chờ Xác Nhận', 'Awaiting Approval'],
                            'confirmed' => ['info', 'Đã Xác Nhận', 'Approved'],
                            'paid' => ['success', 'Đã Thanh Toán', 'Payment Received'],
                            'cancelled' => ['danger', 'Đã Hủy', 'Cancelled'],
                            'completed' => ['secondary', 'Hoàn Thành', 'Completed']
                        ];
                        [$color, $label, $engLabel] = $statusMap[$booking['status']] ?? ['secondary', $booking['status'], ''];
                    ?>
                    <div class="mb-3">
                        <button class="btn btn-<?= $color ?> btn-lg mb-2 w-100 font-weight-bold" disabled style="font-size: 16px;">
                            <?= mb_strtoupper($label, 'UTF-8') ?>
                        </button>
                        <small class="text-muted d-block"><?= $engLabel ?></small>
                    </div>

                    <hr>

                    <!-- FORM CẬP NHẬT TRẠNG THÁI -->
                    <form action="index.php?action=bookings_update_status" method="POST" class="mt-3">
                        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                        <div class="form-group text-left">
                            <label class="font-weight-bold mb-2">Cập Nhật Trạng Thái:</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="pending" <?= $booking['status']=='pending'?'selected':'' ?>>Chờ xác nhận</option>
                                <option value="confirmed" <?= $booking['status']=='confirmed'?'selected':'' ?>>✓ Xác nhận giữ chỗ</option>
                                <option value="paid" <?= $booking['status']=='paid'?'selected':'' ?>>✓ Đã thanh toán</option>
                                <option value="completed" <?= $booking['status']=='completed'?'selected':'' ?>>✓ Hoàn thành tour</option>
                                <option value="cancelled" <?= $booking['status']=='cancelled'?'selected':'' ?>>✗ Hủy đơn</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Lưu Thay Đổi
                        </button>
                    </form>

                    <!-- THỐNG KÊ -->
                    <hr>
                    <div class="text-left">
                        <h6 class="font-weight-bold">Thống Kê</h6>
                        <p class="mb-1 text-muted"><small>
                            <strong>ID:</strong> #<?= $booking['id'] ?><br>
                            <strong>Tạo:</strong> <?= date('d/m/Y', strtotime($booking['created_at'] ?? $booking['booking_date'])) ?><br>
                            <?php if (!empty($booking['updated_at'])): ?>
                            <strong>Cập Nhật:</strong> <?= date('d/m/Y H:i', strtotime($booking['updated_at'])) ?><br>
                            <?php endif; ?>
                            <strong>Loại:</strong> <?= $booking['booking_type'] === 'group' ? 'ĐOÀN' : 'KHÁCH LẺ' ?>
                        </small></p>
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTON -->
            <div class="card shadow">
                <div class="card-body text-center">
                    <button class="btn btn-info btn-block mb-2" onclick="window.print()">
                        <i class="fas fa-print"></i> In Phiếu Đặt
                    </button>
                    <a href="index.php?action=bookings" class="btn btn-secondary btn-block">
                        <i class="fas fa-list"></i> Quay Lại Danh Sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>