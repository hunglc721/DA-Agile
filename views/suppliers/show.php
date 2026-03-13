<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-building"></i> <?= htmlspecialchars($supplier['name']) ?></h1>
        <div>
            <a href="index.php?action=suppliers_edit&id=<?= $supplier['id'] ?>" class="btn btn-warning mr-2">
                <i class="fas fa-edit"></i> Sửa
            </a>
            <a href="index.php?action=suppliers" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- THÔNG TIN CƠ BẢN -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle"></i> Thông Tin Cơ Bản</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>👤 Người Liên Hệ:</strong> <?= htmlspecialchars($supplier['contact_person'] ?? 'Chưa cập nhật') ?></p>
                            <p class="mb-2"><strong>📧 Email:</strong> 
                                <a href="mailto:<?= htmlspecialchars($supplier['email'] ?? '') ?>">
                                    <?= htmlspecialchars($supplier['email'] ?? 'Chưa cập nhật') ?>
                                </a>
                            </p>
                            <p class="mb-2"><strong>📱 Điện Thoại:</strong> 
                                <a href="tel:<?= htmlspecialchars($supplier['phone'] ?? '') ?>">
                                    <?= htmlspecialchars($supplier['phone'] ?? 'Chưa cập nhật') ?>
                                </a>
                            </p>
                            <p class="mb-2"><strong>🌐 Website:</strong> 
                                <?php if (!empty($supplier['website'])): ?>
                                    <a href="<?= htmlspecialchars($supplier['website']) ?>" target="_blank">
                                        <?= htmlspecialchars($supplier['website']) ?>
                                    </a>
                                <?php else: ?>
                                    Chưa cập nhật
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>📍 Địa Chỉ:</strong> <?= htmlspecialchars($supplier['address'] ?? 'Chưa cập nhật') ?></p>
                            <p class="mb-2"><strong>💰 Hoa Hồng:</strong> <span class="badge badge-success"><?= number_format($supplier['commission_rate'], 2) ?>%</span></p>
                            <p class="mb-2"><strong>📋 Thanh Toán:</strong> <?= htmlspecialchars($supplier['payment_terms'] ?? 'Chưa cập nhật') ?></p>
                            <p class="mb-2"><strong>⏱️ Trạng Thái:</strong> 
                                <span class="badge badge-<?= $supplier['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= $supplier['status'] === 'active' ? '✓ Hoạt Động' : '⏸️ Không Hoạt Động' ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <?php if (!empty($supplier['description'])): ?>
                        <hr>
                        <h6 class="font-weight-bold">Mô Tả:</h6>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($supplier['description'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- DANH SÁCH TOUR -->
            <div class="card shadow">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-list"></i> Tour của Nhà Cung Cấp (<?= count($supplier['tours'] ?? []) ?>)</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($supplier['tours'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%">ID</th>
                                        <th width="35%">Tên Tour</th>
                                        <th width="20%">Mã Tour</th>
                                        <th width="15%">Giá</th>
                                        <th width="12%">Trạng Thái</th>
                                        <th width="8%">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($supplier['tours'] as $tour): ?>
                                        <tr>
                                            <td class="font-weight-bold text-primary">#<?= $tour['id'] ?></td>
                                            <td><?= htmlspecialchars($tour['name']) ?></td>
                                            <td><code><?= htmlspecialchars($tour['tour_code']) ?></code></td>
                                            <td class="font-weight-bold text-success"><?= number_format($tour['price']) ?>đ</td>
                                            <td class="text-center">
                                                <span class="badge badge-<?= $tour['status'] === 'active' ? 'success' : 'warning' ?>">
                                                    <?= $tour['status'] === 'active' ? '✓ Hoạt Động' : '⏸️ Tạm Dừng' ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="index.php?action=tours_detail&id=<?= $tour['id'] ?>" 
                                                   class="btn btn-sm btn-primary" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-inbox"></i> Chưa có tour nào từ nhà cung cấp này
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- THÔNG TIN BỔ SUNG -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-bar"></i> Thống Kê</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-primary font-weight-bold mb-2"><?= count($supplier['tours'] ?? []) ?></h2>
                    <p class="text-muted">Tour đang quản lý</p>
                    <hr>
                    <p class="mb-2"><strong>Hoa Hồng:</strong></p>
                    <h3 class="text-success font-weight-bold"><?= number_format($supplier['commission_rate'], 2) ?>%</h3>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3 bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-calendar-alt"></i> Thông Tin</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>ID:</strong> #<?= $supplier['id'] ?></p>
                    <p class="mb-2"><strong>Tạo:</strong> <?= date('d/m/Y H:i', strtotime($supplier['created_at'])) ?></p>
                    <hr>
                    <p class="mb-2"><strong>Trạng Thái:</strong></p>
                    <span class="badge badge-<?= $supplier['status'] === 'active' ? 'success' : 'warning' ?> w-100 p-2">
                        <?= $supplier['status'] === 'active' ? '✓ Hoạt Động' : '⏸️ Không Hoạt Động' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
