<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-flag-checkered"></i> Hoàn Thành Tour: <?= htmlspecialchars($tour['tour_name'] ?? 'N/A') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Xác nhận hoàn thành tour sẽ gửi đến Admin để xử lý báo cáo tổng kết.
                    </div>

                    <form method="POST" action="?action=guide_complete_tour_store">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

                        <div class="form-group mb-3">
                            <label for="completion_notes" class="form-label">Ghi Chú Hoàn Thành</label>
                            <textarea class="form-control" id="completion_notes" name="completion_notes" rows="4" placeholder="Mô tả quá trình hoàn thành tour..."></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirm_complete" name="confirm_complete" required>
                            <label class="form-check-label" for="confirm_complete">
                                Tôi xác nhận tour đã được hoàn thành thành công
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> Xác Nhận Hoàn Thành
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
