<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Sửa Lịch Khởi Hành</h1>
    <a href="<?php echo url('departures'); ?>" class="btn btn-secondary">Quay lại</a>
</div>

<?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success alert-dismissible fade show" role="alert"><?= $_SESSION['success'] ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div><?php unset($_SESSION['success']); endif; ?>
<?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $_SESSION['error'] ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div><?php unset($_SESSION['error']); endif; ?>

<form action="<?php echo url('departures_update'); ?>" method="POST">
    <input type="hidden" name="id" value="<?php echo $departure['id']; ?>">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Tour</label>
                    <select class="form-control" disabled>
                        <option><?= escape($departure['tour_name'] ?? 'N/A') ?></option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Ngày khởi hành</label>
                    <input type="date" name="departure_date" class="form-control" value="<?= escape($departure['departure_date'] ?? '') ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Sức chứa</label>
                    <input type="number" name="capacity" class="form-control" min="1" value="<?= (int)($departure['capacity'] ?? 0) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Chỗ trống</label>
                    <input type="number" name="available_slots" class="form-control" min="0" value="<?= (int)($departure['available_slots'] ?? 0) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="scheduled" <?= (($departure['status'] ?? '') === 'scheduled' ? 'selected' : '') ?>>Đã lên lịch</option>
                    <option value="ongoing" <?= (($departure['status'] ?? '') === 'ongoing' ? 'selected' : '') ?>>Đang chạy</option>
                    <option value="finished" <?= (($departure['status'] ?? '') === 'finished' ? 'selected' : '') ?>>Đã kết thúc</option>
                    <option value="cancelled" <?= (($departure['status'] ?? '') === 'cancelled' ? 'selected' : '') ?>>Đã hủy</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="<?php echo url('departures'); ?>" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </div>
</form>
