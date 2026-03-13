<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users"></i> Quản Lý Khách Đoàn & Tour
        </h1>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-check-circle"></i> Thành công!</strong> <?= escape($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Lỗi!</strong> <?= escape($_SESSION['error']) ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Card: Lựa chọn Tour -->
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-map-marker-alt"></i> Chọn Tour Để Quản Lý
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="mb-0">
                <input type="hidden" name="action" value="tour_customers">
                <div class="row">
                    <div class="col-md-10">
                        <select name="tour_id" id="tourSelect" class="form-control form-control-lg" onchange="this.form.submit()" style="font-size: 15px;">
                            <option value="">-- Chọn một tour --</option>
                            <?php if (!empty($tours)): ?>
                                <?php foreach ($tours as $tour): ?>
                                    <option value="<?= escape($tour['id']) ?>" <?= ($tourId == ($tour['id'] ?? '')) ? 'selected' : '' ?>>
                                        <strong><?= escape($tour['name'] ?? '') ?></strong> (<?= escape($tour['category_name'] ?? 'N/A') ?>) - Thời lượng: <?= escape($tour['duration'] ?? 0) ?> ngày
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-search"></i> Tìm Kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($tourId && $selectedTour): ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-primary font-weight-bold text-uppercase mb-1">Tổng Khách</div>
                        <div class="h3 mb-0 text-gray-800"><?= $statistics['total_customers'] ?? 0 ?></div>
                        <small class="text-muted"><?= $statistics['total_people'] ?? 0 ?> người</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-success font-weight-bold text-uppercase mb-1">Đã Xác Nhận</div>
                        <div class="h3 mb-0 text-gray-800"><?= $statistics['confirmed'] ?? 0 ?></div>
                        <small class="text-muted">Booking</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-warning font-weight-bold text-uppercase mb-1">Chờ Xác Nhận</div>
                        <div class="h3 mb-0 text-gray-800"><?= $statistics['pending'] ?? 0 ?></div>
                        <small class="text-muted">Booking</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-info font-weight-bold text-uppercase mb-1">Doanh Thu</div>
                        <div class="h3 mb-0 text-gray-800"><?= number_format($statistics['total_revenue'] ?? 0, 0, ',', '.') ?> ₫</div>
                        <small class="text-muted">Tổng cộng</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin Tour -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow border-left-success">
                    <div class="card-header py-3 bg-success text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-info-circle"></i> Thông Tin Tour
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Tên Tour:</strong><br>
                                <span class="text-primary font-weight-bold"><?= escape($selectedTour['name']) ?></span>
                            </div>
                            <div class="col-md-2">
                                <strong>Loại Tour:</strong><br>
                                <?php 
                                    $tourTypes = ['domestic' => 'Trong Nước', 'international' => 'Quốc Tế', 'custom' => 'Theo Yêu Cầu'];
                                    $type = $selectedTour['tour_type'] ?? 'domestic';
                                    $typeLabels = ['domestic' => 'Trong Nước', 'international' => 'Quốc Tế', 'custom' => 'Theo Yêu Cầu'];
                                    $typeColors = ['domestic' => 'success', 'international' => 'info', 'custom' => 'warning'];
                                ?>
                                <span class="badge badge-<?= $typeColors[$type] ?? 'secondary' ?>">
                                    <?= $typeLabels[$type] ?? $type ?>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <strong>Thời Lượng:</strong><br>
                                <span class="badge badge-light"><?= escape($selectedTour['duration'] ?? 0) ?> ngày</span>
                            </div>
                            <div class="col-md-2">
                                <strong>Giá Tour:</strong><br>
                                <span class="text-danger font-weight-bold"><?= number_format($selectedTour['price'] ?? 0, 0, ',', '.') ?> ₫</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Trạng Thái:</strong><br>
                                <?php 
                                    $statusBadges = ['active' => 'success', 'inactive' => 'danger', 'completed' => 'secondary'];
                                    $status = $selectedTour['status'] ?? 'active';
                                ?>
                                <span class="badge badge-<?= $statusBadges[$status] ?? 'secondary' ?>">
                                    <?= ucfirst($status) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs: Khách Hàng, Khởi Hành, Hướng Dẫn, Dịch Vụ -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-dark text-white">
                <ul class="nav nav-tabs nav-dark card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-white" id="customers-tab" data-toggle="tab" href="#customers" role="tab">
                            <i class="fas fa-users"></i> Khách Hàng (<?= $statistics['total_customers'] ?? 0 ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" id="departures-tab" data-toggle="tab" href="#departures" role="tab">
                            <i class="fas fa-calendar"></i> Khởi Hành (<?= count($departures ?? []) ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" id="guides-tab" data-toggle="tab" href="#guides" role="tab">
                            <i class="fas fa-user-tie"></i> Hướng Dẫn (<?= count($guides ?? []) ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" id="services-tab" data-toggle="tab" href="#services" role="tab">
                            <i class="fas fa-concierge-bell"></i> Dịch Vụ (<?= count($services ?? []) ?>)
                        </a>
                    </li>
                    <li class="nav-item ml-auto">
                        <a class="nav-link text-white" id="actions-tab" data-toggle="tab" href="#actions" role="tab">
                            <i class="fas fa-cogs"></i> Hành Động
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">

                <!-- TAB 1: KHÁCH HÀNG -->
                <div class="tab-pane fade show active" id="customers" role="tabpanel">
                    <div class="card-body">
                        <?php if (!empty($customers)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-striped" id="customersTable">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th style="width: 40px;">STT</th>
                                            <th style="width: 70px;">Mã Booking</th>
                                            <th>Họ Tên</th>
                                            <th style="width: 100px;">Số ĐT</th>
                                            <th>Email</th>
                                            <th style="width: 60px;">Người</th>
                                            <th style="width: 90px;">Trạng Thái</th>
                                            <th style="width: 100px;">Check-in</th>
                                            <th style="width: 120px;">Giá Tiền</th>
                                            <th style="width: 150px;">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $stt = 1; ?>
                                        <?php foreach ($customers as $customer): ?>
                                            <tr>
                                                <td class="text-center font-weight-bold"><?= $stt++ ?></td>
                                                <td><strong>#<?= escape($customer['booking_id'] ?? '') ?></strong></td>
                                                <td><?= escape($customer['full_name'] ?? '') ?></td>
                                                <td>
                                                        <a href="tel:<?= escape($customer['phone'] ?? '') ?>">
                                                        <?= escape($customer['phone'] ?? '') ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?= escape($customer['email'] ?? '') ?>">
                                                        <?= escape($customer['email'] ?? '') ?>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-info"><?= escape($customer['number_of_people'] ?? 0) ?></span>
                                                </td>
                                                <td class="text-center">
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
                                                        $status = $customer['booking_status'] ?? 'pending';
                                                        $color = $statusColors[$status] ?? 'secondary';
                                                        $label = $statusLabels[$status] ?? $status;
                                                    ?>
                                                    <span class="badge badge-<?= $color ?>">
                                                        <?= $label ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                        $checkinStatus = $customer['checkin_status'] ?? 'not_checked_in';
                                                        if ($checkinStatus === 'checked_in'):
                                                    ?>
                                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Check-in</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary"><i class="fas fa-clock"></i> Chưa</span>
                                                    <?php endif; ?>
                                                </td>
                                                    <td class="text-right font-weight-bold text-danger">
                                                    <?= number_format($customer['total_price'] ?? 0, 0, ',', '.') ?> ₫
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="<?= url('index.php?action=bookings_show&id=' . (int)($customer['booking_id'] ?? 0)) ?>" 
                                                           class="btn btn-info" title="Xem chi tiết" target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-warning" 
                                                                onclick="editSpecialRequests(<?= json_encode($customer['booking_id'] ?? '') ?>, <?= json_encode($customer['special_requests'] ?? '') ?>)"
                                                                title="Yêu cầu đặc biệt">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                        <a href="<?= url('index.php?action=checkin_attendance&tour_id=' . (int)($tourId ?? 0)) ?>" 
                                                           class="btn btn-success" title="Check-in">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center py-4">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <p class="mb-2"><strong>Không có khách hàng nào cho tour này</strong></p>
                                <p class="mb-0 text-muted">Hãy thêm khách hàng bằng cách nhấp vào nút "Thêm Khách Hàng"</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TAB 2: KHỞI HÀNH -->
                <div class="tab-pane fade" id="departures" role="tabpanel">
                    <div class="card-body">
                        <?php if (!empty($departures)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Mã Khởi Hành</th>
                                            <th>Ngày Khởi Hành</th>
                                            <th>Địa Điểm Tập Trung</th>
                                            <th>Số Hướng Dẫn</th>
                                            <th>Ghi Chú</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($departures as $dep): ?>
                                            <tr>
                                                <td><strong><?= escape($dep['departure_code'] ?? $dep['id'] ?? 'N/A') ?></strong></td>
                                                <td><?= isset($dep['departure_date']) ? date('d/m/Y', strtotime($dep['departure_date'])) : 'N/A' ?></td>
                                                <td><?= escape($dep['meeting_point'] ?? $dep['location'] ?? 'N/A') ?></td>
                                                <td>
                                                    <span class="badge badge-info">-</span>
                                                </td>
                                                <td><?= escape(substr($dep['notes'] ?? $dep['description'] ?? '', 0, 30)) ?></td>
                                                <td>
                                                    <a href="<?= url('index.php?action=departures&id=' . (int)($dep['id'] ?? 0)) ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> Chi Tiết
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center py-4">
                                <i class="fas fa-calendar fa-2x mb-3"></i>
                                <p class="mb-2"><strong>Chưa có khởi hành nào</strong></p>
                                <p class="mb-0">
                                    <a href="<?= url('index.php?action=departures_create&tour_id=' . $tourId) ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Tạo Khởi Hành
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TAB 3: HƯỚNG DẪN VIÊN -->
                <div class="tab-pane fade" id="guides" role="tabpanel">
                    <div class="card-body">
                        <?php if (!empty($guides)): ?>
                            <div class="row">
                                <?php foreach ($guides as $guide): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-left-primary">
                                            <div class="card-body">
                                                <h6 class="font-weight-bold text-primary">
                                                    <i class="fas fa-user-tie"></i> <?= escape($guide['full_name']) ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone"></i> <?= escape($guide['phone'] ?? 'N/A') ?><br>
                                                    <i class="fas fa-calendar-check"></i> Khởi hành: <?= isset($guide['departure_date']) ? date('d/m/Y', strtotime($guide['departure_date'])) : 'N/A' ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center py-4">
                                <i class="fas fa-user-tie fa-2x mb-3"></i>
                                <p class="mb-2"><strong>Chưa có hướng dẫn viên nào được gán</strong></p>
                                <p class="mb-0">
                                    <a href="<?= url('index.php?action=assignments') ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Phân Bổ Hướng Dẫn
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TAB 4: DỊCH VỤ -->
                <div class="tab-pane fade" id="services" role="tabpanel">
                    <div class="card-body">
                        <?php if (!empty($services)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tên Dịch Vụ</th>
                                            <th>Nhà Cung Cấp</th>
                                            <th>Giá Tiền</th>
                                            <th>Ghi Chú</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($services as $svc): ?>
                                            <tr>
                                                <td><?= escape($svc['name'] ?? $svc['service_name'] ?? 'N/A') ?></td>
                                                <td><?= escape($svc['vendor'] ?? $svc['provider'] ?? 'N/A') ?></td>
                                                <td class="text-right font-weight-bold">
                                                    <?= number_format($svc['amount'] ?? $svc['cost'] ?? 0, 0, ',', '.') ?> ₫
                                                </td>
                                                <td><?= escape(substr($svc['notes'] ?? '', 0, 30)) ?></td>
                                                <td>
                                                    <a href="<?= url('index.php?action=services&id=' . $svc['id']) ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center py-4">
                                <i class="fas fa-concierge-bell fa-2x mb-3"></i>
                                <p class="mb-2"><strong>Chưa có dịch vụ nào</strong></p>
                                <p class="mb-0">
                                    <a href="<?= url('index.php?action=services') ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Thêm Dịch Vụ
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TAB 5: HÀNH ĐỘNG -->
                <div class="tab-pane fade" id="actions" role="tabpanel">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary btn-lg btn-block mb-3" data-toggle="modal" data-target="#addCustomerModal">
                                    <i class="fas fa-user-plus"></i> Thêm Khách Hàng
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?= url('index.php?action=departures_create&tour_id=' . $tourId) ?>" class="btn btn-success btn-lg btn-block mb-3">
                                    <i class="fas fa-calendar-plus"></i> Tạo Khởi Hành Mới
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?= url('index.php?action=assignments') ?>" class="btn btn-info btn-lg btn-block mb-3">
                                    <i class="fas fa-user-tie"></i> Phân Bổ Hướng Dẫn
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?= url('index.php?action=services') ?>" class="btn btn-warning btn-lg btn-block mb-3">
                                    <i class="fas fa-concierge-bell"></i> Quản Lý Dịch Vụ
                                </a>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?= url('index.php?action=checkin_attendance&tour_id=' . $tourId) ?>" class="btn btn-success btn-block mb-3">
                                    <i class="fas fa-check-circle"></i> Check-in & Điểm Danh
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?= url('index.php?action=tour_diary&tour_id=' . $tourId) ?>" class="btn btn-secondary btn-block mb-3">
                                    <i class="fas fa-book"></i> Nhật Ký Tour
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="?action=bookings_statistics&tour_id=<?= $tourId ?>" class="btn btn-info btn-block mb-3">
                                    <i class="fas fa-chart-bar"></i> Thống Kê & Báo Cáo
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-secondary btn-block mb-3">
                                    <i class="fas fa-file-excel"></i> Xuất Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-info alert-lg text-center py-5" role="alert">
            <i class="fas fa-arrow-up fa-3x mb-3"></i>
            <h5 class="mb-3"><strong>Vui lòng chọn một tour ở trên</strong></h5>
            <p class="mb-0 text-muted">Để xem danh sách khách hàng, khởi hành, hướng dẫn và dịch vụ liên quan</p>
        </div>
    <?php endif; ?>

</div>

<!-- Modal: Thêm Khách Hàng -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Thêm Khách Hàng Vào Tour</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="?action=tour_customers_add">
                <div class="modal-body">
                    <input type="hidden" name="tour_id" value="<?= escape((int)($tourId ?? 0)) ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Họ và Tên <span class="text-danger">*</span></strong></label>
                                <input type="text" name="full_name" class="form-control" required placeholder="Nhập họ tên khách">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Số Điện Thoại <span class="text-danger">*</span></strong></label>
                                <input type="tel" name="phone" class="form-control" required placeholder="Nhập SĐT">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Email <span class="text-danger">*</span></strong></label>
                        <input type="email" name="email" class="form-control" required placeholder="khachhang@example.com">
                        <small class="form-text text-muted">Nếu email chưa tồn tại, hệ thống sẽ tự tạo tài khoản với mật khẩu: <strong>123456</strong></small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Số Người <span class="text-danger">*</span></strong></label>
                                <input type="number" name="number_of_people" class="form-control" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Đơn Giá (VNĐ)</strong></label>
                                <input type="number" name="custom_price" class="form-control" placeholder="Để trống = Giá gốc">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Yêu Cầu Đặc Biệt / Ghi Chú</strong></label>
                        <textarea class="form-control" name="special_requests" rows="3" placeholder="VD: Dị ứng, nhu cầu phòng,..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm Khách Hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Yêu Cầu Đặc Biệt -->
<div class="modal fade" id="specialRequestsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-pen"></i> Yêu Cầu Đặc Biệt</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <input type="hidden" name="booking_id" id="bookingId" value="">
                    <div class="form-group">
                        <label><strong>Ghi Chú Yêu Cầu</strong></label>
                        <textarea class="form-control" id="specialRequests" name="special_requests" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="updateSpecialRequests()">
                        <i class="fas fa-save"></i> Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editSpecialRequests(bookingId, specialRequests) {
    document.getElementById('bookingId').value = bookingId;
    document.getElementById('specialRequests').value = specialRequests;
    $('#specialRequestsModal').modal('show');
}

function updateSpecialRequests() {
    const bookingId = document.getElementById('bookingId').value;
    const specialRequests = document.getElementById('specialRequests').value;

    fetch('<?= url("index.php?action=tour_customers_update_special_requests") ?>', {
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
            alert('Lỗi: ' + (data.message || 'Không biết lỗi'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

// DataTables initialization
$(document).ready(function() {
    if ($('#customersTable').length) {
        $('#customersTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Vietnamese.json'
            },
            pageLength: 10,
            order: [[0, 'asc']]
        });
    }
});
</script>
