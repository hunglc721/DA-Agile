<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h2 text-gray-800 mb-2">
                <i class="fas fa-chart-bar text-primary"></i> Báo Cáo Tài Chính Tour
            </h1>
            <p class="text-muted">Phân tích doanh thu, chi phí và lợi nhuận từ các tour</p>
        </div>
        <div>
            <a href="index.php?action=tour_financial_report&export=csv" class="btn btn-success btn-lg">
                <i class="fas fa-download"></i> Xuất CSV
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-lg mb-4 border-0">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-filter"></i> Tiêu Chí Lọc
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="index.php?action=tour_financial_report">
                <input type="hidden" name="action" value="tour_financial_report">
                <div class="row">
                    <!-- Category Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold mb-2">
                            <i class="fas fa-list text-info"></i> Danh Mục
                        </label>
                        <select name="category_id" class="form-select form-select-sm" style="border-radius: 8px;">
                            <option value="">-- Tất Cả Danh Mục --</option>
                            <?php if (isset($categories) && is_array($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? null) == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Tour Type Filter -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold mb-2">
                            <i class="fas fa-globe text-warning"></i> Loại Tour
                        </label>
                        <select name="tour_type" class="form-select form-select-sm" style="border-radius: 8px;">
                            <option value="">-- Tất Cả Loại --</option>
                            <?php if (isset($tourTypes) && is_array($tourTypes)): ?>
                                <?php foreach ($tourTypes as $type): ?>
                                    <option value="<?= $type['code'] ?>" <?= ($filters['tour_type'] ?? null) == $type['code'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($type['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- From Date -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold mb-2">
                            <i class="fas fa-calendar-alt text-danger"></i> Từ Ngày
                        </label>
                        <input type="date" name="from_date" class="form-control form-control-sm" style="border-radius: 8px;" value="<?= htmlspecialchars($filters['from_date'] ?? '') ?>">
                    </div>

                    <!-- To Date -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold mb-2">
                            <i class="fas fa-calendar-alt text-success"></i> Đến Ngày
                        </label>
                        <input type="date" name="to_date" class="form-control form-control-sm" style="border-radius: 8px;" value="<?= htmlspecialchars($filters['to_date'] ?? '') ?>">
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="row mt-3">
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Áp Dụng Bộ Lọc
                        </button>
                        <a href="index.php?action=tour_financial_report" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Đặt Lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <?php if (isset($summary) && $summary): ?>
    <div class="row mb-4">
        <!-- Total Tours Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-primary font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fas fa-suitcase"></i> Tổng Số Tour
                            </div>
                            <div class="h3 mb-0 text-gray-800"><?= $summary['total_tours'] ?? 0 ?></div>
                            <small class="text-muted">tour đang chạy</small>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="fas fa-suitcase"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-success font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fas fa-money-bill-wave"></i> Tổng Doanh Thu
                            </div>
                            <div class="h3 mb-0 text-gray-800"><?= number_format($summary['total_revenue'] ?? 0, 0, ',', '.') ?> đ</div>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> Từ <?= $summary['confirmed_bookings'] ?? 0 ?> đơn
                            </small>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cost Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-warning font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fas fa-calculator"></i> Tổng Chi Phí
                            </div>
                            <div class="h3 mb-0 text-gray-800"><?= number_format($summary['total_cost'] ?? 0, 0, ',', '.') ?> đ</div>
                            <small class="text-muted">bao gồm tất cả chi phí</small>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-info font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fas fa-chart-line"></i> Lợi Nhuận Ròng
                            </div>
                            <div class="h3 mb-0 text-gray-800"><?= number_format($summary['total_profit'] ?? 0, 0, ',', '.') ?> đ</div>
                            <?php 
                            $roi = ($summary['total_revenue'] && $summary['total_cost']) 
                                ? (($summary['total_profit'] / $summary['total_cost']) * 100) 
                                : 0;
                            ?>
                            <small class="text-info">
                                <i class="fas fa-percentage"></i> ROI: <?= number_format($roi, 2) ?>%
                            </small>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tours Table -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-table"></i> Chi Tiết Tài Chính Theo Tour
            </h6>
            <span class="badge badge-light text-primary badge-pill" style="font-size: 0.85rem;">
                <?= count($tours ?? []) ?> tour
            </span>
        </div>
        <div class="card-body p-0">
            <?php if (empty($tours)): ?>
                <div class="alert alert-info m-3 border-0" role="alert">
                    <i class="fas fa-info-circle"></i> Không có dữ liệu để hiển thị. Vui lòng thử thay đổi tiêu chí lọc.
                </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0" id="financialTable">
                    <thead class="bg-light border-bottom">
                        <tr style="background-color: #f8f9fa;">
                            <th class="fw-bold text-gray-800" width="5%">
                                <i class="fas fa-hashtag"></i> ID
                            </th>
                            <th class="fw-bold text-gray-800" width="15%">
                                <i class="fas fa-map-pin"></i> Tên Tour
                            </th>
                            <th class="fw-bold text-gray-800" width="10%">
                                <i class="fas fa-list"></i> Danh Mục
                            </th>
                            <th class="fw-bold text-gray-800" width="10%">
                                <i class="fas fa-globe"></i> Loại
                            </th>
                            <th class="fw-bold text-gray-800 text-center" width="8%">
                                <i class="fas fa-ticket-alt"></i> Đặt
                            </th>
                            <th class="fw-bold text-gray-800 text-center" width="8%">
                                <i class="fas fa-check-circle"></i> Xác Nhận
                            </th>
                            <th class="fw-bold text-gray-800 text-end" width="12%">
                                <i class="fas fa-dollar-sign"></i> Giá/Người
                            </th>
                            <th class="fw-bold text-gray-800 text-end" width="12%">
                                <i class="fas fa-coins"></i> CP/Người
                            </th>
                            <th class="fw-bold text-gray-800 text-end" width="12%">
                                <i class="fas fa-arrow-alt-circle-up text-success"></i> Doanh Thu
                            </th>
                            <th class="fw-bold text-gray-800 text-end" width="12%">
                                <i class="fas fa-arrow-alt-circle-down text-warning"></i> Chi Phí
                            </th>
                            <th class="fw-bold text-gray-800 text-end" width="12%">
                                <i class="fas fa-chart-pie text-info"></i> Lợi Nhuận
                            </th>
                            <th class="fw-bold text-gray-800 text-end" width="8%">
                                <i class="fas fa-percentage"></i> Lợi Suất
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tours as $tour): 
                            $profit_margin = (!empty($tour['revenue']) && $tour['revenue'] > 0) 
                                ? (($tour['profit'] / $tour['revenue']) * 100) 
                                : 0;
                        ?>
                        <tr style="transition: background-color 0.3s ease;">
                            <td class="fw-bold">#<?= $tour['id'] ?></td>
                            <td>
                                <div class="fw-bold text-gray-800"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                <?php if ($tour['tour_code']): ?>
                                    <small class="text-muted"><?= htmlspecialchars($tour['tour_code']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info" style="border-radius: 6px;">
                                    <?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge <?php 
                                    if ($tour['tour_type'] === 'domestic') echo 'bg-success';
                                    elseif ($tour['tour_type'] === 'international') echo 'bg-primary';
                                    else echo 'bg-warning';
                                ?>" style="border-radius: 6px;">
                                    <?php 
                                    if ($tour['tour_type'] === 'domestic') echo '<i class="fas fa-home"></i> Trong Nước';
                                    elseif ($tour['tour_type'] === 'international') echo '<i class="fas fa-globe"></i> Quốc Tế';
                                    else echo '<i class="fas fa-cogs"></i> Tùy Chỉnh';
                                    ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark" style="border-radius: 6px;">
                                    <?= $tour['total_bookings'] ?? 0 ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success" style="border-radius: 6px;">
                                    <?= $tour['confirmed_bookings'] ?? 0 ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold"><?= number_format($tour['unit_price'] ?? 0, 0, ',', '.') ?></span>
                                <small class="text-muted d-block">đ</small>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold"><?= number_format($tour['unit_cost'] ?? 0, 0, ',', '.') ?></span>
                                <small class="text-muted d-block">đ</small>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold text-success"><?= number_format($tour['revenue'] ?? 0, 0, ',', '.') ?></span>
                                <small class="text-success d-block">đ</small>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold text-warning"><?= number_format($tour['total_cost'] ?? 0, 0, ',', '.') ?></span>
                                <small class="text-warning d-block">đ</small>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold <?= ($tour['profit'] ?? 0) > 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= number_format($tour['profit'] ?? 0, 0, ',', '.') ?>
                                </span>
                                <small class="<?= ($tour['profit'] ?? 0) > 0 ? 'text-success' : 'text-danger' ?> d-block">
                                    <?= ($tour['profit'] ?? 0) > 0 ? '✓' : '✗' ?> đ
                                </small>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold badge <?= $profit_margin > 0 ? 'bg-success' : 'bg-danger' ?>" style="border-radius: 6px; font-size: 0.9rem;">
                                    <?= number_format($profit_margin, 1) ?>%
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
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
    }

    .card {
        border-radius: 10px;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        padding: 6px 10px;
        font-size: 0.85rem;
    }

    .form-select, .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>
