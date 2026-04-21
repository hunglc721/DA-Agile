<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-white">Cập Nhật Thông Tin HDV: <?= htmlspecialchars($guide['full_name']) ?></h6>
    </div>
    <div class="card-body">
        <form action="<?= url('guides_update') ?>" method="POST">
            <input type="hidden" name="id" value="<?= $guide['id'] ?>">

            <div class="form-group">
                <label>Số Năm Kinh Nghiệm</label>
                <input type="number" name="experience_years" class="form-control" value="<?= $guide['experience_years'] ?>">
            </div>

            <div class="form-group">
                <label>Ngôn Ngữ</label>
                <input type="text" name="languages" class="form-control" value="<?= htmlspecialchars($guide['languages']) ?>">
            </div>

            <div class="form-group">
                <label>Giới Thiệu</label>
                <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($guide['bio'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?= url('guides') ?>" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>