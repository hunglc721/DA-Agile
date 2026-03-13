<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Danh Sách Phân Bổ Nhân Sự</h1>
    <div>
        <a href="<?php echo url('assignments_create'); ?>" class="btn btn-primary">Tạo phân bổ mới</a>
        <a href="<?php echo url('departures'); ?>" class="btn btn-secondary">Quay lại Khởi Hành</a>
    </div>
</div>

<?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success alert-dismissible fade show" role="alert"><?= $_SESSION['success'] ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div><?php unset($_SESSION['success']); endif; ?>
<?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $_SESSION['error'] ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div><?php unset($_SESSION['error']); endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Phân Bổ Hiện Có</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tour</th>
                        <th>Ngày phân bổ</th>
                        <th>Hướng dẫn viên</th>
                        <th>Trạng thái HDV</th>
                        <th>Trạng thái phân bổ</th>
                        <th>Ghi chú</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assignments)): ?>
                    <tr><td colspan="7" class="text-center text-muted">Chưa có phân bổ nào.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($assignments as $a): ?>
                    <tr>
                        <td>
                            <strong><?= escape($a['tour_name'] ?? 'N/A') ?></strong><br>
                            <small class="text-muted"><?= escape($a['tour_code'] ?? '') ?></small>
                        </td>
                        <td><?= escape($a['assignment_date'] ?? 'N/A') ?></td>
                        <td><?= escape($a['guide_name'] ?? 'N/A') ?></td>
                        <td>
                            <span class="badge badge-<?= ($a['guide_status'] === 'active' ? 'success' : 'secondary') ?>">
                                <?= escape($a['guide_status'] ?? 'inactive') ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?= ($a['assign_status'] === 'assigned' ? 'info' : ($a['assign_status'] === 'completed' ? 'success' : 'warning')) ?>">
                                <?= escape($a['assign_status'] ?? 'pending') ?>
                            </span>
                        </td>
                        <td><?= escape($a['assign_notes'] ?? '-') ?></td>
                        <td>
                            <form action="<?php echo url('departures_unassign'); ?>" method="POST" onsubmit="return confirm('Hủy phân bổ này?');" style="display:inline;">
                                <input type="hidden" name="assign_id" value="<?php echo $a['assign_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hủy</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
