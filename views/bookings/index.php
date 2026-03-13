<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Danh Sách Đặt Tour</h1>
        <a href="index.php?action=bookings_create" class="btn btn-success shadow-sm">
            <i class="fas fa-plus-circle"></i> Tạo Booking Mới
        </a>
    </div>

    <!-- FILTER -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="index.php?action=bookings" method="GET" class="row align-items-end">
                <input type="hidden" name="action" value="bookings">
                
                <div class="col-md-3">
                    <label><strong>Trạng Thái</strong></label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">-- Tất Cả --</option>
                        <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Chờ Xác Nhận</option>
                        <option value="confirmed" <?= ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Đã Xác Nhận</option>
                        <option value="paid" <?= ($_GET['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Đã Thanh Toán</option>
                        <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Hoàn Thành</option>
                        <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Đã Hủy</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block btn-sm">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="index.php?action=bookings" class="btn btn-secondary btn-block btn-sm">
                        <i class="fas fa-redo"></i> Đặt Lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- DANH SÁCH -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Bán Tour & Đặt Chỗ (<?= count($bookings) ?> đơn)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="12%">Khách Hàng</th>
                            <th width="18%">Tour</th>
                            <th width="10%">Loại Đặt</th>
                            <th width="8%">Số Người</th>
                            <th width="12%">Tổng Tiền</th>
                            <th width="10%">Ngày Đặt</th>
                            <th width="12%">Trạng Thái</th>
                            <th width="13%">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $b): ?>
                            <tr>
                                <td class="font-weight-bold text-primary">#<?= $b['id'] ?></td>
                                
                                <td>
                                    <strong><?= htmlspecialchars($b['user_name']) ?></strong>
                                </td>
                                
                                <td>
                                    <small><?= htmlspecialchars(substr($b['tour_name'], 0, 30)) ?></small>
                                </td>
                                
                                <td class="text-center">
                                    <?php if ($b['booking_type'] === 'group'): ?>
                                        <span class="badge badge-warning" title="Đoàn GIT">
                                            <i class="fas fa-users"></i> ĐOÀN
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-info" title="Khách lẻ FIT">
                                            <i class="fas fa-user"></i> LẺ
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-center"><strong><?= $b['number_of_people'] ?></strong></td>
                                
                                <td class="text-right text-success font-weight-bold">
                                    <?= number_format($b['total_price']) ?>đ
                                </td>
                                
                                <td class="text-muted small">
                                    <?= date('d/m/Y', strtotime($b['created_at'] ?? $b['booking_date'])) ?>
                                </td>
                                
                                <td class="text-center">
                                    <?php 
                                        $statusMap = [
                                            'pending' => ['warning', 'Chờ XN'],
                                            'confirmed' => ['info', 'Đã XN'],
                                            'paid' => ['success', 'Đã TT'],
                                            'completed' => ['secondary', 'Hoàn TT'],
                                            'cancelled' => ['danger', 'Đã Hủy']
                                        ];
                                        [$badgeClass, $label] = $statusMap[$b['status']] ?? ['secondary', $b['status']];
                                    ?>
                                    <span class="badge badge-<?= $badgeClass ?>" style="font-size: 11px; padding: 5px 8px;">
                                        <?= $label ?>
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    <a href="index.php?action=bookings_show&id=<?= $b['id'] ?>" 
                                       class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox" style="font-size: 24px;"></i>
                                    <p class="mt-2 mb-0">Không có đơn đặt tour nào</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>