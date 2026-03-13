<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tạo Phân Bổ Mới</h1>
    <a href="<?php echo url('assignments'); ?>" class="btn btn-secondary">Quay lại</a>
</div>

<?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Lỗi:</strong>
        <ul class="mb-0">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['errors']); ?>
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

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thông Tin Phân Bổ</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo url('assignments_store'); ?>">
            <div class="form-group">
                <label for="departure_id"><strong>Lịch Khởi Hành <span class="text-danger">*</span></strong></label>
                <select class="form-control" id="departure_id" name="departure_id" required>
                    <option value="">-- Chọn lịch khởi hành --</option>
                    <?php foreach ($departures as $dep): ?>
                        <option value="<?= $dep['id'] ?>">
                            <?= escape($dep['tour_name'] ?? '') ?> 
                            (<?= escape($dep['tour_code'] ?? '') ?>) 
                            - <?= escape($dep['departure_date'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">Chỉ hiển thị lịch khởi hành đang được lên kế hoạch</small>
            </div>

            <div class="form-group">
                <label for="guide_id"><strong>Hướng Dẫn Viên <span class="text-danger">*</span></strong></label>
                <select class="form-control" id="guide_id" name="guide_id" required>
                    <option value="">-- Chọn hướng dẫn viên --</option>
                    <?php foreach ($guides as $guide): ?>
                        <option value="<?= $guide['id'] ?>">
                            <?= escape($guide['guide_name'] ?? '') ?> 
                            (<?= escape($guide['phone'] ?? '') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">Chỉ hiển thị hướng dẫn viên đang hoạt động</small>
            </div>

            <div class="form-group">
                <label for="notes"><strong>Ghi Chú</strong></label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Nhập ghi chú (tùy chọn)"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu Phân Bổ
                </button>
                <a href="<?php echo url('assignments'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
