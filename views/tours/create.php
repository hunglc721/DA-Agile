<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm Tour Mới</h1>
        <a href="index.php?action=tours" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <form action="index.php?action=tours_store" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Mã Tour <span class="text-danger">*</span></label>
                                <input type="text" name="tour_code" class="form-control" required placeholder="VD: HL001" value="<?= $_SESSION['old']['tour_code'] ?? '' ?>">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Tên Tour <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Nhập tên tour..." value="<?= $_SESSION['old']['name'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Loại Tour <span class="text-danger">*</span></label>
                                <select name="tour_type" class="form-control" required>
                                    <option value="">-- Chọn loại tour --</option>
                                    <?php if (isset($tourTypes) && is_array($tourTypes)): ?>
                                        <?php foreach ($tourTypes as $type): ?>
                                            <option value="<?= $type['code'] ?>" 
                                                    <?= ($_SESSION['old']['tour_type'] ?? 'domestic') == $type['code'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($type['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Danh Mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" 
                                                <?= ($_SESSION['old']['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nhà cung cấp (Supplier)</label>
                            <input type="text" name="supplier" class="form-control" placeholder="Công ty tổ chức / Đối tác..." value="<?= $_SESSION['old']['supplier'] ?? '' ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Điểm đón (Start Location)</label>
                                <input type="text" name="start_location" class="form-control" required value="<?= $_SESSION['old']['start_location'] ?? '' ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Các điểm đến (Destinations)</label>
                                <input type="text" name="destinations" class="form-control" placeholder="Hạ Long, Bãi Cháy..." value="<?= $_SESSION['old']['destinations'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Mô tả chi tiết</label>
                            <textarea name="description" class="form-control" rows="5"><?= $_SESSION['old']['description'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Giá & Vận hành</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control font-weight-bold text-primary" required min="0" value="<?= $_SESSION['old']['price'] ?? '' ?>">
                        </div>

                        <div class="form-group">
                            <label>Giá vốn (Cost Price) <span class="text-danger">*</span></label>
                            <input type="number" name="cost_price" class="form-control text-danger" required min="0" value="<?= $_SESSION['old']['cost_price'] ?? '' ?>">
                            <small class="form-text text-muted">Dùng để tính lợi nhuận (Ẩn với khách).</small>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Thời lượng (Ngày)</label>
                                <input type="number" name="duration" class="form-control" min="1" value="1">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Số chỗ tối đa</label>
                                <input type="number" name="max_capacity" class="form-control" min="1" value="20">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Ảnh Tour (Chọn nhiều)</label>
                            <div class="custom-file">
                                <input type="file" name="images[]" class="custom-file-input" id="customFile" multiple accept="image/*">
                                <label class="custom-file-label" for="customFile">Chọn ảnh...</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Chính sách & Điều khoản</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Chính sách hoàn hủy</label>
                            <textarea name="policy" class="form-control" rows="4" placeholder="Ví dụ: Hủy trước 3 ngày hoàn 100%..."><?= $_SESSION['old']['policy'] ?? '' ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-save"></i> Lưu Tour
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php 
// Xóa session cũ sau khi đã hiển thị
unset($_SESSION['old']); 
?>