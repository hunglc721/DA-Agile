<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm Danh Mục Tour Mới</h1>
        <a href="index.php?action=tour_categories" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông Tin Danh Mục</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=tour_categories_store" method="POST">
                        <div class="form-group">
                            <label for="name">Tên Danh Mục <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required 
                                   placeholder="VD: Tour Biển Đảo"
                                   value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="description">Mô Tả</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                    placeholder="Mô tả chi tiết về danh mục này..."><?= htmlspecialchars($_SESSION['old']['description'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu Danh Mục
                            </button>
                            <a href="index.php?action=tour_categories" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php unset($_SESSION['old']); ?>