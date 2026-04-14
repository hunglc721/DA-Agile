<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="<?= asset('img/undraw_profile.svg') ?>" class="rounded-circle mb-3" width="140" height="140" alt="Avatar">
                    <h4 class="card-title mb-0"><?= htmlspecialchars($guide['full_name'] ?? 'N/A') ?></h4>
                    <p class="text-muted mb-2">Hướng dẫn viên</p>
                    <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($guide['email'] ?? 'N/A') ?></p>
                    <p class="mb-1"><strong>Điện thoại:</strong> <?= htmlspecialchars($guide['phone'] ?? 'N/A') ?></p>
                    <p class="mb-1"><strong>Mã giấy phép:</strong> <?= htmlspecialchars($guide['license_number'] ?? 'N/A') ?></p>
                    <p class="mb-1"><strong>Kinh nghiệm:</strong> <?= htmlspecialchars($guide['experience_years'] ?? '0') ?> năm</p>
                    <p class="mb-1"><strong>Ngôn ngữ:</strong> <?= htmlspecialchars($guide['languages'] ?? 'Chưa cập nhật') ?></p>
                    <p class="mb-1"><strong>Chuyên môn:</strong> <?= htmlspecialchars($guide['specialties'] ?? 'Chưa cập nhật') ?></p>
                    <p class="mb-1"><strong>Trạng thái:</strong> <?= htmlspecialchars(ucfirst($guide['status'] ?? 'unknown')) ?></p>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h5 class="card-title">Tóm tắt</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Tour được phân công
                            <span class="badge badge-primary badge-pill"><?= $assigned_tours_count ?? 0 ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Tour đã hoàn thành
                            <span class="badge badge-success badge-pill"><?= $completed_tours_count ?? 0 ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Thông báo
                            <span class="badge badge-warning badge-pill"><?= $notifications_count ?? 0 ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Giới thiệu</h5>
                </div>
                <div class="card-body">
                    <p>Đây là trang cá nhân của hướng dẫn viên. Bạn có thể theo dõi thông tin cá nhân, kinh nghiệm và các tour đang được phân công.</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Tour Được Phân Công</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($assignedTours)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tên Tour</th>
                                        <th>Ngày phân công</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($assignedTours as $tour): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tour['name']) ?></td>
                                            <td><?= htmlspecialchars($tour['assignment_date']) ?></td>
                                            <td><?= htmlspecialchars(ucfirst($tour['assignment_status'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Hiện tại chưa có tour phân công.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>