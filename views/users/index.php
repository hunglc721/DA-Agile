<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Quản Lý Tài Khoản</h1>

    <!-- Success Message -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách tài khoản</h6>
            <a href="index.php?action=users_create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> Thêm Tài Khoản Mới
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="form-inline">
                        <input type="hidden" name="action" value="users">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm theo tên hoặc email..." 
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-6">
                    <select name="role" class="form-control" onchange="window.location.href='index.php?action=users&role='+this.value">
                        <option value="">-- Lọc theo vai trò --</option>
                        <option value="admin" <?= ($_GET['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="guide" <?= ($_GET['role'] ?? '') === 'guide' ? 'selected' : '' ?>>Hướng Dẫn Viên</option>
                        <option value="staff" <?= ($_GET['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Nhân Viên</option>
                        <option value="customer" <?= ($_GET['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Khách Hàng</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Tên Người Dùng</th>
                            <th width="20%">Email</th>
                            <th width="15%">Số Điện Thoại</th>
                            <th width="15%">Vai Trò</th>
                            <th width="15%">Trạng Thái</th>
                            <th width="10%">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td><?= htmlspecialchars($user['full_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php
                                            $role = $user['role'] ?? 'customer';
                                            $roleBadge = [
                                                'admin' => '<span class="badge badge-danger">Admin</span>',
                                                'guide' => '<span class="badge badge-info">Hướng Dẫn Viên</span>',
                                                'staff' => '<span class="badge badge-warning">Nhân Viên</span>',
                                                'customer' => '<span class="badge badge-secondary">Khách Hàng</span>'
                                            ];
                                            echo $roleBadge[$role] ?? '<span class="badge badge-secondary">Unknown</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $status = $user['status'] ?? 'active';
                                            if ($status === 'active') {
                                                echo '<span class="badge badge-success">Hoạt Động</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Vô Hiệu</span>';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="index.php?action=users_edit&id=<?= $user['id'] ?>" class="btn btn-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger" onclick="confirmDelete(<?= $user['id'] ?>, '<?= htmlspecialchars($user['full_name']) ?>')" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    Không có tài khoản nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác Nhận Xóa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa tài khoản của <strong id="userName"></strong>?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="users_delete">
                    <input type="hidden" name="id" id="userId">
                    <button type="submit" class="btn btn-danger">Xóa Tài Khoản</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(userId, userName) {
    document.getElementById('userId').value = userId;
    document.getElementById('userName').textContent = userName;
    $('#deleteModal').modal('show');
}
</script>
