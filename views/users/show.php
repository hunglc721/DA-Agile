<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Chi Tiết Tài Khoản</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Tài Khoản</h6>
                    <div>
                        <a href="index.php?action=users_edit&id=<?= $user['id'] ?>" class="btn btn-primary btn-sm mr-2">
                            <i class="fas fa-edit mr-1"></i> Chỉnh Sửa
                        </a>
                        <a href="index.php?action=users" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Quay Lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID</th>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                        </tr>
                        <tr>
                            <th>Tên Người Dùng</th>
                            <td><?= htmlspecialchars($user['full_name'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Số Điện Thoại</th>
                            <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Địa Chỉ</th>
                            <td><?= htmlspecialchars($user['address'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Vai Trò</th>
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
                        </tr>
                        <tr>
                            <th>Trạng Thái</th>
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
                        </tr>
                        <tr>
                            <th>Ngày Tạo</th>
                            <td><?= !empty($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : 'N/A' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
