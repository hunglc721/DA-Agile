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
                        <i class="fas fa-table"></i> 5 Booking gần nhất
                    </h6>
                    <a href="#" class="btn btn-sm btn-light">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#ID</th>
                                    <th>Tên khách hàng</th>
                                    <th>Tên Tour</th>
                                    <th class="text-center">Ngày khởi hành</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row 1 -->
                                <tr>
                                    <td class="text-center"><strong>#BK001</strong></td>
                                    <td>Nguyễn Văn A</td>
                                    <td>Tour Hà Nội - Hạ Long 3 ngày</td>
                                    <td class="text-center">15/12/2025</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Paid</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-xs btn-outline-primary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Row 2 -->
                                <tr>
                                    <td class="text-center"><strong>#BK002</strong></td>
                                    <td>Trần Thị B</td>
                                    <td>Tour Sapa - Fansipan 4 ngày</td>
                                    <td class="text-center">18/12/2025</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-xs btn-outline-primary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Row 3 -->
                                <tr>
                                    <td class="text-center"><strong>#BK003</strong></td>
                                    <td>Phạm Văn C</td>
                                    <td>Tour Đà Nẵng - Hội An 2 ngày</td>
                                    <td class="text-center">20/12/2025</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Paid</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-xs btn-outline-primary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Row 4 -->
                                <tr>
                                    <td class="text-center"><strong>#BK004</strong></td>
                                    <td>Lê Thị D</td>
                                    <td>Tour Nha Trang - Đảo Hòn Mun 3 ngày</td>
                                    <td class="text-center">22/12/2025</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">Cancelled</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-xs btn-outline-primary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Row 5 -->
                                <tr>
                                    <td class="text-center"><strong>#BK005</strong></td>
                                    <td>Đặng Văn E</td>
                                    <td>Tour Huế - Phong Nha 5 ngày</td>
                                    <td class="text-center">25/12/2025</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-xs btn-outline-primary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
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
                    <span class="badge bg-info">3 tuần tới</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <!-- Tour 1 -->
                        <div class="list-group-item px-4 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 font-weight-bold text-gray-800">
                                        <i class="fas fa-map-marker-alt text-danger"></i> Hà Nội - Hạ Long 3N2Đ
                                    </h6>
                                    <p class="small text-muted mb-1">
                                        <i class="fas fa-users"></i> 24 khách | 
                                        <i class="fas fa-bus"></i> 2 xe
                                    </p>
                                    <p class="small mb-0">
                                        <span class="badge bg-success">Confirmed</span>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="d-block text-muted mb-2">Khởi hành</small>
                                    <strong class="d-block text-primary">15/12/2025</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Tour 2 -->
                        <div class="list-group-item px-4 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 font-weight-bold text-gray-800">
                                        <i class="fas fa-mountain text-warning"></i> Sapa - Fansipan 4N3Đ
                                    </h6>
                                    <p class="small text-muted mb-1">
                                        <i class="fas fa-users"></i> 18 khách | 
                                        <i class="fas fa-bus"></i> 1 xe
                                    </p>
                                    <p class="small mb-0">
                                        <span class="badge bg-warning">Pending</span>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="d-block text-muted mb-2">Khởi hành</small>
                                    <strong class="d-block text-warning">18/12/2025</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Tour 3 -->
                        <div class="list-group-item px-4 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 font-weight-bold text-gray-800">
                                        <i class="fas fa-wave-square text-info"></i> Đà Nẵng - Hội An 2N1Đ
                                    </h6>
                                    <p class="small text-muted mb-1">
                                        <i class="fas fa-users"></i> 32 khách | 
                                        <i class="fas fa-bus"></i> 2 xe
                                    </p>
                                    <p class="small mb-0">
                                        <span class="badge bg-success">Confirmed</span>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="d-block text-muted mb-2">Khởi hành</small>
                                    <strong class="d-block text-primary">20/12/2025</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <a href="#" class="small text-primary font-weight-bold">
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
</div>

