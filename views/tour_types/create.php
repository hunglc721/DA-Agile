<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm Loại Tour</h1>
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

    <form action="index.php?action=tour_types_store" method="POST">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông Tin Cơ Bản</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Mã Loại <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" required 
                                   placeholder="VD: domestic, international, custom"
                                   value="<?= htmlspecialchars($_SESSION['old']['code'] ?? '') ?>">
                            <small class="form-text text-muted">Ví dụ: domestic, international, custom (không được thay đổi sau khi tạo)</small>
                        </div>

                        <div class="form-group">
                            <label>Tên Loại <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                   placeholder="VD: Tour Trong Nước"
                                   value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label>Mô Tả</label>
                            <textarea name="description" class="form-control" rows="4"
                                    placeholder="Mô tả chi tiết về loại tour này..."><?= htmlspecialchars($_SESSION['old']['description'] ?? '') ?></textarea>
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
                                   placeholder="VD: fas fa-map-marker-alt"
                                   value="<?= htmlspecialchars($_SESSION['old']['icon'] ?? '') ?>">
                            <small class="form-text text-muted">Font Awesome icons. <a href="https://fontawesome.com" target="_blank">Xem danh sách</a></small>
                        </div>

                        <div class="form-group">
                            <label>Màu Sắc <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" name="color" class="form-control form-control-color" required
                                       value="<?= htmlspecialchars($_SESSION['old']['color'] ?? '#28a745') ?>"
                                       style="max-width: 60px;">
                                <input type="text" class="form-control" placeholder="#28a745"
                                       value="<?= htmlspecialchars($_SESSION['old']['color'] ?? '#28a745') ?>" 
                                       disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Thứ Tự Hiển Thị</label>
                            <input type="number" name="display_order" class="form-control" min="0"
                                   value="<?= htmlspecialchars($_SESSION['old']['display_order'] ?? 0) ?>">
                            <small class="form-text text-muted">Số nhỏ hơn hiển thị trước</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" 
                                       name="is_active" value="1" checked>
                                <label class="custom-control-label" for="is_active">
                                    Hoạt Động
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Lưu
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

<?php unset($_SESSION['old']); ?>
