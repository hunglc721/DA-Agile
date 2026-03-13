<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thêm Hướng Dẫn Viên Mới</h6>
    </div>
    <div class="card-body">
        <form action="<?= url('guides_store') ?>" method="POST">
            <div class="form-group">
                <label><strong>Chọn Người Dùng (User)</strong></label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Chọn user để nâng cấp thành HDV --</option>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>">
                                <?= htmlspecialchars($user['full_name']) ?> (<?= $user['email'] ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>Không có user nào khả dụng</option>
                    <?php endif; ?>
                </select>
                <small class="form-text text-muted">Chỉ hiển thị user chưa phải là Admin hoặc HDV.</small>
            </div>
            
            <div class="form-group">
                <label><strong>Số Năm Kinh Nghiệm</strong></label>
                <input type="number" name="experience_years" class="form-control" min="0" value="0">
            </div>

            <div class="form-group">
                <label><strong>Ngôn Ngữ Thành Thạo</strong></label>
                <input type="text" name="languages" class="form-control" placeholder="VD: Tiếng Việt, Tiếng Anh" value="Tiếng Việt">
            </div>

            <div class="form-group">
                <label><strong>Giới Thiệu Ngắn (Bio)</strong></label>
                <textarea name="bio" class="form-control" rows="3" placeholder="Mô tả ngắn về HDV..."></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Lưu lại
                </button>
                <a href="<?= url('guides') ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
