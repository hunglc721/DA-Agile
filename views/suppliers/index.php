<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-building"></i> Quản Lý Nhà Cung Cấp</h1>
        <a href="index.php?action=suppliers_create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle"></i> Thêm Nhà Cung Cấp Mới
        </a>
    </div>

    <!-- FILTER -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="index.php?action=suppliers" method="GET" class="row align-items-end">
                <input type="hidden" name="action" value="suppliers">
                
                <div class="col-md-4">
                    <label><strong>Tìm Kiếm</strong></label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Tên, email, điện thoại..." 
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label><strong>Trạng Thái</strong></label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">-- Tất Cả --</option>
                        <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Hoạt Động</option>
                        <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Không Hoạt Động</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block btn-sm">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="index.php?action=suppliers" class="btn btn-secondary btn-block btn-sm">
                        <i class="fas fa-redo"></i> Đặt Lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- DANH SÁCH -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Danh Sách Nhà Cung Cấp (<?= count($suppliers) ?> nhà)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Tên Nhà Cung Cấp</th>
                            <th width="15%">Liên Hệ</th>
                            <th width="15%">Email</th>
                            <th width="12%">Điện Thoại</th>
                            <th width="8%">Hoa Hồng</th>
                            <th width="10%">Trạng Thái</th>
                            <th width="15%">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($suppliers)): ?>
                            <?php foreach ($suppliers as $supplier): ?>
                            <tr>
                                <td class="font-weight-bold text-primary">#<?= $supplier['id'] ?></td>
                                
                                <td>
                                    <strong><?= htmlspecialchars($supplier['name']) ?></strong>
                                    <?php if (!empty($supplier['description'])): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars(substr($supplier['description'], 0, 50)) ?></small>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-muted small">
                                    <?= htmlspecialchars($supplier['contact_person'] ?? 'N/A') ?>
                                </td>
                                
                                <td class="small">
                                    <a href="mailto:<?= htmlspecialchars($supplier['email'] ?? '') ?>" class="text-primary">
                                        <?= htmlspecialchars($supplier['email'] ?? 'Chưa cập nhật') ?>
                                    </a>
                                </td>
                                
                                <td class="small">
                                    <a href="tel:<?= htmlspecialchars($supplier['phone'] ?? '') ?>" class="text-primary">
                                        <?= htmlspecialchars($supplier['phone'] ?? 'Chưa cập nhật') ?>
                                    </a>
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge badge-info"><?= number_format($supplier['commission_rate'], 2) ?>%</span>
                                </td>
                                
                                <td class="text-center">
                                    <?php if ($supplier['status'] === 'active'): ?>
                                        <span class="badge badge-success">✓ Hoạt Động</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">⏸️ Tạm Dừng</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-center">
                                    <a href="index.php?action=suppliers_show&id=<?= $supplier['id'] ?>" 
                                       class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="index.php?action=suppliers_edit&id=<?= $supplier['id'] ?>" 
                                       class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?action=suppliers_delete&id=<?= $supplier['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn chắc chắn muốn xóa nhà cung cấp này?')" 
                                       title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> Chưa có nhà cung cấp nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
