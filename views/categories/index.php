<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản Lý Danh Mục Tour</h1>
        <a href="index.php?action=tour_categories_create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm Danh Mục Mới
        </a>
    </div>

    <!-- DEBUG: Check if categories exist -->
    <?php
    echo "<!-- DEBUG: categories = " . print_r($categories, true) . " -->";
    echo "<!-- DEBUG: categories type = " . gettype($categories) . " -->";
    echo "<!-- DEBUG: categories count = " . count($categories ?? []) . " -->";
    ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php unset($_SESSION['success']);
    endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php unset($_SESSION['error']);
    endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">Danh Sách Danh Mục</h6>
            <span class="badge badge-primary badge-pill"><?= count($categories ?? []) ?> danh mục</span>
        </div>
        <div class="card-body">
            <?php if (empty($categories)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Chưa có danh mục nào. <a href="index.php?action=tour_categories_create">Tạo danh mục mới</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="40%">Tên Danh Mục</th>
                                <th width="40%">Mô Tả</th>
                                <th width="15%">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><strong>#<?= $cat['id'] ?></strong></td>
                                    <td><?= htmlspecialchars($cat['name']) ?></td>
                                    <td><?= htmlspecialchars($cat['description'] ?? '') ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="index.php?action=tour_categories_edit&id=<?= $cat['id'] ?>" class="btn btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <a href="index.php?action=tour_categories_delete&id=<?= $cat['id'] ?>" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?');" title="Xóa">
                                                <i class="fas fa-trash"></i> Xóa
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>