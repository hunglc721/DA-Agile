<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Quản Lý Dịch Vụ Tour</h1>
</div>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Chọn tour</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= url('services') ?>">
            <div class="form-row">
                <div class="form-group col-md-8">
                    <select name="tour_id" class="form-control">
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ($selectedTourId==$t['id'])?'selected':'' ?>><?= escape($t['name']) ?> (<?= escape($t['tour_code'] ?? '') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <button class="btn btn-info w-100" type="submit">Xem dịch vụ</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ($selectedTourId !== null && $selectedTourId !== ''): ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thêm dịch vụ</h6>
    </div>
    <div class="card-body">
        <form action="<?= url('services_store') ?>" method="POST">
            <input type="hidden" name="tour_id" value="<?= $selectedTourId ?>">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Tên dịch vụ" required>
                </div>
                <div class="form-group col-md-3">
                    <input type="number" name="amount" class="form-control" placeholder="Chi phí" min="0" required>
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="vendor" class="form-control" placeholder="Nhà cung cấp">
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="notes" class="form-control" placeholder="Ghi chú">
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Thêm</button>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách dịch vụ</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên dịch vụ</th>
                        <th>Nhà cung cấp</th>
                        <th>Chi phí</th>
                        <th>Ghi chú</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($services)): ?>
                    <tr>
                        <td colspan="5" class="text-center">Chưa có dịch vụ nào cho tour này.</td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($services as $s): ?>
                    <tr>
                        <td><?= escape($s['name'] ?? '') ?></td>
                        <td><?= escape($s['vendor'] ?? '') ?></td>
                        <td><?= number_format((float)($s['amount'] ?? 0)) ?></td>
                        <td><?= escape($s['notes'] ?? '') ?></td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="<?= url('services_edit?id=' . $s['id'] . '&tour_id=' . $selectedTourId) ?>">Sửa</a>
                            <form action="<?= url('services_delete') ?>" method="POST" onsubmit="return confirm('Xóa dịch vụ?');">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <input type="hidden" name="tour_id" value="<?= $selectedTourId ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
