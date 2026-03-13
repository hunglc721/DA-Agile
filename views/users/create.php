<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tạo Tài Khoản Mới</h1>

    <!-- Error Messages -->
    <?php if (isset($_SESSION['errors'])): ?>
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

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông Tin Tài Khoản</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="index.php?action=users_store">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="full_name"><strong>Tên Người Dùng <span class="text-danger">*</span></strong></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                value="<?= htmlspecialchars($_SESSION['old']['full_name'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email"><strong>Email <span class="text-danger">*</span></strong></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone"><strong>Số Điện Thoại</strong></label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                value="<?= htmlspecialchars($_SESSION['old']['phone'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role"><strong>Vai Trò <span class="text-danger">*</span></strong></label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">-- Chọn vai trò --</option>
                                <option value="admin" <?= ($_SESSION['old']['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="guide" <?= ($_SESSION['old']['role'] ?? '') === 'guide' ? 'selected' : '' ?>>Hướng Dẫn Viên</option>
                                <option value="staff" <?= ($_SESSION['old']['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Nhân Viên</option>
                                <option value="customer" <?= ($_SESSION['old']['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Khách Hàng</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password"><strong>Mật Khẩu <span class="text-danger">*</span></strong></label>
                            <input type="password" class="form-control" id="password" name="password" required 
                                placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirm"><strong>Xác Nhận Mật Khẩu <span class="text-danger">*</span></strong></label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required 
                                placeholder="Nhập lại mật khẩu">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address"><strong>Địa Chỉ</strong></label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($_SESSION['old']['address'] ?? '') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status"><strong>Trạng Thái</strong></label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" <?= ($_SESSION['old']['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Hoạt Động</option>
                                <option value="inactive" <?= ($_SESSION['old']['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Vô Hiệu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <a href="index.php?action=users" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Quay Lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Tạo Tài Khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php unset($_SESSION['old']); ?>
