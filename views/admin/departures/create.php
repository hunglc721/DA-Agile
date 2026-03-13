<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tạo Lịch Khởi Hành</h1>
    <a href="<?php echo url('departures'); ?>" class="btn btn-secondary">Quay lại</a>
</div>

<?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $_SESSION['error'] ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div><?php unset($_SESSION['error']); endif; ?>

<form action="<?php echo url('departures_store'); ?>" method="POST">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Tour <span class="text-danger">*</span></label>
                    <select name="tour_id" class="form-control" required>
                        <option value="">-- Chọn tour --</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?php echo $t['id']; ?>"><?= escape($t['name'] ?? 'N/A') ?> (<?= escape($t['tour_code'] ?? '') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Ngày khởi hành <span class="text-danger">*</span></label>
                    <input type="date" name="departure_date" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Sức chứa <span class="text-danger">*</span></label>
                    <input type="number" name="capacity" class="form-control" min="1" required placeholder="Nhập số chỗ">
                </div>
                <div class="form-group col-md-6">
                    <label>Chỗ trống</label>
                    <input type="number" name="available_slots" class="form-control" min="0" placeholder="Để trống = cùng sức chứa">
                </div>
            </div>
            <div class="form-group">
                <label>Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="scheduled">Đã lên lịch</option>
                    <option value="ongoing">Đang chạy</option>
                    <option value="finished">Đã kết thúc</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Tạo</button>
                <a href="<?php echo url('departures'); ?>" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </div>
</form>
