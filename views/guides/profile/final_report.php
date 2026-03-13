<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="mb-4">
                <h1 class="h2 text-gray-800 mb-2">
                    <i class="fas fa-file-alt text-primary"></i> Báo Cáo Tổng Kết Tour
                </h1>
                <p class="text-muted">Cung cấp báo cáo chi tiết sau khi hoàn thành tour</p>
            </div>

            <!-- Alert -->
            <div class="alert alert-info border-left-info alert-left-lg mb-4" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Thông tin:</strong> Báo cáo này sẽ được lưu để admin có thể theo dõi quá trình hoàn thành tour.
            </div>

            <!-- Form Card -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-pen-square"></i> Nội Dung Báo Cáo
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="?action=guide_final_report_store" id="reportForm" enctype="multipart/form-data">
                        <input type="hidden" name="tour_id" value="<?= $tour_id ?>">

                        <!-- Tóm Tắt Tour -->
                        <div class="form-group mb-4">
                            <label for="summary" class="form-label fw-bold">
                                <i class="fas fa-briefcase text-primary"></i> Tóm Tắt Tour <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="summary" name="summary" rows="5" required 
                                placeholder="- Các điểm du lịch đã thăm&#10;- Các hoạt động chính&#10;- Sự kiện đặc biệt&#10;- Điểm nhấn của tour..." 
                                style="border-radius: 8px; font-family: monospace;"></textarea>
                            <small class="form-text text-muted">Tối thiểu 30 ký tự</small>
                        </div>

                        <!-- Phản Hồi Khách Hàng -->
                        <div class="form-group mb-4">
                            <label for="customer_feedback" class="form-label fw-bold">
                                <i class="fas fa-comments text-success"></i> Phản Hồi Khách Hàng
                            </label>
                            <textarea class="form-control" id="customer_feedback" name="customer_feedback" rows="3" 
                                placeholder="Mô tả phản hồi chung từ khách hàng về tour (tích cực hay tiêu cực)" 
                                style="border-radius: 8px;"></textarea>
                            <small class="form-text text-muted">Tùy chọn - bỏ trống nếu không có</small>
                        </div>

                        <!-- Chi Phí Phát Sinh -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="expenses" class="form-label fw-bold">
                                    <i class="fas fa-money-bill-wave text-warning"></i> Chi Phí Phát Sinh
                                </label>
                                <div class="input-group input-group-lg" style="border-radius: 8px;">
                                    <span class="input-group-text bg-light">₫</span>
                                    <input type="number" class="form-control" id="expenses" name="expenses" min="0" step="1000" 
                                        placeholder="0" style="border-radius: 8px;">
                                </div>
                                <small class="form-text text-muted">Nhập 0 nếu không có chi phí phát sinh</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-info-circle text-muted"></i> Loại Chi Phí
                                </label>
                                <select class="form-select form-select-lg" name="expense_type" style="border-radius: 8px;">
                                    <option value="">-- Chọn loại --</option>
                                    <option value="accommodation">Lưu trú</option>
                                    <option value="food">Ăn uống</option>
                                    <option value="transportation">Vận chuyển</option>
                                    <option value="activities">Hoạt động</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tệp Đính Kèm -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-paperclip text-secondary"></i> Tệp Đính Kèm
                            </label>
                            <div class="input-group" style="border-radius: 8px;">
                                <input type="file" class="form-control" id="attachments" name="attachments" multiple 
                                    accept="image/*,.pdf,.doc,.docx" style="border-radius: 8px;">
                            </div>
                            <small class="form-text text-muted d-block mt-2">
                                <i class="fas fa-check"></i> Hỗ trợ: Ảnh (.jpg, .png), PDF, Word<br>
                                <i class="fas fa-check"></i> Tối đa 5 tệp, mỗi tệp dưới 5MB
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-3 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
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
            <div class="mt-4 p-3 bg-light rounded border-left border-primary">
                <h6 class="mb-2"><i class="fas fa-clipboard-check"></i> Checklist Báo Cáo:</h6>
                <ul class="mb-0 small">
                    <li>✓ Đã mô tả chi tiết các điểm du lịch và hoạt động</li>
                    <li>✓ Đã ghi nhận phản hồi từ khách hàng</li>
                    <li>✓ Đã tính toán chính xác chi phí phát sinh (nếu có)</li>
                    <li>✓ Đã đính kèm các bằng chứng hoặc tài liệu liên quan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.alert-left-lg {
    border-left: 5px solid !important;
    border-radius: 8px;
}

.card {
    border-radius: 10px;
}

.form-control, .form-select, .form-check-input, .input-group {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.input-group-lg > .input-group-text {
    padding: 0.75rem 1rem;
    font-weight: 600;
}
</style>
