<div class="container-fluid">
    <!-- DEBUG: Tours Data -->
    <?php
    echo "<!-- DEBUG: tours count = " . count($tours ?? []) . " -->";
    echo "<!-- DEBUG: tours = " . print_r($tours, true) . " -->";
    ?>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Tiêu đề bên trái -->
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-map-marked-alt text-primary mr-2"></i> Danh Sách Tour
        </h1>


        <div class="d-flex gap-2">
            <a href="index.php?action=tours_trash" class="btn btn-outline-secondary">
                <i class="fas fa-trash"></i> Thùng Rác
            </a>
            <a href="index.php?action=tours_create"
                class="btn btn-success shadow-sm font-weight-bold"
                style="background: linear-gradient(135deg, #28a745, #20c997); border: none; border-radius: 8px; padding: 8px 18px;">
                <i class="fas fa-plus"></i> Thêm Tour Mới
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="index.php?action=tours" class="form-inline">
                <input type="hidden" name="action" value="tours">

                <!-- Search Box -->
                <div class="form-group mr-3">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tour..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>

                <!-- Tour Type Filter -->
                <div class="form-group mr-3">
                    <select name="tour_type" class="form-control">
                        <option value="">-- Tất Cả Loại Tour --</option>
                        <?php if (isset($tourTypes) && is_array($tourTypes)): ?>
                            <?php foreach ($tourTypes as $type): ?>
                                <option value="<?= $type['code'] ?>" <?= ($filters['tour_type'] ?? null) == $type['code'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($type['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="form-group mr-3">
                    <select name="category_id" class="form-control">
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

                <!-- Status Filter -->
                <div class="form-group mr-3">
                    <select name="status" class="form-control">
                        <option value="">-- Tất Cả Trạng Thái --</option>
                        <option value="active" <?= ($filters['status'] ?? null) == 'active' ? 'selected' : '' ?>>Hoạt Động</option>
                        <option value="inactive" <?= ($filters['status'] ?? null) == 'inactive' ? 'selected' : '' ?>>Không Hoạt Động</option>
                        <option value="completed" <?= ($filters['status'] ?? null) == 'completed' ? 'selected' : '' ?>>Đã Hoàn Thành</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="form-group">
                    <button type="submit" class="btn btn-info mr-2">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    <a href="index.php?action=tours" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Đặt Lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tour Type & Category Quick Filter -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <h6 class="text-primary font-weight-bold mb-2"><i class="fas fa-filter"></i> Lọc Nhanh Theo Loại Tour</h6>
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="index.php?action=tours" class="btn btn-sm btn-outline-primary <?= empty($filters['tour_type']) ? 'active' : '' ?>">
                    <i class="fas fa-globe"></i> Tất Cả
                </a>
                <?php if (isset($tourTypes) && is_array($tourTypes)): ?>
                    <?php foreach ($tourTypes as $type): ?>
                        <a href="index.php?action=tours&tour_type=<?= urlencode($type['code']) ?>"
                            class="btn btn-sm btn-outline-info <?= ($filters['tour_type'] ?? null) == $type['code'] ? 'active' : '' ?>"
                            style="border-color: <?= $type['color'] ?>; color: <?= $type['color'] ?>;">
                            <i class="<?= $type['icon'] ?>"></i> <?= htmlspecialchars($type['name']) ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <h6 class="text-primary font-weight-bold mb-2"><i class="fas fa-tag"></i> Lọc Nhanh Theo Danh Mục</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="index.php?action=tours" class="btn btn-sm btn-outline-primary <?= empty($filters['category_id']) ? 'active' : '' ?>">
                    <i class="fas fa-globe"></i> Tất Cả Danh Mục
                </a>
                <?php if (isset($categories) && is_array($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <a href="index.php?action=tours&category_id=<?= $cat['id'] ?>"
                            class="btn btn-sm btn-outline-warning <?= ($filters['category_id'] ?? null) == $cat['id'] ? 'active' : '' ?>">
                            <i class="fas fa-map"></i> <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tours Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <?php
                if (!empty($filters['category_id'])):
                    $catName = '';
                    foreach ($categories as $cat) {
                        if ($cat['id'] == $filters['category_id']) {
                            $catName = $cat['name'];
                            break;
                        }
                    }
                    echo "Tour: " . htmlspecialchars($catName);
                else:
                    echo "Tất Cả Tour";
                endif;
                ?>
            </h6>
            <span class="badge badge-primary badge-pill"><?= count($tours ?? []) ?> tour</span>
        </div>
        <div class="card-body">
            <?php if (empty($tours)): ?>
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> Không tìm thấy tour nào.
                    <a href="index.php?action=tours_create" class="alert-link">Tạo tour mới</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th width="5%">#ID</th>
                                <th width="18%">Tên Tour</th>
                                <th width="12%">Loại Tour</th>
                                <th width="12%">Danh Mục</th>
                                <th width="12%">Giá (VNĐ)</th>
                                <th width="7%">Thời Lượng</th>
                                <th width="8%">Sức Chứa</th>
                                <th width="8%">Trạng Thái</th>
                                <th width="18%">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tours as $tour): ?>
                                <tr>
                                    <td><strong>#<?= $tour['id'] ?></strong></td>
                                    <td>
                                        <strong><?= htmlspecialchars($tour['name']) ?></strong>
                                        <br>
                                        <small class="text-muted">Mã: <?= htmlspecialchars($tour['tour_code'] ?? 'N/A') ?></small>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $typeLabel = '';
                                        $typeClass = '';
                                        switch ($tour['tour_type'] ?? 'domestic') {
                                            case 'domestic':
                                                $typeLabel = 'Trong Nước';
                                                $typeClass = 'badge-success';
                                                break;
                                            case 'international':
                                                $typeLabel = 'Quốc Tế';
                                                $typeClass = 'badge-primary';
                                                break;
                                            case 'custom':
                                                $typeLabel = 'Theo Yêu Cầu';
                                                $typeClass = 'badge-warning';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $typeClass ?>">
                                            <?= $typeLabel ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td class="text-right font-weight-bold text-success">
                                        <?= number_format($tour['price'], 0, ',', '.') ?> đ
                                    </td>
                                    <td class="text-center">
                                        <?= $tour['duration'] ?? 0 ?> ngày
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary">
                                            <?= $tour['available_slots'] ?? 0 ?>/<?= $tour['max_capacity'] ?? 0 ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $statusClass = match ($tour['status']) {
                                            'active' => 'badge-success',
                                            'inactive' => 'badge-danger',
                                            'completed' => 'badge-secondary',
                                            default => 'badge-warning'
                                        };
                                        $statusText = match ($tour['status']) {
                                            'active' => 'Hoạt Động',
                                            'inactive' => 'Không Hoạt Động',
                                            'completed' => 'Đã Hoàn Thành',
                                            default => 'Không Xác Định'
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="index.php?action=tours_show&id=<?= $tour['id'] ?>" class="btn btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                            <a href="index.php?action=tours_edit&id=<?= $tour['id'] ?>" class="btn btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <a href="index.php?action=tours_delete&id=<?= $tour['id'] ?>" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?');" title="Xóa">
                                                <i class="fas fa-trash"></i> Xóa
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Tổng Số Tour</div>
                    <div class="h3 mb-0 text-gray-800"><?= count($tours ?? []) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Đang Hoạt Động</div>
                    <div class="h3 mb-0 text-gray-800">
                        <?php
                        $activeCount = 0;
                        foreach ($tours as $t) {
                            if ($t['status'] == 'active') $activeCount++;
                        }
                        echo $activeCount;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-1">Chỗ Còn Trống</div>
                    <div class="h3 mb-0 text-gray-800">
                        <?php
                        $totalSlots = 0;
                        foreach ($tours as $t) {
                            $totalSlots += $t['available_slots'] ?? 0;
                        }
                        echo $totalSlots;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Tổng Doanh Thu (Dự Kiến)</div>
                    <div class="h3 mb-0 text-gray-800">
                        <?php
                        $totalRevenue = 0;
                        foreach ($tours as $t) {
                            $totalRevenue += ($t['price'] ?? 0) * (($t['max_capacity'] ?? 0) - ($t['available_slots'] ?? 0));
                        }
                        echo number_format($totalRevenue / 1000000, 1) . "M đ";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS & JS -->
<link href="<?= asset('vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
<script src="<?= asset('vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= asset('vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script>
    // Initialize DataTable
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [
                [0, "desc"]
            ],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi",
                "search": "Tìm kiếm:",
                "info": "Hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
                "infoEmpty": "Không có bản ghi nào",
                "paginate": {
                    "first": "Đầu tiên",
                    "last": "Cuối cùng",
                    "next": "Tiếp theo",
                    "previous": "Trước đó"
                }
            }
        });
    });
</script>