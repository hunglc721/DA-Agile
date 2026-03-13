<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Check-in & Điểm Danh Khách</h1>
    <p class="mb-4">Hệ thống điểm danh theo thời gian thực.</p>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng số khách</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total'] ?? 0 ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đã check-in</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['checked_in'] ?? 0 ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Chờ check-in</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['pending'] ?? 0 ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Vắng mặt</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['absent'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lựa chọn Tour & Ngày Điểm Danh</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="index.php" id="filterForm">
                <input type="hidden" name="action" value="checkin"> <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tourSelect">Chọn Tour đang chạy</label>
                        <select class="form-control" name="tour_id" id="tourSelect" onchange="document.getElementById('filterForm').submit()">
                       <option value="">-- Chọn Tour --</option>
    
                      <?php if (!empty($tours)): ?>
                      <?php foreach ($tours as $tour): ?>
                    <option value="<?= $tour['id'] ?>" 
                    <?= (isset($selectedTourId) && $selectedTourId == $tour['id']) ? 'selected' : '' ?>>
                     <?= htmlspecialchars($tour['name']) ?> (<?= htmlspecialchars($tour['tour_code'] ?? 'Mã: '.$tour['id']) ?>)
                      </option>
                   <?php endforeach; ?>
                   <?php else: ?>
                    <option disabled>Không tìm thấy tour nào trong hệ thống</option>
                   <?php endif; ?>
    
                    </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Ngày điểm danh</label>
                        <input type="date" class="form-control" name="date" value="<?= $selectedDate ?? date('Y-m-d') ?>">
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-info w-100">Xem</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách khách hàng</h6>
            <div>
                <button class="btn btn-primary btn-sm" onclick="batchAction('checked_in')" id="batchCheckinBtn">
                    <i class="fas fa-check-circle"></i> Check-in Chọn
                </button>
                <button class="btn btn-danger btn-sm" onclick="batchAction('absent')" id="batchAbsentBtn">
                    <i class="fas fa-times-circle"></i> Vắng Chọn
                </button>
                <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#qrScannerModal">
                    <i class="fas fa-qrcode"></i> Quét QR
                </button>
            </div>
        </div>

        <!-- Status Filter Buttons -->
        <div class="card-body pb-0">
            <div class="btn-group btn-block mb-3" role="group">
                <a href="?action=checkin_attendance&tour_id=<?= (int)($selectedTourId ?? 0) ?>&date=<?= escape($selectedDate ?? date('Y-m-d')) ?>&status=all" 
                   class="btn btn-outline-secondary <?= ($filterStatus === 'all') ? 'active' : '' ?>">
                    <i class="fas fa-th-list"></i> Tất cả (<?= $stats['total'] ?>)
                </a>
                <a href="?action=checkin_attendance&tour_id=<?= (int)($selectedTourId ?? 0) ?>&date=<?= escape($selectedDate ?? date('Y-m-d')) ?>&status=pending" 
                   class="btn btn-outline-warning <?= ($filterStatus === 'pending') ? 'active' : '' ?>">
                    <i class="fas fa-clock"></i> Chờ (<?= $stats['pending'] ?>)
                </a>
                <a href="?action=checkin_attendance&tour_id=<?= (int)($selectedTourId ?? 0) ?>&date=<?= escape($selectedDate ?? date('Y-m-d')) ?>&status=checked_in" 
                   class="btn btn-outline-success <?= ($filterStatus === 'checked_in') ? 'active' : '' ?>">
                    <i class="fas fa-check"></i> Đã điểm danh (<?= $stats['checked_in'] ?>)
                </a>
                <a href="?action=checkin_attendance&tour_id=<?= (int)($selectedTourId ?? 0) ?>&date=<?= escape($selectedDate ?? date('Y-m-d')) ?>&status=absent" 
                   class="btn btn-outline-danger <?= ($filterStatus === 'absent') ? 'active' : '' ?>">
                    <i class="fas fa-ban"></i> Vắng mặt (<?= $stats['absent'] ?>)
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="checkinTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%"><input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)"></th>
                            <th>Mã Booking</th>
                            <th>Họ tên khách</th>
                            <th>SĐT</th>
                            <th>Số người</th>
                            <th>Thời gian Check-in</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr><td colspan="8" class="text-center">Vui lòng chọn tour để hiển thị danh sách khách hàng.</td></tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $b): ?>
                                <tr data-booking-id="<?= (int)($b['id'] ?? 0) ?>">
                                    <td class="text-center">
                                        <input type="checkbox" class="check-item" 
                                               data-id="<?= (int)($b['id'] ?? 0) ?>"
                                               style="transform: scale(1.5);">
                                    </td>
                                    <td><strong><?= escape($b['booking_code'] ?? ('BK'.(int)($b['id'] ?? 0))) ?></strong></td>
                                    <td><?= escape($b['full_name'] ?? '') ?></td>
                                    <td><?= escape($b['phone'] ?? '') ?></td>
                                    <td class="text-center"><?= (int)($b['number_of_people'] ?? 0) ?></td>
                                    
                                    <td class="checkin-time font-weight-bold text-info">
                                        <?= ($b['checkin_status'] === 'checked_in' && !empty($b['checkin_time'])) ? date('H:i d/m', strtotime($b['checkin_time'])) : '' ?>
                                    </td>
                                    
                                    <td class="status-badge">
                                        <?php $status = $b['checkin_status'] ?? 'pending'; ?>
                                        <?php if ($status === 'checked_in'): ?>
                                            <span class="badge badge-success"><i class="fas fa-check"></i> Đã điểm danh</span>
                                        <?php elseif ($status === 'absent'): ?>
                                            <span class="badge badge-danger"><i class="fas fa-ban"></i> Vắng mặt</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Chờ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-success" onclick="updateCheckin(<?= (int)($b['id'] ?? 0) ?>, 'checked_in')" title="Check-in">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="updateCheckin(<?= (int)($b['id'] ?? 0) ?>, 'absent')" title="Vắng mặt">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quét mã QR</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p>Tính năng đang phát triển...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Update single check-in
