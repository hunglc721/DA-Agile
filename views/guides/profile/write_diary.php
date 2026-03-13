<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8">
            <!-- Form Viết Nhật Ký -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">
                        <i class="fas fa-pen-fancy"></i> Viết Nhật Ký Tour: <?= htmlspecialchars($tour['name'] ?? '') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success'] ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error'] ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=guide_save_diary">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

                        <div class="form-group">
                            <label for="diary_type"><strong>Loại Nhật Ký <span class="text-danger">*</span></strong></label>
                            <select class="form-control" id="diary_type" name="diary_type" required>
                                <option value="daily">Hàng Ngày</option>
                                <option value="incident">Sự Cố</option>
                                <option value="feedback">Phản Hồi Khách</option>
                                <option value="note">Ghi Chú</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title"><strong>Tiêu Đề <span class="text-danger">*</span></strong></label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề nhật ký" required>
                        </div>

                        <div class="form-group">
                            <label for="content"><strong>Nội Dung <span class="text-danger">*</span></strong></label>
                            <textarea class="form-control" id="content" name="content" rows="8" placeholder="Nhập nội dung nhật ký..." required></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu Nhật Ký
                            </button>
                            <a href="index.php?action=guide_assigned_tours" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Danh Sách Nhật Ký Gần Đây -->
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0">
                        <i class="fas fa-list"></i> Nhật Ký Gần Đây (<?= count($diaries) ?>)
                    </h6>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <?php if (!empty($diaries)): ?>
                        <?php foreach ($diaries as $diary): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($diary['title'] ?? '') ?></h6>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-clock"></i> 
                                        <?= date('d/m/Y H:i', strtotime($diary['created_at'] ?? '')) ?>
                                    </small>
                                    <small class="text-info d-block">
                                        <i class="fas fa-tag"></i> 
                                        <?php 
                                            $types = [
                                                'daily' => 'Hàng Ngày',
                                                'incident' => 'Sự Cố',
                                                'feedback' => 'Phản Hồi',
                                                'note' => 'Ghi Chú'
                                            ];
                                            echo $types[$diary['diary_type'] ?? 'note'] ?? 'Ghi Chú';
                                        ?>
                                    </small>
                                </div>
                            </div>
                            <p class="mb-0 small mt-2">
                                <?= nl2br(htmlspecialchars(substr($diary['content'], 0, 100))) ?>
                                <?= strlen($diary['content']) > 100 ? '...' : '' ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> Chưa có nhật ký nào.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thông Tin Tour -->
            <div class="card shadow mt-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="m-0">
                        <i class="fas fa-map"></i> Thông Tin Tour
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Tên:</strong><br>
                        <?= htmlspecialchars($tour['name'] ?? '') ?>
                    </p>
                    <p class="mb-2">
                        <strong>Mã:</strong><br>
                        <?= htmlspecialchars($tour['tour_code'] ?? '') ?>
                    </p>
                    <p class="mb-2">
                        <strong>Thời Gian:</strong><br>
                        <?= htmlspecialchars($tour['duration'] ?? '') ?> ngày
                    </p>
                    <p class="mb-0">
                        <strong>Giá:</strong><br>
                        <span class="text-success font-weight-bold">
                            <?= number_format($tour['price'] ?? 0, 0, ',', '.') ?> VND
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
