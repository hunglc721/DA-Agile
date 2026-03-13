<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h2 text-gray-800 mb-2">
                <i class="fas fa-exclamation-triangle text-danger"></i> Quản Lý Báo Cáo Sự Cố
            </h1>
            <p class="text-muted">Theo dõi tất cả các sự cố được báo cáo từ các tour</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-danger font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fas fa-exclamation-circle"></i> Tổng Sự Cố
                            </div>
                            <div class="h3 mb-0 text-gray-800"><?= $stats['total'] ?? 0 ?></div>
                            <small class="text-muted">được báo cáo</small>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-warning font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fas fa-clock"></i> Chưa Xử Lý
                            </div>
                            <div class="h3 mb-0 text-gray-800"><?= $stats['pending'] ?? 0 ?></div>
                            <small class="text-warning">cần xử lý</small>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-info font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fas fa-hourglass-end"></i> Đang Xử Lý
                            </div>
                            <div class="h3 mb-0 text-gray-800"><?= $stats['in_progress'] ?? 0 ?></div>
                            <small class="text-muted">đang được xem xét</small>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="fas fa-hourglass-end"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow h-100" style="border-radius: 10px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-success font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fas fa-check-circle"></i> Đã Xử Lý
                            </div>
                            <div class="h3 mb-0 text-gray-800"><?= $stats['resolved'] ?? 0 ?></div>
                            <small class="text-success">hoàn thành</small>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-lg mb-4 border-0">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-filter"></i> Tiêu Chí Lọc
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="row">
                <input type="hidden" name="action" value="incident_reports">
                
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold mb-2">
                        <i class="fas fa-filter text-info"></i> Trạng Thái
                    </label>
                    <select name="status" class="form-select" style="border-radius: 8px;" onchange="this.form.submit()">
                        <option value="">-- Tất Cả Trạng Thái --</option>
                        <option value="pending" <?= ($_GET['status'] ?? '') == 'pending' ? 'selected' : '' ?>>⏳ Chưa Xử Lý</option>
                        <option value="in_progress" <?= ($_GET['status'] ?? '') == 'in_progress' ? 'selected' : '' ?>>⌛ Đang Xử Lý</option>
                        <option value="resolved" <?= ($_GET['status'] ?? '') == 'resolved' ? 'selected' : '' ?>>✓ Đã Xử Lý</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold mb-2">
                        <i class="fas fa-calendar-alt text-danger"></i> Từ Ngày
                    </label>
                    <input type="date" name="from_date" class="form-control" style="border-radius: 8px;" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold mb-2">
                        <i class="fas fa-calendar-alt text-success"></i> Đến Ngày
                    </label>
                    <input type="date" name="to_date" class="form-control" style="border-radius: 8px;" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold mb-2">
                        <i class="fas fa-search"></i> Tìm Kiếm
                    </label>
                    <input type="text" name="search" class="form-control" style="border-radius: 8px;" placeholder="Tiêu đề sự cố..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>

                <div class="col-12 d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm Kiếm
                    </button>
                    <a href="?action=incident_reports" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i> Đặt Lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Incidents List -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-list"></i> Danh Sách Báo Cáo Sự Cố
            </h6>
            <span class="badge badge-light text-primary badge-pill"><?= count($incidents ?? []) ?> báo cáo</span>
        </div>
        <div class="card-body p-0">
            <?php if (empty($incidents)): ?>
                <div class="alert alert-info m-3 border-0" role="alert">
                    <i class="fas fa-info-circle"></i> Không có báo cáo sự cố nào phù hợp với tiêu chí lọc.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light border-bottom">
                            <tr style="background-color: #f8f9fa;">
                                <th class="fw-bold text-gray-800">
                                    <i class="fas fa-hashtag"></i> ID
                                </th>
                                <th class="fw-bold text-gray-800">
                                    <i class="fas fa-exclamation-triangle"></i> Tiêu Đề Sự Cố
                                </th>
                                <th class="fw-bold text-gray-800">
                                    <i class="fas fa-map-pin"></i> Tour
                                </th>
                                <th class="fw-bold text-gray-800">
                                    <i class="fas fa-user-tie"></i> HDV
                                </th>
                                <th class="fw-bold text-gray-800 text-center">
                                    <i class="fas fa-layer-group"></i> Trạng Thái
                                </th>
                                <th class="fw-bold text-gray-800">
                                    <i class="fas fa-calendar-alt"></i> Thời Gian
                                </th>
                                <th class="fw-bold text-gray-800 text-center">
                                    <i class="fas fa-cog"></i> Hành Động
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incidents as $incident): ?>
                            <tr>
                                <td class="fw-bold">#<?= $incident['id'] ?></td>
                                <td>
                                    <div class="fw-bold text-gray-800"><?= htmlspecialchars($incident['title']) ?></div>
                                    <small class="text-muted text-truncate d-block" style="max-width: 300px;">
                                        <?= htmlspecialchars(substr($incident['content'], 0, 80)) ?>...
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-info" style="border-radius: 6px;">
                                        <?= htmlspecialchars($incident['tour_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <strong><?= htmlspecialchars($incident['guide_name']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($incident['guide_phone']) ?></small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    $status = $incident['status'] ?? 'pending';
                                    $statusBadgeClass = [
                                        'pending' => 'bg-warning',
                                        'in_progress' => 'bg-info',
                                        'resolved' => 'bg-success'
                                    ][$status] ?? 'bg-secondary';
                                    $statusText = [
                                        'pending' => '⏳ Chưa Xử Lý',
                                        'in_progress' => '⌛ Đang Xử Lý',
                                        'resolved' => '✓ Đã Xử Lý'
                                    ][$status] ?? 'Không xác định';
                                    ?>
                                    <span class="badge <?= $statusBadgeClass ?>" style="border-radius: 6px; font-size: 0.85rem;">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td class="small">
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($incident['created_at'])) ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <a href="?action=tour_diary&tour_id=<?= $incident['tour_id'] ?>" class="btn btn-sm btn-outline-primary" title="Xem Chi Tiết">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
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
