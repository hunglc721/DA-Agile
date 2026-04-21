<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-white">Danh Sách Hướng Dẫn Viên</h6>
        <a href="<?= url('guides_create') ?>" class="btn btn-primary btn-sm">Thêm HDV</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Kinh Nghiệm</th>
                        <th>Ngôn Ngữ</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($guides as $guide): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($guide['full_name']) ?></strong><br>
                                <small><?= htmlspecialchars($guide['email']) ?></small>
                            </td>
                            <td><?= $guide['experience_years'] ?> năm</td>
                            <td><?= htmlspecialchars($guide['languages']) ?></td>
                            <td>
                                <span class="badge badge-<?= $guide['status'] === 'active' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($guide['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= url('guides_edit?id=' . $guide['id']) ?>" class="btn btn-info btn-sm">Sửa</a>
                                <form action="<?= url('guides_delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Xóa HDV này?');">
                                    <input type="hidden" name="id" value="<?= $guide['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>