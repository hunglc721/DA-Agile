<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-edit"></i> Chỉnh Sửa Nhà Cung Cấp</h1>
        <a href="index.php?action=suppliers" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle"></i> Thông Tin Nhà Cung Cấp</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=suppliers_update" method="POST">
                        <input type="hidden" name="id" value="<?= $supplier['id'] ?>">

                        <!-- Tên Nhà Cung Cấp -->
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Tên Nhà Cung Cấp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($supplier['name']) ?>" required>
                        </div>

                        <!-- Người Liên Hệ -->
                        <div class="form-group">
                            <label for="contact_person" class="font-weight-bold">Người Liên Hệ</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" 
                                   value="<?= htmlspecialchars($supplier['contact_person'] ?? '') ?>">
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="font-weight-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($supplier['email'] ?? '') ?>">
                        </div>

                        <!-- Điện Thoại -->
                        <div class="form-group">
                            <label for="phone" class="font-weight-bold">Điện Thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($supplier['phone'] ?? '') ?>">
                        </div>

                        <!-- Địa Chỉ -->
                        <div class="form-group">
                            <label for="address" class="font-weight-bold">Địa Chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($supplier['address'] ?? '') ?></textarea>
                        </div>

                        <!-- Website -->
                        <div class="form-group">
                            <label for="website" class="font-weight-bold">Website</label>
                            <input type="url" class="form-control" id="website" name="website" 
                                   value="<?= htmlspecialchars($supplier['website'] ?? '') ?>">
                        </div>

                        <!-- Mô Tả -->
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">Mô Tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($supplier['description'] ?? '') ?></textarea>
                        </div>

                        <!-- Tỷ Lệ Hoa Hồng -->
                        <div class="form-group">
                            <label for="commission_rate" class="font-weight-bold">Tỷ Lệ Hoa Hồng (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="commission_rate" name="commission_rate" 
                                       value="<?= htmlspecialchars($supplier['commission_rate'] ?? 0) ?>" step="0.01" min="0" max="100">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Điều Kiện Thanh Toán -->
                        <div class="form-group">
                            <label for="payment_terms" class="font-weight-bold">Điều Kiện Thanh Toán</label>
                            <input type="text" class="form-control" id="payment_terms" name="payment_terms" 
                                   value="<?= htmlspecialchars($supplier['payment_terms'] ?? '') ?>">
                        </div>

                        <!-- Trạng Thái -->
                        <div class="form-group">
                            <label for="status" class="font-weight-bold">Trạng Thái</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" <?= $supplier['status'] === 'active' ? 'selected' : '' ?>>✓ Hoạt Động</option>
                                <option value="inactive" <?= $supplier['status'] === 'inactive' ? 'selected' : '' ?>>⏸️ Không Hoạt Động</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save"></i> Lưu Thay Đổi
                            </button>
                            <a href="index.php?action=suppliers_show&id=<?= $supplier['id'] ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-calendar-alt"></i> Thông Tin</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>ID:</strong> #<?= $supplier['id'] ?></p>
                    <p class="mb-2"><strong>Tạo lúc:</strong> <?= date('d/m/Y H:i', strtotime($supplier['created_at'])) ?></p>
                    <p class="mb-0"><strong>Trạng Thái:</strong> 
                        <span class="badge badge-<?= $supplier['status'] === 'active' ? 'success' : 'warning' ?>">
                            <?= $supplier['status'] === 'active' ? '✓ Hoạt Động' : '⏸️ Không Hoạt Động' ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