<!-- Custom CSS for Dashboard -->
<style>
    /* ============================================
       1. CARD STYLES - Các thẻ chỉ số
       ============================================ */
    
    .card {
        border: none;
        border-radius: 0.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Border left cho metric cards */
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    /* Icon colors in metric cards */
    .text-primary {
        color: #007bff !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-info {
        color: #17a2b8 !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    /* ============================================
       2. CHART CONTAINER STYLES
       ============================================ */

    .card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 0.5rem;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15) !important;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-bottom: none;
        padding: 1.5rem 1.25rem;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .card-header h6 {
        color: white;
        font-weight: 600;
        margin: 0;
    }

    .card-header .dropdown-toggle {
        color: white !important;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .card-header .dropdown-toggle:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: white;
    }

    /* Chart container styling */
    .chart-container {
        position: relative;
        height: 300px;
        padding: 20px;
    }

    canvas {
        max-width: 100%;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* ============================================
       3. TABLE STYLES
       ============================================ */

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease;
    }

    .table th {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
        font-size: 0.95rem;
    }

    .btn-xs {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
    }

    /* ============================================
       4. BADGE STYLES
       ============================================ */

    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        border-radius: 0.35rem;
        font-size: 0.8rem;
    }

    .badge.bg-success {
        background-color: #28a745 !important;
    }

    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }

    .badge.bg-danger {
        background-color: #dc3545 !important;
    }

    /* ============================================
       5. TYPOGRAPHY
       ============================================ */

    .h3 {
        font-weight: 700;
        color: #2e3338;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .text-gray-800 {
        color: #2e3338 !important;
    }

    .font-weight-bold {
        font-weight: 700;
    }

    .text-uppercase {
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .text-xs {
        font-size: 0.8rem;
    }

    /* ============================================
       6. RESPONSIVE ADJUSTMENTS
       ============================================ */

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .col-xl-3 {
            margin-bottom: 1rem;
        }

        .col-xl-8,
        .col-xl-4 {
            margin-bottom: 1rem;
        }

        .table {
            font-size: 0.85rem;
        }

        .table th {
            font-size: 0.75rem;
        }
    }

    /* ============================================
       7. SHADOW UTILITIES
       ============================================ */

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .shadow-sm:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* ============================================
       8. SPACING & PADDING
       ============================================ */

    .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    .px-4 {
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }

    .my-4 {
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .mb-0 {
        margin-bottom: 0 !important;
    }

    .mt-1 {
        margin-top: 0.25rem !important;
    }

    .mt-2 {
        margin-top: 0.5rem !important;
    }

    .small {
        font-size: 0.875rem;
    }

    /* ============================================
       9. LIST GROUP STYLES - Upcoming Tours & Activity
       ============================================ */

    .list-group-item {
        background-color: #fff;
        border: none;
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .list-group-item.border-bottom {
        border-bottom: 1px solid #dee2e6 !important;
    }

    /* ============================================
       10. ACTIVITY FEED STYLES
       ============================================ */

    .activity-feed {
        padding: 0;
    }

    .activity-item {
        border-bottom: 1px solid #dee2e6;
        transition: background-color 0.2s ease;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item:hover {
        background-color: #f8f9fa;
    }

    .activity-icon {
        min-width: 2.5rem;
        text-align: center;
    }

    .activity-icon i {
        font-size: 1.25rem;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-radius: 50%;
    }

    .activity-icon i.text-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745 !important;
    }

    .activity-icon i.text-primary {
        background-color: rgba(0, 123, 255, 0.1);
        color: #007bff !important;
    }

    .activity-icon i.text-warning {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107 !important;
    }

    .activity-icon i.text-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545 !important;
    }

    .activity-icon i.text-info {
        background-color: rgba(23, 162, 184, 0.1);
        color: #17a2b8 !important;
    }

    .activity-content h6 {
        color: #2e3338;
        font-weight: 600;
    }

    /* ============================================
       11. TOUR CARD STYLES - Upcoming Tours
       ============================================ */

    .list-group-item h6 {
        color: #2e3338;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .list-group-item .badge {
        font-size: 0.7rem;
        padding: 0.4rem 0.6rem;
    }

    /* ============================================
       12. TEXT UTILITIES
       ============================================ */

    .me-3 {
        margin-right: 1rem !important;
    }

    .d-block {
        display: block;
    }

    .text-end {
        text-align: right;
    }

    /* ============================================
       13. ENHANCED RESPONSIVE
       ============================================ */

    @media (max-width: 1200px) {
        .col-xl-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .activity-item,
        .list-group-item {
            padding: 0.75rem 1rem !important;
        }

        .activity-icon {
            min-width: 2rem;
        }

        .activity-icon i {
            font-size: 1rem;
            width: 1.75rem;
            height: 1.75rem;
        }

        .list-group-item h6 {
            font-size: 0.85rem;
        }
    }
</style>

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