<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thống Kê Bán Tour & Đặt Chỗ</h1>
        <div>
            <a href="index.php?action=bookings" class="btn btn-info btn-sm shadow-sm">
                <i class="fas fa-list"></i> Danh Sách Booking
            </a>
            <a href="index.php?action=bookings_create" class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-plus-circle"></i> Tạo Booking Mới
            </a>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="row mb-4">
        <!-- Card: Tổng Booking -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary text-uppercase mb-1 small font-weight-bold">
                        <i class="fas fa-ticket-alt"></i> Tổng Booking
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <?= isset($stats) ? count($stats['all']) : '0' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Chờ Xác Nhận -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning text-uppercase mb-1 small font-weight-bold">
                        <i class="fas fa-hourglass-half"></i> Chờ Xác Nhận
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <?= isset($stats) ? count($stats['pending']) : '0' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Đã Xác Nhận -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info text-uppercase mb-1 small font-weight-bold">
                        <i class="fas fa-check-circle"></i> Đã Xác Nhận
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <?= isset($stats) ? count($stats['confirmed']) : '0' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Tổng Doanh Thu -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success text-uppercase mb-1 small font-weight-bold">
                        <i class="fas fa-coins"></i> Tổng Doanh Thu
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-success">
                        <?= isset($stats) ? number_format($stats['total_revenue']) . 'đ' : '0đ' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BIỂU ĐỒ & PHÂN TÍCH -->
    <div class="row mb-4">
        <!-- Biểu đồ trạng thái -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Phân Bố Theo Trạng Thái</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ loại booking -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Khách Lẻ vs Đoàn</h6>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TOP TOURS & CUSTOMERS -->
    <div class="row">
        <!-- Top Tours -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-fire"></i> Top 5 Tour Được Đặt</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive small">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Xếp Hạng</th>
                                    <th>Tên Tour</th>
                                    <th>Lượt Đặt</th>
                                    <th>Doanh Thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge badge-warning">1</span></td>
                                    <td>Tour Nha Trang 3N2Đ</td>
                                    <td><strong>15</strong></td>
                                    <td class="text-success font-weight-bold">7,500,000đ</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">2</span></td>
                                    <td>Tour Đà Lạt 2N1Đ</td>
                                    <td><strong>12</strong></td>
                                    <td class="text-success font-weight-bold">4,800,000đ</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">3</span></td>
                                    <td>Tour Hạ Long 3N2Đ</td>
                                    <td><strong>10</strong></td>
                                    <td class="text-success font-weight-bold">6,500,000đ</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">4</span></td>
                                    <td>Tour Sài Gòn - Cần Thơ</td>
                                    <td><strong>8</strong></td>
                                    <td class="text-success font-weight-bold">3,200,000đ</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">5</span></td>
                                    <td>Tour Phú Quốc 4N3Đ</td>
                                    <td><strong>7</strong></td>
                                    <td class="text-success font-weight-bold">5,600,000đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê khách -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-warning text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-users"></i> Thống Kê Khách Hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6 mb-3">
                            <div class="border-left-success card-stat">
                                <h6 class="text-muted text-uppercase text-xs font-weight-bold mb-1">Khách Lẻ (FIT)</h6>
                                <h3 class="text-success font-weight-bold">52</h3>
                                <small class="text-muted">45% tổng booking</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border-left-primary card-stat">
                                <h6 class="text-muted text-uppercase text-xs font-weight-bold mb-1">Đoàn (GIT)</h6>
                                <h3 class="text-primary font-weight-bold">63</h3>
                                <small class="text-muted">55% tổng booking</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="row text-center">
                        <div class="col-md-6 mb-3">
                            <div class="border-left-info card-stat">
                                <h6 class="text-muted text-uppercase text-xs font-weight-bold mb-1">Tổng Khách</h6>
                                <h3 class="text-info font-weight-bold">487</h3>
                                <small class="text-muted">người tham gia</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border-left-danger card-stat">
                                <h6 class="text-muted text-uppercase text-xs font-weight-bold mb-1">Tỷ Lệ Hủy</h6>
                                <h3 class="text-danger font-weight-bold">8.2%</h3>
                                <small class="text-muted">so với tổng booking</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-stat {
    padding: 15px;
    border-left: 4px solid;
    border-radius: 4px;
    background-color: #f8f9fc;
}
</style>

<script>
// Chart.js - Biểu đồ trạng thái
var ctxStatus = document.getElementById('statusChart').getContext('2d');
var statusChart = new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: ['Chờ XN', 'Đã XN', 'Đã TT', 'Hoàn TT', 'Đã Hủy'],
        datasets: [{
            data: [12, 19, 8, 15, 6],
            backgroundColor: [
                '#ffc107',
                '#17a2b8',
                '#28a745',
                '#6c757d',
                '#dc3545'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Chart.js - Biểu đồ loại booking
var ctxType = document.getElementById('typeChart').getContext('2d');
var typeChart = new Chart(ctxType, {
    type: 'pie',
    data: {
        labels: ['Khách Lẻ (FIT)', 'Đoàn (GIT)'],
        datasets: [{
            data: [52, 63],
            backgroundColor: ['#17a2b8', '#0c5460']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