function updateCheckin(bookingId, status) {
    const row = document.querySelector(`tr[data-booking-id="${bookingId}"]`);
    if (!row) return;

    fetch('index.php?action=checkin_update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'booking_id=' + bookingId + '&status=' + status
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update badge
            const badge = row.querySelector('.status-badge');
            const timeCell = row.querySelector('.checkin-time');
            
            if (status === 'checked_in') {
                badge.innerHTML = '<span class="badge badge-success"><i class="fas fa-check"></i> Đã điểm danh</span>';
                timeCell.innerText = data.time;
            } else if (status === 'absent') {
                badge.innerHTML = '<span class="badge badge-danger"><i class="fas fa-ban"></i> Vắng mặt</span>';
                timeCell.innerText = '';
            } else {
                badge.innerHTML = '<span class="badge badge-warning"><i class="fas fa-clock"></i> Chờ</span>';
                timeCell.innerText = '';
            }
            
            // Update checkbox state
            const checkbox = row.querySelector('.check-item');
            if (checkbox) checkbox.checked = (status === 'checked_in');
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi kết nối server');
    });
}

// Toggle select all checkboxes
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('table .check-item');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

// Get selected booking IDs
function getSelectedBookingIds() {
    const checkboxes = document.querySelectorAll('table .check-item:checked');
    return Array.from(checkboxes).map(cb => cb.dataset.id);
}

// Batch action for selected customers
function batchAction(status) {
    const ids = getSelectedBookingIds();
    
    if (ids.length === 0) {
        alert('Vui lòng chọn ít nhất một khách hàng');
        return;
    }

    const confirmMsg = status === 'checked_in' ? 
        `Check-in ${ids.length} khách hàng?` : 
        `Đánh dấu ${ids.length} khách hàng vắng mặt?`;

    if (!confirm(confirmMsg)) return;

    fetch('index.php?action=checkin_batch', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'booking_ids=' + JSON.stringify(ids) + '&status=' + status
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Reload page to refresh
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Lỗi kết nối server');
    });
}

// DataTables initialization
$(document).ready(function() {
    if ($('#checkinTable').length) {
        $('#checkinTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Vietnamese.json'
            },
            pageLength: 20,
            order: [[1, 'asc']]
        });
    }
});
</script>