<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sync"></i> Cập Nhật Tình Trạng Tour: <?= htmlspecialchars($tour['tour_name'] ?? 'N/A') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="?action=guide_update_status_store">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Tình Trạng Tour</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">-- Chọn Tình Trạng --</option>
                                <option value="ongoing">Đang Diễn Ra</option>
                                <option value="completed">Đã Hoàn Thành</option>
                                <option value="cancelled">Đã Hủy</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes" class="form-label">Ghi Chú</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Mô tả chi tiết về tình trạng tour..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập Nhật
                            </button>
                            <a href="?action=guide_assigned_tours" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
