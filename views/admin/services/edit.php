<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Sửa Dịch Vụ</h1>
    <a href="<?= url('services?tour_id=' . $selectedTourId) ?>" class="btn btn-secondary">Quay lại</a>
</div>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); endif; ?>

<form action="<?= url('services_update') ?>" method="POST">
    <input type="hidden" name="id" value="<?= $service['id'] ?>">
    <input type="hidden" name="tour_id" value="<?= $selectedTourId ?>">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin dịch vụ</h6>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Tên dịch vụ</label>
                    <input type="text" name="name" class="form-control" value="<?= escape($service['name'] ?? '') ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Chi phí</label>
                    <input type="number" name="amount" class="form-control" min="0" value="<?= (float)($service['amount'] ?? 0) ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nhà cung cấp</label>
                    <input type="text" name="vendor" class="form-control" value="<?= escape($service['vendor'] ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Loại chi phí</label>
                    <input type="text" name="cost_type" class="form-control" value="<?= escape($service['cost_type'] ?? 'service') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Ghi chú</label>
                <input type="text" name="notes" class="form-control" value="<?= escape($service['notes'] ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </div>
</form>

