<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Danh Sách Khách Hàng</h1>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Thành công!</strong> <?= escape($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Lỗi!</strong> <?= escape($_SESSION['error']) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Card: Lựa chọn Tour -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-map-marker-alt"></i> Chọn Tour
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="form-inline">
    <input type="hidden" name="action" value="tour_customers"> 
    
    <div class="form-group mr-3 mb-2" style="flex: 1; min-width: 300px;">
                <div class="form-group mr-3 mb-2" style="flex: 1; min-width: 300px;">
                    <select name="tour_id" id="tourSelect" class="form-control w-100" onchange="this.form.submit()">
                        <option value="">-- Chọn một tour --</option>
                        <?php if (!empty($tours)): ?>
                            <?php foreach ($tours as $tour): ?>
                                <option value="<?= $tour['id'] ?>" <?= ($tourId == $tour['id']) ? 'selected' : '' ?>>
                                    <?= escape($tour['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
    

    <?php if ($tourId && $selectedTour): ?>
        <!-- Card: Thông tin Tour -->
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle"></i> Thông Tin Tour
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Tên Tour:</strong><br>
                        <?= escape($selectedTour['name']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Thời lượng:</strong><br>
                        <?= escape($selectedTour['duration'] ?? '') ?> ngày
                    </div>
                    <div class="col-md-3">
                        <strong>Tạo lúc:</strong><br>
                        <?= isset($selectedTour['created_at']) ? date('d/m/Y', strtotime($selectedTour['created_at'])) : '' ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Số Khách Đã Đặt:</strong><br>
                        <span class="badge badge-info badge-lg"><?= count($customers) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Danh sách Khách -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users"></i> Danh Sách Khách Hàng
                </h6>
                <div>
                    <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#addCustomerModal">
            <i class="fas fa-user-plus"></i> Thêm Khách Hàng
        </button>
                    <a href="?action=tour-customers/export&tour_id=<?= $tourId ?>" class="btn btn-success btn-sm mr-2">
                        <i class="fas fa-file-excel"></i> Xuất Excel
                    </a>
                </div>
                
                
            </div>
            <div class="card-body">
                <?php if (!empty($customers)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="dataTable">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">STT</th>
                                    <th>#ID</th>
                                    <th>Họ Tên</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Email</th>
                                    <th style="width: 100px;">Số Người</th>
                                    <th>Trạng Thái</th>
                                    <th style="width: 120px;">Giá Tiền</th>
                                    <th style="width: 200px;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $stt = 1; ?>
                                <?php foreach ($customers as $customer): ?>
                                    <tr>
                                        <td><?= $stt++ ?></td>
                                        <td><strong>#<?= escape($customer['booking_id']) ?></strong></td>
                                        <td><?= escape($customer['full_name'] ?? '') ?></td>
                                        <td><?= escape($customer['phone']) ?></td>
                                        <td>
                                            <a href="mailto:<?= escape($customer['email']) ?>">
                                                <?= escape($customer['email']) ?>
                                            </a>
                                        </td>
                                        <td><?= $customer['number_of_people'] ?></td>
                                        <td>
                                            <?php 
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'cancelled' => 'danger',
                                                'completed' => 'info'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Chờ Xác Nhận',
                                                'confirmed' => 'Đã Xác Nhận',
                                                'cancelled' => 'Đã Hủy',
                                                'completed' => 'Hoàn Thành'
                                            ];
                                            $status = $customer['booking_status'];
                                            $color = $statusColors[$status] ?? 'secondary';
                                            $label = $statusLabels[$status] ?? $status;
                                            ?>
                                            <span class="badge badge-<?= $color ?>">
                                                <?= $label ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($customer['total_price'], 0, ',', '.') ?> ₫</td>
                                        <td>
                                            <a class="btn btn-sm btn-info" href="<?= url('bookings_show?id=' . $customer['booking_id']) ?>" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> Không có khách hàng nào cho tour này
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-arrow-up"></i> Vui lòng chọn một tour ở trên để xem danh sách khách hàng
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Chi Tiết Khách Hàng -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi Tiết Khách Hàng</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Yêu Cầu Đặc Biệt -->
<div class="modal fade" id="specialRequestsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập Nhật Yêu Cầu Đặc Biệt</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="booking_id" id="bookingId" value="">
                    <div class="form-group">
                        <label for="specialRequests">Yêu Cầu Đặc Biệt</label>
                        <textarea class="form-control" id="specialRequests" name="special_requests" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="updateSpecialRequests()">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Khách Hàng Vào Tour</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="?action=tour-customers/add">
                <div class="modal-body">
                    <input type="hidden" name="tour_id" value="<?= $tourId ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Họ và Tên <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" required placeholder="Nhập họ tên khách">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số Điện Thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" required placeholder="Nhập SĐT">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email (Sẽ dùng để kiểm tra hoặc tạo tài khoản mới) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required placeholder="khachhang@example.com">
                        <small class="form-text text-muted">Nếu email chưa tồn tại, hệ thống sẽ tự tạo tài khoản với mật khẩu mặc định là <strong>123456</strong>.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số Người <span class="text-danger">*</span></label>
                                <input type="number" name="number_of_people" class="form-control" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Đơn Giá (VNĐ)</label>
                                <input type="number" name="custom_price" class="form-control" placeholder="Để trống = Giá gốc của Tour">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Yêu Cầu Đặc Biệt / Ghi Chú</label>
                        <textarea class="form-control" name="special_requests" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm Vào Tour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setBookingId(bookingId, specialRequests) {
    document.getElementById('bookingId').value = bookingId;
    document.getElementById('specialRequests').value = specialRequests;
}

function updateSpecialRequests() {
    const bookingId = document.getElementById('bookingId').value;
    const specialRequests = document.getElementById('specialRequests').value;

    fetch('<?= url("?action=tour-customers/update-special-requests") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'booking_id=' + bookingId + '&special_requests=' + encodeURIComponent(specialRequests)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cập nhật thành công');
            $('#specialRequestsModal').modal('hide');
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

function loadCustomerDetail(bookingId) {
    fetch('<?= url("?action=tour-customers/show&id=") ?>' + bookingId)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const booking = data.data.booking;
            const user = data.data.user;
            
            const html = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Mã Booking:</strong> ${booking.booking_code}<br>
                        <strong>Họ Tên:</strong> ${user.first_name} ${user.last_name}<br>
                        <strong>Email:</strong> <a href="mailto:${user.email}">${user.email}</a><br>
                        <strong>Số Điện Thoại:</strong> ${user.phone}<br>
                    </div>
                    <div class="col-md-6">
                        <strong>Số Người:</strong> ${booking.number_of_people}<br>
                        <strong>Tổng Giá:</strong> ${booking.total_price.toLocaleString('vi-VN')} ₫<br>
                        <strong>Trạng Thái:</strong> ${booking.status}<br>
                        <strong>Ngày Đặt:</strong> ${new Date(booking.created_at).toLocaleDateString('vi-VN')}<br>
                    </div>
                </div>
                <hr>
                <strong>Yêu Cầu Đặc Biệt:</strong>
                <p>${booking.special_requests || 'Không có'}</p>
            `;
            
            document.getElementById('detailContent').innerHTML = html;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('detailContent').innerHTML = '<div class="alert alert-danger">Có lỗi xảy ra</div>';
    });
}

function cancelBooking(bookingId, tourId) {
    if (confirm('Bạn chắc chắn muốn hủy booking này?')) {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = 'index.php'; // Đặt action về file index gốc
        
        // 1. Thêm input cho action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'tour-customers/cancel'; // Giá trị action đúng
        form.appendChild(actionInput);

        // 2. Các input cũ (booking_id, tour_id) giữ nguyên
        const bookingInput = document.createElement('input');
        bookingInput.type = 'hidden';
        bookingInput.name = 'booking_id';
        bookingInput.value = bookingId;
        form.appendChild(bookingInput);
        
        const tourInput = document.createElement('input');
        tourInput.type = 'hidden';
        tourInput.name = 'tour_id';
        tourInput.value = tourId;
        form.appendChild(tourInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
