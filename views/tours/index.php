<form method="GET" action="index.php" class="search-form">
    <input type="hidden" name="action" value="tours">

    <input type="text"
        name="search"
        value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
        placeholder="Tìm tên tour, địa điểm...">

    <input type="number"
        name="price_min"
        value="<?= htmlspecialchars($filters['price_min'] ?? '') ?>"
        placeholder="Giá từ (VNĐ)"
        min="0">

    <input type="number"
        name="price_max"
        value="<?= htmlspecialchars($filters['price_max'] ?? '') ?>"
        placeholder="Giá đến (VNĐ)"
        min="0">

    <select name="sort">
        <option value="">-- Sắp xếp --</option>
        <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc'  ? 'selected' : '' ?>>Giá tăng dần</option>
        <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
        <option value="name_asc" <?= ($filters['sort'] ?? '') === 'name_asc'   ? 'selected' : '' ?>>Tên A → Z</option>
        <option value="newest" <?= ($filters['sort'] ?? '') === 'newest'     ? 'selected' : '' ?>>Mới nhất</option>
    </select>

    <button type="submit">Tìm kiếm</button>
    <a href="index.php?action=tours">Xóa bộ lọc</a>
</form>

<p>Tìm thấy <strong><?= count($tours) ?></strong> tour</p>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Mã Tour</th>
            <th>Tên Tour</th>
            <th>Địa điểm</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Số ngày</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($tours)): ?>
            <?php foreach ($tours as $i => $tour): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($tour['tour_code'] ?? '') ?></td>
                    <td><?= htmlspecialchars($tour['name']) ?></td>
                    <td><?= htmlspecialchars($tour['destinations'] ?? $tour['start_location'] ?? '') ?></td>
                    <td><?= htmlspecialchars($tour['category_name'] ?? '') ?></td>
                    <td><?= number_format($tour['price']) ?>đ</td>
                    <td><?= htmlspecialchars($tour['duration'] ?? '') ?> ngày</td>
                    <td><?= htmlspecialchars($tour['status'] ?? '') ?></td>
                    <td>
                        <a href="index.php?action=tours_show&id=<?= $tour['id'] ?>">Xem</a> |
                        <a href="index.php?action=tours_edit&id=<?= $tour['id'] ?>">Sửa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" style="text-align:center">Không tìm thấy tour nào</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>