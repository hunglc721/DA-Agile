<div class="container-fluid">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-800"><?= htmlspecialchars($tour['name']) ?></h1>
            <p class="text-muted mb-0">
                <span class="badge badge-info"><?= htmlspecialchars($tour['tour_code']) ?></span>
                <span class="badge badge-<?= $tour['tour_type'] === 'domestic' ? 'primary' : 'danger' ?>" style="margin-left: 10px;">
                    <?= $tour['tour_type'] === 'domestic' ? '🇻🇳 Trong Nước' : '✈️ Quốc Tế' ?>
                </span>
            </p>
        </div>
        <a href="index.php?action=tours" class="btn btn-secondary">
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

                    <hr>

                    <div class="alert alert-light">
                        <p class="mb-1"><strong>💡 Giá Đặc Biệt:</strong></p>
                        <ul class="mb-0 small">
                            <li>Giá trẻ em (2-10 tuổi): <strong><?= number_format(($tour['price'] ?? 0) * 0.5) ?>đ</strong> (50% giá bán lẻ)</li>
                            <li>Giá trẻ sơ sinh (&lt;2 tuổi): <strong>Miễn phí</strong></li>
                            <li>Giá nhóm (10+ người): Liên hệ để được tư vấn</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- LỊCH TRÌNH CHI TIẾT -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-warning text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-map-marked-alt"></i> LỊCH TRÌNH CHI TIẾT</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($itinerary)): ?>
                        <div class="timeline">
                            <?php foreach ($itinerary as $idx => $day): ?>
                                <div class="timeline-item mb-4">
                                    <div class="timeline-marker" style="width: 40px; height: 40px; background: #007bff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 10px;">
                                        <?= $day['day_number'] ?>
                                    </div>
                                    <div class="timeline-content bg-light p-3 rounded">
                                        <h6 class="font-weight-bold mb-2">
                                            <i class="fas fa-calendar"></i> Ngày <?= $day['day_number'] ?>: <?= htmlspecialchars($day['title']) ?>
                                        </h6>
                                        <p class="mb-2"><?= htmlspecialchars($day['activity']) ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-utensils"></i> Ăn uống: <strong><?= htmlspecialchars($day['meals']) ?></strong>
                                        </small>
                                        <?php if (!empty($day['accommodation'])): ?>
                                            <br><small class="text-muted"><i class="fas fa-bed"></i> Lưu trú: <strong><?= htmlspecialchars($day['accommodation']) ?></strong></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Chưa có lịch trình chi tiết
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CHÍNH SÁCH TOUR -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-file-contract"></i> CHÍNH SÁCH TOUR</h6>
                </div>
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs mb-3" id="policyTabs">
                            <a class="nav-link active" id="policy-included-tab" data-toggle="tab" href="#policy-included">Bao Gồm</a>
                            <a class="nav-link" id="policy-excluded-tab" data-toggle="tab" href="#policy-excluded">Không Bao Gồm</a>
                            <a class="nav-link" id="policy-terms-tab" data-toggle="tab" href="#policy-terms">Điều Khoản</a>
                            <a class="nav-link" id="policy-cancel-tab" data-toggle="tab" href="#policy-cancel">Hủy & Hoàn Tiền</a>
                        </div>
                    </nav>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="policy-included">
                            <h6 class="font-weight-bold mb-3">Những Gì Bao Gồm:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Vận chuyển xe cao cấp</li>
                                <li><i class="fas fa-check text-success"></i> Hướng dẫn viên chuyên nghiệp</li>
                                <li><i class="fas fa-check text-success"></i> Khách sạn 3-4 sao</li>
                                <li><i class="fas fa-check text-success"></i> Ăn sáng, trưa, chiều</li>
                                <li><i class="fas fa-check text-success"></i> Vé tham quan các điểm du lịch</li>
                                <li><i class="fas fa-check text-success"></i> Bảo hiểm du lịch</li>
                            </ul>
                        </div>

                        <div class="tab-pane fade" id="policy-excluded">
                            <h6 class="font-weight-bold mb-3">Không Bao Gồm:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-times text-danger"></i> Vé máy bay</li>
                                <li><i class="fas fa-times text-danger"></i> Visa / Hộ chiếu</li>
                                <li><i class="fas fa-times text-danger"></i> Chi phí cá nhân (phòng riêng, minibar, ...)</li>
                                <li><i class="fas fa-times text-danger"></i> Tiền tip hướng dẫn viên, tài xế</li>
                                <li><i class="fas fa-times text-danger"></i> Các hoạt động không nằm trong chương trình</li>
                            </ul>
                        </div>

                        <div class="tab-pane fade" id="policy-terms">
                            <h6 class="font-weight-bold mb-3">Điều Khoản & Điều Kiện:</h6>
                            <p><?= nl2br(htmlspecialchars($tour['policy'] ?? 'Chưa cập nhật')) ?></p>
                        </div>

                        <div class="tab-pane fade" id="policy-cancel">
                            <h6 class="font-weight-bold mb-3">Chính Sách Hủy & Hoàn Tiền:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Thời Gian Hủy</th>
                                            <th>Phí Hủy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Trước 30 ngày</td>
                                            <td class="text-success">Hoàn 100%</td>
                                        </tr>
                                        <tr>
                                            <td>Từ 15-29 ngày</td>
                                            <td class="text-warning">Hoàn 50%</td>
                                        </tr>
                                        <tr>
                                            <td>Từ 7-14 ngày</td>
                                            <td class="text-warning">Hoàn 25%</td>
                                        </tr>
                                        <tr>
                                            <td>Dưới 7 ngày</td>
                                            <td class="text-danger">Không hoàn</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MÔ TẢ CHI TIẾT -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-dark text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-align-left"></i> MÔ TẢ CHI TIẾT</h6>
                </div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($tour['description'] ?? 'Chưa cập nhật mô tả')) ?>
                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <!-- THÔNG TIN NHÀ CUNG CẤP -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-building"></i> NHÀ CUNG CẤP</h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold mb-2"><?= htmlspecialchars($tour['supplier'] ?? 'Chưa cập nhật') ?></h6>
                    <p class="small text-muted mb-2">
                        <i class="fas fa-phone"></i> <?= htmlspecialchars($tour['supplier_phone'] ?? 'N/A') ?>
                    </p>
                    <p class="small text-muted mb-3">
                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($tour['supplier_email'] ?? 'N/A') ?>
                    </p>
                    <div class="alert alert-info small mb-0">
                        <strong>Đánh giá:</strong> ⭐⭐⭐⭐⭐ (4.8/5 từ 120 đánh giá)
                    </div>
                </div>
            </div>

            <!-- THỐNG KÊ ĐẶT TOUR -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-bar"></i> THỐNG KÊ</h6>
                </div>
                <div class="card-body text-center">
                    <p class="mb-2">
                        <strong class="h5 text-success"><?= $tour['available_slots'] ?? 0 ?></strong><br>
                        <small class="text-muted">Chỗ Còn Trống</small>
                    </p>
                    <p class="mb-2">
                        <strong class="h5 text-info">245</strong><br>
                        <small class="text-muted">Khách Đã Đặt</small>
                    </p>
                    <p class="mb-0">
                        <strong class="h5 text-warning">38</strong><br>
                        <small class="text-muted">Đánh Giá</small>
                    </p>
                </div>
            </div>

            <!-- BOOKING FORM -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-shopping-cart"></i> ĐẶT TOUR NGAY</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=bookings_store" method="POST">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        <input type="hidden" name="booking_type" value="retail">
                        
                        <div class="form-group">
                            <label class="font-weight-bold small mb-2">Chọn Khách Hàng</label>
                            <select name="user_id" class="form-control form-control-sm" required>
                                <option value="">-- Chọn khách hàng --</option>
                                <?php if (!empty($customers)): ?>
                                    <?php foreach ($customers as $cust): ?>
                                        <option value="<?= $cust['id'] ?>">
                                            <?= htmlspecialchars($cust['full_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold small mb-2">Số Người</label>
                            <input type="number" name="number_of_people" class="form-control form-control-sm" 
                                   min="1" max="<?= $tour['available_slots'] ?? 50 ?>" value="1" required>
                        </div>

                        <div class="alert alert-info small mb-3">
                            <strong>Tổng Tiền:</strong>
                            <h5 class="mb-0 text-success font-weight-bold">
                                <span id="totalPrice"><?= number_format($tour['price'] ?? 0) ?></span>đ
                            </h5>
                        </div>

                        <button type="submit" class="btn btn-danger btn-block font-weight-bold">
                            <i class="fas fa-check-circle"></i> ĐẶT TOUR
                        </button>
                    </form>

                    <a href="index.php?action=bookings_create" class="btn btn-secondary btn-block btn-sm mt-2">
                        <i class="fas fa-edit"></i> Đặt Đoàn
                    </a>
                </div>
            </div>

            <!-- LIÊN HỆ HỖ TRỢ -->
            <div class="card shadow">
                <div class="card-header py-3 bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-headset"></i> HỖ TRỢ</h6>
                </div>
                <div class="card-body text-center small">
                    <p class="mb-2">
                        <i class="fas fa-phone text-success"></i> <strong>1900 XXXX</strong><br>
                        <small class="text-muted">Hỗ trợ 24/7</small>
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-envelope text-primary"></i> <strong>support@tourism.vn</strong><br>
                        <small class="text-muted">Trả lời trong 1 giờ</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cập nhật tổng tiền khi thay đổi số người
document.querySelector('input[name="number_of_people"]').addEventListener('change', function() {
    const price = <?= $tour['price'] ?? 0 ?>;
    const people = this.value;
    const total = price * people;
    document.getElementById('totalPrice').textContent = total.toLocaleString('vi-VN');
});
</script>