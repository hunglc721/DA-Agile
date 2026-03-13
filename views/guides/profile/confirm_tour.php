<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle"></i> Xác Nhận Tour: <?= htmlspecialchars($tour['name']) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Vui lòng xác nhận chi tiết tour trước khi khởi hành.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Tên Tour:</strong> <?= htmlspecialchars($tour['name']) ?></p>
                            <p><strong>Ngày Khởi Hành:</strong> <?= date('d/m/Y', strtotime($tour['assignment_date'])) ?></p>
                            <p><strong>Số Lượng Khách:</strong> <?= $tour['participant_count'] ?? 0 ?> người</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mô Tả:</strong> <?= htmlspecialchars($tour['description']) ?></p>
                            <p><strong>Trạng Thái Hiện Tại:</strong> <span class="badge bg-warning"><?= ucfirst($tour['assignment_status']) ?></span></p>
                        </div>
                    </div>

                    <hr>

                    <form method="POST" action="?action=guide_confirm_tour_store">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

                        <div class="form-group mb-3">
                            <label for="notes" class="form-label">Ghi Chú (Nếu Có)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Ghi chú thêm về tour..."></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirm" name="confirm" required>
                            <label class="form-check-label" for="confirm">
                                Tôi đã kiểm tra và xác nhận chi tiết tour này là chính xác
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Xác Nhận Tour
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
