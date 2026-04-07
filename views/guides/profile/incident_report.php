<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="mb-4">
                <h1 class="h2 text-gray-800 mb-2">
                    <i class="fas fa-exclamation-triangle text-danger"></i> Báo Cáo Sự Cố
                </h1>
                <p class="text-muted">Vui lòng báo cáo chi tiết sự cố để Admin có thể hỗ trợ kịp thời</p>
            </div>

            <!-- Alert -->
            <div class="alert alert-warning border-left-warning alert-left-lg mb-4" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Lưu ý quan trọng:</strong> Hãy cung cấp thông tin chi tiết và chính xác nhất về sự cố. Điều này giúp admin xử lý nhanh chóng.
            </div>

            <!-- Form Card -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-pen-square"></i> Chi Tiết Sự Cố
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="?action=guide_incident_report_store" id="incidentForm">
                        <input type="hidden" name="tour_id" value="<?= $tour_id ?>">

                        <!-- Tiêu Đề -->
                        <div class="form-group mb-4">
                            <label for="title" class="form-label fw-bold">
                                <i class="fas fa-tag text-danger"></i> Tiêu Đề Sự Cố <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" required 
                                placeholder="Ví dụ: Khách hàng bị ốm, Xe hỏng, Mất hành lý..." style="border-radius: 8px;">
                            <small class="form-text text-muted">Mô tả ngắn gọn vấn đề chính</small>
                        </div>

                        <!-- Mô Tả Chi Tiết -->
                        <div class="form-group mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left text-danger"></i> Mô Tả Chi Tiết <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="6" required 
                                placeholder="- Mô tả chi tiết về sự cố&#10;- Thời gian phát sinh&#10;- Những người liên quan&#10;- Tác động và hậu quả&#10;- Các biện pháp đã thực hiện..." 
                                style="border-radius: 8px; font-family: monospace;"></textarea>
                            <small class="form-text text-muted">Tối thiểu 20 ký tự</small>
                        </div>

                        <!-- Mức Độ Nghiêm Trọng -->
                        <div class="form-group mb-4">
                            <label for="severity" class="form-label fw-bold">
                                <i class="fas fa-fire text-warning"></i> Mức Độ Nghiêm Trọng
                            </label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="severity_low" name="severity" value="low">
                                        <label class="form-check-label" for="severity_low">
                                            <span class="badge bg-info">Thấp</span> Ảnh hưởng nhỏ
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="severity_medium" name="severity" value="medium" checked>
                                        <label class="form-check-label" for="severity_medium">
                                            <span class="badge bg-warning">Trung Bình</span> Ảnh hưởng vừa phải
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="severity_high" name="severity" value="high">
                                        <label class="form-check-label" for="severity_high">
                                            <span class="badge bg-danger">Cao</span> Ảnh hưởng nghiêm trọng
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-3 pt-3">
                            <button type="submit" class="btn btn-danger btn-lg flex-grow-1">
                                <i class="fas fa-paper-plane"></i> Gửi Báo Cáo
                            </button>
                            <a href="?action=guide_assigned_tours" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-4 p-3 bg-light rounded border-left border-danger">
                <h6 class="mb-2"><i class="fas fa-lightbulb"></i> Mẹo:</h6>
                <ul class="mb-0 small">
                    <li>Cung cấp càng chi tiết càng tốt để admin hiểu rõ vấn đề</li>
                    <li>Nêu rõ thời gian chính xác sự cố xảy ra</li>
                    <li>Liệt kê các hành động khắc phục đã thực hiện</li>
                </ul>
            </div>
        </div>
    </div>
</div>

