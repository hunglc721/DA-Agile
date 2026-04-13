<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-trash"></i> Thùng Rác - Tour Đã Xóa</h2>
        <a href="index.php?action=tours" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <?php if (empty($tours)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-check-circle fa-2x mb-2"></i>
            <p class="mb-0">Thùng rác trống!</p>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã Tour</th>
                            <th>Tên Tour</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Ngày xóa</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tours as $tour): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($tour['tour_code'] ?? '') ?></code></td>
                                <td><?= htmlspecialchars($tour['name']) ?></td>
                                <td><?= htmlspecialchars($tour['category_name'] ?? '—') ?></td>
                                <td><?= number_format($tour['price']) ?> đ</td>
                                <td class="text-danger">
                                    <?= date('d/m/Y H:i', strtotime($tour['deleted_at'])) ?>
                                </td>
                                <td class="text-center">
                                    <!-- Khôi phục -->
                                    <a href="index.php?action=tours_restore&id=<?= $tour['id'] ?>"
                                        class="btn btn-sm btn-success"
                                        title="Khôi phục">
                                        <i class="fas fa-undo"></i> Khôi phục
                                    </a>
                                    <!-- Xóa vĩnh viễn -->
                                    <a href="index.php?action=tours_force_delete&id=<?= $tour['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Xóa vĩnh viễn? Không thể hoàn tác!')"
                                        title="Xóa vĩnh viễn">
                                        <i class="fas fa-times"></i> Xóa vĩnh viễn
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>