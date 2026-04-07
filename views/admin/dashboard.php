<!-- Dashboard Admin - Trang chủ quản lý tour -->

<!-- Dashboard Main Content -->
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <p class="text-muted small mt-1">Chào mừng bạn quay lại, Admin!</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-light text-dark">Hôm nay: <?php echo date('d/m/Y'); ?></span>
            <button class="btn btn-sm btn-outline-primary" onclick="refreshDashboard()" title="Làm mới dữ liệu">
                <i class="fas fa-sync-alt"></i> Làm mới
            </button>
            <button class="btn btn-sm btn-outline-success" onclick="exportReport()" title="Xuất báo cáo">
                <i class="fas fa-download"></i> Xuất
            </button>
        </div>
    </div>

    <!-- ============================================
         1. KEY METRICS - Hàng thẻ chỉ số quan trọng
         ============================================ -->
    <div class="row mb-4">
        <!-- Card 1: Doanh thu tháng này -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-primary text-uppercase mb-1 font-weight-bold text-xs">
                        <i class="fas fa-money-bill-wave"></i> Doanh thu tháng này
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">45,500,000₫</div>
                    <div class="small text-success mt-2">
                        <i class="fas fa-arrow-up"></i> +12.5% so với tháng trước
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Booking mới chờ xử lý -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-warning text-uppercase mb-1 font-weight-bold text-xs">
                        <i class="fas fa-ticket-alt"></i> Booking chờ xử lý
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">12</div>
                    <div class="small text-muted mt-2">
                        <i class="fas fa-clock"></i> Cần xử lý ngay
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Tour đang khởi hành -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-info text-uppercase mb-1 font-weight-bold text-xs">
                        <i class="fas fa-bus"></i> Tour đang hoạt động
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">8</div>
                    <div class="small text-info mt-2">
                        <i class="fas fa-play-circle"></i> Đang khởi hành
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Khách hàng mới -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="text-success text-uppercase mb-1 font-weight-bold text-xs">
                        <i class="fas fa-user-circle"></i> Khách hàng mới
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">24</div>
                    <div class="small text-success mt-2">
                        <i class="fas fa-arrow-up"></i> Tuần này
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
         2. CHARTS - Biểu đồ doanh thu và booking
         ============================================ -->
    <div class="row mb-4">
        <!-- Chart 1: Doanh thu theo tháng (8 cột) -->
        <div class="col-xl-8 mb-4">
            <div class="card shadow border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0">
                        <i class="fas fa-chart-line"></i> Doanh thu theo tháng
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="dropdownChartFilter" data-bs-toggle="dropdown" aria-expanded="false">
                            Năm 2025
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownChartFilter">
                            <li><a class="dropdown-item" href="#">2023</a></li>
                            <li><a class="dropdown-item" href="#">2024</a></li>
                            <li><a class="dropdown-item" href="#">2025</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 2: Tỷ lệ trạng thái Booking (4 cột) -->
        <div class="col-xl-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header">
                    <h6 class="m-0">
                        <i class="fas fa-chart-pie"></i> Trạng thái Booking
                    </h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                    <div class="chart-container" style="width: 100%; height: 100%;">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-light pt-3">
                    <div class="row text-center small">
                        <div class="col-4">
                            <span class="badge bg-success">Paid: 45%</span>
                        </div>
                        <div class="col-4">
                            <span class="badge bg-warning">Pending: 35%</span>
                        </div>
                        <div class="col-4">
                            <span class="badge bg-danger">Cancel: 20%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
         3. RECENT BOOKINGS - Bảng booking gần nhất
         ============================================ -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0">
                        <i class="fas fa-table"></i> <?php echo count($bookings ?? []); ?> Booking gần nhất
                    </h6>
                    <a href="index.php?action=bookings_index" class="btn btn-sm btn-light">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#ID</th>
                                    <th>Tên khách hàng</th>
                                    <th>Tên Tour</th>
                                    <th class="text-center">Số người</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($bookings)) {
                                    foreach ($bookings as $booking) {
                                        $statusBg = 'secondary';
                                        $statusText = 'Chưa xác định';

                                        if ($booking['status'] === 'pending') {
                                            $statusBg = 'warning';
                                            $statusText = 'Chờ xử lý';
                                        } elseif ($booking['status'] === 'confirmed') {
                                            $statusBg = 'info';
                                            $statusText = 'Xác nhận';
                                        } elseif ($booking['status'] === 'paid') {
                                            $statusBg = 'success';
                                            $statusText = 'Đã thanh toán';
                                        } elseif ($booking['status'] === 'completed') {
                                            $statusBg = 'success';
                                            $statusText = 'Hoàn tất';
                                        } elseif ($booking['status'] === 'cancelled') {
                                            $statusBg = 'danger';
                                            $statusText = 'Đã hủy';
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-center"><strong>#BK<?php echo str_pad($booking['id'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td><?php echo htmlspecialchars($booking['customer_name'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($booking['tour_name'] ?? 'N/A'); ?></td>
                                            <td class="text-center"><?php echo $booking['number_of_people'] ?? '1'; ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-<?php echo $statusBg; ?>"><?php echo $statusText; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <a href="index.php?action=bookings_show&id=<?php echo $booking['id']; ?>" class="btn btn-xs btn-outline-primary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">Không có booking nào</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
         4. ADDITIONAL FEATURES - Tour sắp diễn ra & Activity
         ============================================ -->
    <div class="row mb-4">
        <!-- Upcoming Tours (6 cột) -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt"></i> Tour sắp diễn ra
                    </h6>
                    <span class="badge bg-info"><?php echo count($connectedTours ?? []); ?> tour</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php
                        if (!empty($connectedTours)) {
                            foreach ($connectedTours as $idx => $tour) {
                                $icons = ['map-marker-alt', 'mountain', 'wave-square', 'chair', 'camera'];
                                $colors = ['danger', 'warning', 'info', 'primary', 'success'];
                                $icon = $icons[$idx % count($icons)];
                                $color = $colors[$idx % count($colors)];
                                $departureDate = new DateTime($tour['departure_date']);
                                $formattedDate = $departureDate->format('d/m/Y');
                        ?>
                        <div class="list-group-item px-4 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 font-weight-bold text-gray-800">
                                        <i class="fas fa-<?php echo $icon; ?> text-<?php echo $color; ?>"></i>
                                        <?php echo htmlspecialchars($tour['tour_name'] ?? 'N/A'); ?>
                                    </h6>
                                    <p class="small text-muted mb-1">
                                        <i class="fas fa-users"></i> <?php echo $tour['booking_count'] ?? '0'; ?> booking |
                                        <i class="fas fa-bus"></i> <?php echo ($tour['booking_count'] > 0 ? ceil($tour['booking_count'] / 10) : '0'); ?> xe
                                    </p>
                                    <p class="small mb-0">
                                        <span class="badge bg-success">Đang khởi hành</span>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="d-block text-muted mb-2">Khởi hành</small>
                                    <strong class="d-block text-<?php echo $color; ?>"><?php echo $formattedDate; ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                        ?>
                        <div class="list-group-item px-4 py-3">
                            <p class="text-muted text-center mb-0">Chưa có tour khởi hành trong tương lai</p>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <a href="index.php?action=departures_index" class="small text-primary font-weight-bold">
                        Xem tất cả lịch biểu <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Activity Feed (6 cột) -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock"></i> Hoạt động gần đây
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="activity-feed">
                        <!-- Activity 1 -->
                        <div class="activity-item px-4 py-3 border-bottom">
                            <div class="d-flex">
                                <div class="activity-icon me-3">
                                    <i class="fas fa-user-plus text-success"></i>
                                </div>
                                <div class="activity-content flex-grow-1">
                                    <h6 class="mb-0 small font-weight-bold">Khách hàng mới đăng ký</h6>
                                    <p class="mb-1 small text-muted">Lê Văn Nam vừa tạo tài khoản</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> 5 phút trước
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Activity 2 -->
                        <div class="activity-item px-4 py-3 border-bottom">
                            <div class="d-flex">
                                <div class="activity-icon me-3">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                                <div class="activity-content flex-grow-1">
                                    <h6 class="mb-0 small font-weight-bold">Booking được xác nhận</h6>
                                    <p class="mb-1 small text-muted">Booking #BK001 đã được thanh toán</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> 12 phút trước
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Activity 3 -->
                        <div class="activity-item px-4 py-3 border-bottom">
                            <div class="d-flex">
                                <div class="activity-icon me-3">
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                                <div class="activity-content flex-grow-1">
                                    <h6 class="mb-0 small font-weight-bold">Đánh giá mới</h6>
                                    <p class="mb-1 small text-muted">Trần Anh Tuấn đánh giá 5 sao cho tour Hạ Long</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> 1 giờ trước
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Activity 4 -->
                        <div class="activity-item px-4 py-3 border-bottom">
                            <div class="d-flex">
                                <div class="activity-icon me-3">
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                </div>
                                <div class="activity-content flex-grow-1">
                                    <h6 class="mb-0 small font-weight-bold">Booking bị hủy</h6>
                                    <p class="mb-1 small text-muted">Booking #BK004 đã bị hủy bởi khách hàng</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> 2 giờ trước
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Activity 5 -->
                        <div class="activity-item px-4 py-3">
                            <div class="d-flex">
                                <div class="activity-icon me-3">
                                    <i class="fas fa-edit text-info"></i>
                                </div>
                                <div class="activity-content flex-grow-1">
                                    <h6 class="mb-0 small font-weight-bold">Cập nhật tour</h6>
                                    <p class="mb-1 small text-muted">Tour Phong Nha được cập nhật giá mới</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> 3 giờ trước
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <a href="#" class="small text-primary font-weight-bold">
                        Xem toàn bộ hoạt động <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
         5. TOUR NAMES LIST - Danh sách tên tour
         ============================================ -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Danh sách tour
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php
                        if (!empty($tourList)) {
                            foreach ($tourList as $tour) {
                        ?>
                        <div class="list-group-item px-4 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 font-weight-bold text-gray-800">
                                        <i class="fas fa-route text-primary"></i>
                                        <?php echo htmlspecialchars($tour['name'] ?? 'N/A'); ?>
                                    </h6>
                                    <p class="small text-muted mb-0">
                                        <i class="fas fa-clock"></i> <?php echo $tour['duration'] ?? '0'; ?> ngày |
                                        <i class="fas fa-map-pin"></i> <?php echo htmlspecialchars($tour['start_location'] ?? 'N/A'); ?>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <strong class="text-primary d-block"><?php echo number_format($tour['price'] ?? 0, 0, ',', '.'); ?>₫</strong>
                                    <small class="text-muted">Giá cơ bản</small>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                        ?>
                        <div class="list-group-item px-4 py-3">
                            <p class="text-muted text-center mb-0">Chưa có tour nào</p>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <a href="index.php?action=tours_index" class="small text-primary font-weight-bold">
                        Xem tất cả tour <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Connected Tours with Dates (6 cột) -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-check"></i> Tour đã kết nối
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php
                        if (!empty($connectedTours)) {
                            foreach ($connectedTours as $tour) {
                                $departureDate = new DateTime($tour['departure_date']);
                                $formattedDate = $departureDate->format('d/m/Y');
                        ?>
                        <div class="list-group-item px-4 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 font-weight-bold text-gray-800">
                                        <i class="fas fa-bus text-info"></i>
                                        <?php echo htmlspecialchars($tour['tour_name'] ?? 'N/A'); ?>
                                    </h6>
                                    <p class="small text-muted mb-1">
                                        <i class="fas fa-calendar"></i> Khởi hành: <strong><?php echo $formattedDate; ?></strong>
                                    </p>
                                    <p class="small mb-0">
                                        <i class="fas fa-ticket-alt"></i> <?php echo $tour['booking_count'] ?? '0'; ?> booking
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success">Kết nối</span>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                        ?>
                        <div class="list-group-item px-4 py-3">
                            <p class="text-muted text-center mb-0">Chưa có tour kết nối nào</p>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <a href="index.php?action=departures_index" class="small text-primary font-weight-bold">
                        Xem tất cả lịch trình <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>


<!-- CSS loaded in views/layouts/main.php -->

<!-- Script để khởi tạo Charts (nếu Chart.js được load) -->
<script src="assets/vendor/chart.js/Chart.min.js"></script>

<script>
    // 1. NHẬN DỮ LIỆU TỪ PHP
    // Chuyển đổi mảng PHP sang JSON để JS hiểu được
    const revenueData = <?php echo json_encode($revenueData ?? []); ?>;
    const statusLabels = <?php echo json_encode($statusData['labels'] ?? []); ?>;
    const statusValues = <?php echo json_encode($statusData['data'] ?? []); ?>;

    // Cấu hình định dạng tiền tệ (VND)
    const number_format = (number, decimals, dec_point, thousands_sep) => {
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // ==========================================
        // BIỂU ĐỒ 1: AREA CHART (Doanh thu)
        // ==========================================
        var ctxRevenue = document.getElementById("revenueChart");
        if (ctxRevenue) {
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: ["Th 1", "Th 2", "Th 3", "Th 4", "Th 5", "Th 6", "Th 7", "Th 8", "Th 9", "Th 10", "Th 11", "Th 12"],
                    datasets: [{
                        label: "Doanh thu",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: revenueData, // Dữ liệu thật từ PHP
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                    scales: {
                        xAxes: [{
                            time: { unit: 'date' },
                            gridLines: { display: false, drawBorder: false },
                            ticks: { maxTicksLimit: 7 }
                        }],
                        yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                callback: function(value) { return number_format(value) + 'đ'; }
                            },
                            gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                        }],
                    },
                    legend: { display: false },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + ': ' + number_format(tooltipItem.yLabel) + 'đ';
                            }
                        }
                    }
                }
            });
        }

        // ==========================================
        // BIỂU ĐỒ 2: PIE CHART (Trạng thái)
        // ==========================================
        var ctxStatus = document.getElementById("bookingStatusChart");
        if (ctxStatus) {
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: statusLabels, // Labels thật từ PHP (VD: Paid, Pending)
                    datasets: [{
                        data: statusValues, // Số liệu thật từ PHP
                        backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b', '#4e73df'], // Xanh, Vàng, Đỏ, Xanh dương
                        hoverBackgroundColor: ['#17a673', '#dda20a', '#be2617', '#2e59d9'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: { display: true, position: 'bottom' },
                    cutoutPercentage: 80,
                },
            });
        }
    });

    // Hàm refresh data giữ nguyên
    function refreshDashboard() {
        location.reload();
    }
    
    // Hàm export report giữ nguyên
    function exportReport() {
        alert('Chức năng export report sẽ được triển khai.');
    }
</script>