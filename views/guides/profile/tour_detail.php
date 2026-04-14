<div class="container-fluid">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-800"><?= htmlspecialchars($tour['name']) ?></h1>
            <p class="text-muted mb-0">
                <span class="badge badge-info"><?= htmlspecialchars($tour['tour_code'] ?? 'N/A') ?></span>
                <span class="badge badge-<?= $tour['tour_type'] === 'domestic' ? 'primary' : 'danger' ?>" style="margin-left: 10px;">
                    <?= $tour['tour_type'] === 'domestic' ? '🇻🇳 Trong Nước' : '✈️ Quốc Tế' ?>
                </span>
            </p>
        </div>
        <a href="index.php?action=guide_assigned_tours" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại
        </a>
    </div>

    <div class="row">
        <!-- MAIN CONTENT -->
        <div class="col-lg-8">
            <!-- HÌNH ẢNH -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-images"></i> HÌNH ẢNH TOUR</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($images)): ?>
                        <!-- Main Image -->
                        <div class="mb-3">
                            <img id="mainImage" src="<?= htmlspecialchars($images[0]['image_path'] ?? '') ?>" 
                                 alt="Tour Image" class="img-fluid rounded" style="max-height: 400px; width: 100%; object-fit: cover;">
                        </div>
                        
                        <!-- Thumbnails -->
                        <div class="row">
                            <?php foreach ($images as $idx => $img): ?>
                                <?php if (!empty($img['image_path'] ?? $img['image_url'] ?? '')): ?>
                                <div class="col-3 mb-2">
                                    <?php $imageSrc = $img['image_path'] ?? $img['image_url'] ?? ''; ?>
                                    <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                         alt="Thumbnail <?= $idx + 1 ?>" 
                                         class="img-thumbnail cursor-pointer" 
                                         onclick="document.getElementById('mainImage').src='<?= htmlspecialchars($imageSrc) ?>'"
                                         style="cursor: pointer; height: 80px; width: 100%; object-fit: cover;">
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-image"></i> Chưa có hình ảnh tour
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- THÔNG TIN CƠ BẢN -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle"></i> THÔNG TIN CƠ BẢN</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>📍 Danh Mục:</strong> <?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?></p>
                            <p class="mb-2"><strong>⏱️ Thời Gian:</strong> <?= htmlspecialchars($tour['duration'] ?? 'N/A') ?></p>
                            <p class="mb-2"><strong>👥 Nhóm Tối Thiểu:</strong> <?= $tour['min_group_size'] ?? 'N/A' ?> người</p>
                            <p class="mb-2"><strong>🏨 Loại Lưu Trú:</strong> <?= htmlspecialchars($tour['accommodation_type'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>🎫 Mã Tour:</strong> <code><?= htmlspecialchars($tour['tour_code'] ?? 'N/A') ?></code></p>
                            <p class="mb-2"><strong>📅 Ngày Khởi Hành:</strong> <?= date('d/m/Y', strtotime($tour['start_date'] ?? date('Y-m-d'))) ?></p>
                            <p class="mb-2"><strong>🪑 Còn Lại:</strong> <strong class="text-success"><?= $tour['available_slots'] ?? 0 ?></strong> chỗ</p>
                            <p class="mb-2"><strong>📊 Trạng Thái:</strong> 
                                <span class="badge badge-<?= $tour['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= $tour['status'] === 'active' ? '✓ Hoạt Động' : '⏸️ Tạm Dừng' ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mt-3">
                        <p class="mb-2"><strong>📝 Mô Tả:</strong></p>
                        <p class="text-justify"><?= htmlspecialchars($tour['description'] ?? 'Không có mô tả') ?></p>
                    </div>
                </div>
            </div>

            <!-- GIÁ VÀ CHI PHÍ -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-coins"></i> GIÁ VÀ CHI PHÍ</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center border-right">
                            <h6 class="text-muted text-uppercase small mb-2">Giá Bán Lẻ</h6>
                            <h3 class="text-success font-weight-bold">
                                <?= number_format($tour['price'] ?? 0) ?><span style="font-size: 14px;">đ</span>
                            </h3>
                            <small class="text-muted">/người</small>
                        </div>
                        <div class="col-md-4 text-center border-right">
                            <h6 class="text-muted text-uppercase small mb-2">Giá Chi Phí</h6>
                            <h3 class="text-info font-weight-bold">
                                <?= number_format($tour['cost_price'] ?? 0) ?><span style="font-size: 14px;">đ</span>
                            </h3>
                            <small class="text-muted">/người</small>
                        </div>
                        <div class="col-md-4 text-center">
                            <h6 class="text-muted text-uppercase small mb-2">Lợi Nhuận</h6>
                            <h3 class="text-warning font-weight-bold">
                                <?= number_format(($tour['price'] ?? 0) - ($tour['cost_price'] ?? 0)) ?><span style="font-size: 14px;">đ</span>
                            </h3>
                            <small class="text-muted">/người</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LỊCH TRÌNH TOUR -->
            <?php if (!empty($itinerary)): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-route"></i> LỊCH TRÌNH TOUR</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($itinerary as $idx => $day): ?>
                        <div class="card mb-3 border-left border-left-<?= ['success', 'info', 'primary', 'warning', 'danger'][($idx % 5)] ?>">
                            <div class="card-body">
                                <h6 class="font-weight-bold mb-2">
                                    <i class="fas fa-circle text-<?= ['success', 'info', 'primary', 'warning', 'danger'][($idx % 5)] ?>" style="font-size: 8px; margin-right: 8px;"></i>
                                    Ngày <?= $day['day_number'] ?>: <?= htmlspecialchars($day['title'] ?? '') ?>
                                </h6>
                                <p class="mb-2"><strong>Hoạt Động:</strong> <?= htmlspecialchars($day['activity'] ?? '') ?></p>
                                <?php if (!empty($day['meals'])): ?>
                                <p class="mb-0"><strong>Bữa Ăn:</strong> <?= htmlspecialchars($day['meals']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <!-- DANH SÁCH KHÁCH HÀNG -->
            <?php if (!empty($customers)): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-users"></i> DANH SÁCH KHÁCH HÀNG (<?= count($customers) ?>)</h6>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <div class="list-group">
                        <?php foreach ($customers as $customer): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($customer['customer_name'] ?? 'N/A') ?></h6>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($customer['customer_email'] ?? 'N/A') ?>
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-phone"></i> <?= htmlspecialchars($customer['customer_phone'] ?? 'N/A') ?>
                                    </small>
                                </div>
                                <span class="badge badge-<?= $customer['booking_status'] === 'confirmed' ? 'success' : 'warning' ?>">
                                    <?= $customer['booking_status'] === 'confirmed' ? '✓ Đã Xác Nhận' : '⏳ Chờ Xác Nhận' ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- NÚT HÀNH ĐỘNG -->
            <div class="card shadow">
                <div class="card-header py-3 bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-tasks"></i> HÀNH ĐỘNG</h6>
                </div>
                <div class="card-body">
                    <a href="index.php?action=guide_write_diary&tour_id=<?= $tour['id'] ?>" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-pencil-alt"></i> Viết Nhật Ký
                    </a>
                    <a href="index.php?action=guide_itinerary&tour_id=<?= $tour['id'] ?>" class="btn btn-info btn-block">
                        <i class="fas fa-map"></i> Xem Lịch Trình
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

