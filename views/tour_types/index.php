<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản Lý Loại Tour</h1>
        <a href="index.php?action=tour_types_create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm Loại Tour
        </a>
    </div>

    <!-- Status Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Tour Types Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh Sách Loại Tour</h6>
            <span class="badge badge-primary badge-pill"><?= count($tourTypes ?? []) ?> loại</span>
        </div>
        <div class="card-body">
            <?php if (empty($tourTypes)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Chưa có loại tour nào.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Mã Loại</th>
                                <th width="20%">Tên Loại</th>
                                <th width="30%">Mô Tả</th>
                                <th width="10%">Biểu Tượng</th>
                                <th width="8%">Trạng Thái</th>
                                <th width="12%">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tourTypes as $type): ?>
                                <tr>
                                    <td><strong>#<?= $type['id'] ?></strong></td>
                                    <td>
                                        <code><?= htmlspecialchars($type['code']) ?></code>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($type['name']) ?></strong>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(substr($type['description'] ?? '', 0, 50)) ?>
                                        <?= strlen($type['description'] ?? '') > 50 ? '...' : '' ?>
                                    </td>
                                    <td class="text-center">
                                        <i class="<?= htmlspecialchars($type['icon'] ?? '') ?>" style="font-size: 18px; color: <?= htmlspecialchars($type['color'] ?? '#000') ?>"></i>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($type['is_active']): ?>
                                            <span class="badge badge-success">Hoạt Động</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Không Hoạt Động</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="index.php?action=tour_types_edit&id=<?= $type['id'] ?>" class="btn btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?action=tour_types_toggle&id=<?= $type['id'] ?>" class="btn btn-info" title="Bật/Tắt">
                                                <i class="fas fa-toggle-on"></i>
                                            </a>
                                            <a href="index.php?action=tour_types_delete&id=<?= $type['id'] ?>" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?');" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
