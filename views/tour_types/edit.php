<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh Sửa Loại Tour: <?= htmlspecialchars($tourType['name']) ?></h1>
        <a href="index.php?action=tour_types" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <!-- Display Errors -->
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Lỗi:</strong>
            <ul class="mb-0">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form action="index.php?action=tour_types_update" method="POST">
        <input type="hidden" name="id" value="<?= $tourType['id'] ?>">
        
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông Tin Cơ Bản</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Mã Loại (Không thể sửa)</label>
                            <input type="text" class="form-control" disabled
                                   value="<?= htmlspecialchars($tourType['code']) ?>">
                            <input type="hidden" name="code" value="<?= htmlspecialchars($tourType['code']) ?>">
                        </div>

                        <div class="form-group">
                            <label>Tên Loại <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                   value="<?= htmlspecialchars($tourType['name']) ?>">
                        </div>

                        <div class="form-group">
                            <label>Mô Tả</label>
                            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($tourType['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Hiển Thị</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Biểu Tượng (Icon) <span class="text-danger">*</span></label>
                            <input type="text" name="icon" class="form-control" required
                                   value="<?= htmlspecialchars($tourType['icon']) ?>">
                            <small class="form-text text-muted">Font Awesome icons. <a href="https://fontawesome.com" target="_blank">Xem danh sách</a></small>
                        </div>

                        <div class="form-group">
                            <label>Màu Sắc <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" name="color" class="form-control form-control-color" required
                                       value="<?= htmlspecialchars($tourType['color']) ?>"
                                       style="max-width: 60px;">
                                <input type="text" class="form-control" placeholder="#28a745"
                                       value="<?= htmlspecialchars($tourType['color']) ?>" 
                                       disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Thứ Tự Hiển Thị</label>
                            <input type="number" name="display_order" class="form-control" min="0"
                                   value="<?= $tourType['display_order'] ?>">
                            <small class="form-text text-muted">Số nhỏ hơn hiển thị trước</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" 
                                       name="is_active" value="1" 
                                       <?= $tourType['is_active'] ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="is_active">
                                    Hoạt Động
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Lưu Thay Đổi
                        </button>
                        <a href="index.php?action=tour_types" class="btn btn-secondary btn-block mt-2">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
