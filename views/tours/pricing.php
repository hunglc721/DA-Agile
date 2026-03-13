<div class="container-fluid">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-800"><i class="fas fa-tag"></i> Bảng Giá Tour</h1>
            <p class="text-muted mb-0">
                <span class="badge badge-info"><?= htmlspecialchars($tour['tour_code'] ?? 'N/A') ?></span>
                <span class="badge badge-primary" style="margin-left: 10px;"><?= htmlspecialchars($tour['name'] ?? 'N/A') ?></span>
            </p>
        </div>
        <a href="index.php?action=tours_detail&id=<?= $tour['id'] ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại Chi Tiết Tour
        </a>
    </div>

    <div class="row">
        <!-- DANH SÁCH GIÁ -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-list"></i> Danh Sách Bảng Giá</h6>
                    <button class="btn btn-sm btn-light" onclick="location.href='index.php?action=pricing_create&tour_id=<?= $tour['id'] ?>'">
                        <i class="fas fa-plus"></i> Thêm Giá Mới
                    </button>
                </div>
                <div class="card-body">
                    <?php if (!empty($pricings)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="20%">Tên Bảng Giá</th>
                                        <th width="15%">Tối Thiểu</th>
                                        <th width="15%">Tối Đa</th>
                                        <th width="15%">Giá Cơ Bản</th>
                                        <th width="10%">Giảm %</th>
                                        <th width="12%">Giá Cuối</th>
                                        <th width="13%">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pricings as $pricing): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($pricing['name']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($pricing['description'] ?? '') ?></small>
                                            </td>
                                            <td><?= $pricing['min_group_size'] ?> người</td>
                                            <td><?= $pricing['max_group_size'] ? $pricing['max_group_size'] . ' người' : '∞' ?></td>
                                            <td class="font-weight-bold text-primary">
                                                <?= number_format($pricing['base_price']) ?>đ
                                            </td>
                                            <td class="text-center">
                                                <?php if ($pricing['discount_percent'] > 0): ?>
                                                    <span class="badge badge-warning"><?= $pricing['discount_percent'] ?>%</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">0%</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="font-weight-bold text-success">
                                                <?php $finalPrice = $pricing['base_price'] * (1 - $pricing['discount_percent'] / 100); ?>
                                                <?= number_format($finalPrice) ?>đ
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info" onclick="editPricing(<?= $pricing['id'] ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deletePricing(<?= $pricing['id'] ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Chưa có bảng giá nào
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- THÔNG TIN GIẢN LƯỢC -->
        <div class="col-lg-4">
            <!-- GIÁ HIỆN TẠI -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-coins"></i> Giá Hiện Tại</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h2 class="text-success font-weight-bold">
                            <?= number_format($tour['price'] ?? 0) ?><span style="font-size: 16px;">đ</span>
                        </h2>
                        <small class="text-muted">Giá bán</small>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h4 class="text-info font-weight-bold">
                            <?= number_format($tour['cost_price'] ?? 0) ?><span style="font-size: 14px;">đ</span>
                        </h4>
                        <small class="text-muted">Giá chi phí</small>
                    </div>
                </div>
            </div>

            <!-- THỐNG KÊ GIÁ -->
            <div class="card shadow">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-bar"></i> Thống Kê</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Tổng Bảng Giá:</strong> 
                        <span class="badge badge-primary"><?= count($pricings) ?></span>
                    </p>
                    <p class="mb-2">
                        <strong>Giá Min:</strong> 
                        <span class="text-success font-weight-bold">
                            <?= number_format(min(array_column($pricings, 'base_price'))) ?>đ
                        </span>
                    </p>
                    <p class="mb-0">
                        <strong>Giá Max:</strong> 
                        <span class="text-danger font-weight-bold">
                            <?= number_format(max(array_column($pricings, 'base_price'))) ?>đ
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editPricing(id) {
    location.href = 'index.php?action=pricing_edit&id=' + id;
}

function deletePricing(id) {
    if (confirm('Bạn chắc chắn muốn xóa bảng giá này?')) {
        location.href = 'index.php?action=pricing_delete&id=' + id;
    }
}
</script>
