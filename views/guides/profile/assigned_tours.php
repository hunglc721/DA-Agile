<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-map-location-dot"></i> Tour Được Phân Công
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($tours)): ?>
                        <div class="row">
                            <?php foreach ($tours as $tour): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100 hover-card">
                                        <!-- Card Header -->
                                        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h5 class="mb-1"><?= htmlspecialchars($tour['name']) ?></h5>
                                                    <small><?= date('d/m/Y', strtotime($tour['assignment_date'])) ?></small>
                                                </div>
                                                <span class="badge badge-light">
                                                    <i class="fas fa-map-pin"></i> <?= ucfirst($tour['assignment_status']) ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body">
                                            <p class="text-muted mb-3">
                                                <?= substr(htmlspecialchars($tour['description']), 0, 80) ?>...
                                            </p>
                                            <div class="price-section mb-3">
                                                <h4 class="text-primary mb-0">
                                                    <i class="fas fa-tag"></i> 
                                                    <?= number_format($tour['price'], 0, ',', '.') ?> VND
                                                </h4>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="card-footer bg-light border-top-0">
                                            <div class="row g-2">
                                                <!-- Hàng 1: Chức năng chính -->
                                                <div class="col-6">
                                                    <a href="index.php?action=guide_tour_detail&id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-info w-100" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i> Chi Tiết
                                                    </a>
                                                </div>
                                                <div class="col-6">
                                                    <a href="index.php?action=guide_checkin&id=<?= $tour['id'] ?>" class="btn btn-sm btn-success w-100" title="Check-In & Điểm danh">
                                                        <i class="fas fa-clipboard-check"></i> Check-In
                                                    </a>
                                                </div>

                                                <!-- Hàng 2: Xác nhận hoặc Lịch trình -->
                                                <?php if ($tour['assignment_status'] == 'assigned'): ?>
                                                    <div class="col-6">
                                                        <a href="index.php?action=guide_confirm_tour&id=<?= $tour['id'] ?>" class="btn btn-sm btn-primary w-100" title="Xác nhận tour">
                                                            <i class="fas fa-check-circle"></i> Xác Nhận
                                                        </a>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-6">
                                                        <a href="index.php?action=guide_itinerary&id=<?= $tour['id'] ?>" class="btn btn-sm btn-info w-100" title="Lịch trình">
                                                            <i class="fas fa-calendar"></i> Lịch Trình
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="col-6">
                                                    <a href="index.php?action=guide_write_diary&id=<?= $tour['id'] ?>" class="btn btn-sm btn-warning w-100" title="Nhật ký">
                                                        <i class="fas fa-pen"></i> Nhật Ký
                                                    </a>
                                                </div>

                                                <!-- Hàng 3: Khách hàng -->
                                                <div class="col-12">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 view-customers-btn" data-tour-id="<?= $tour['id'] ?>" title="Xem khách hàng">
                                                        <i class="fas fa-users"></i> Khách Hàng (<?= $tour['customer_count'] ?? '0' ?>)
                                                    </button>
                                                </div>

                                                <!-- Hàng 4: Hành động nâng cao -->
                                                <?php if ($tour['assignment_status'] != 'completed'): ?>
                                                    <div class="col-6">
                                                        <a href="index.php?action=guide_update_status&id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-warning w-100" title="Cập nhật">
                                                            <i class="fas fa-sync"></i> Cập Nhật
                                                        </a>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="index.php?action=guide_incident_report&id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-danger w-100" title="Báo cáo">
                                                            <i class="fas fa-exclamation"></i> Báo Cáo
                                                        </a>
                                                    </div>
                                                    <div class="col-12">
                                                        <a href="index.php?action=guide_complete_tour&id=<?= $tour['id'] ?>" class="btn btn-sm btn-outline-success w-100" title="Hoàn thành">
                                                            <i class="fas fa-flag-checkered"></i> Hoàn Thành Tour
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Báo cáo tổng kết -->
                                                <?php if ($tour['assignment_status'] == 'completed'): ?>
                                                    <div class="col-12">
                                                        <a href="index.php?action=guide_final_report&id=<?= $tour['id'] ?>" class="btn btn-sm btn-success w-100" title="Báo cáo tổng kết">
                                                            <i class="fas fa-file-alt"></i> Báo Cáo Tổng Kết
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Bạn hiện không có tour nào được phân công.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Danh Sách Khách Hàng -->
<div class="modal fade" id="customersModal" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customersModalLabel"><i class="fas fa-users"></i> Danh Sách Khách Hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-customer-list-content">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="view-detail-link" class="btn btn-primary" target="_blank">
                    <i class="fas fa-expand"></i> Xem Chi Tiết Đầy Đủ
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Bắt sự kiện click vào nút "Khách Hàng"
    $('.view-customers-btn').on('click', function() {
        var tourId = $(this).data('tour-id');
        var modal = $('#customersModal');
        var modalContent = $('#modal-customer-list-content');
        var modalLabel = $('#customersModalLabel');
        var viewDetailLink = $('#view-detail-link');

        // Hiển thị trạng thái đang tải
        modalLabel.html('<i class="fas fa-users"></i> Danh Sách Khách Hàng');
        modalContent.html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
        viewDetailLink.attr('href', '?action=guide_tour_customers_detail&id=' + tourId);
        modal.modal('show');

        // Gọi API để lấy dữ liệu
        $.ajax({
            url: '?action=guide_tour_customers&id=' + tourId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    modalLabel.html('<i class="fas fa-users"></i> Danh Sách Khách Hàng: ' + data.tour.name + ' (' + (data.customers ? data.customers.length : 0) + ')');
                    var html = '<p class="alert alert-info">Chưa có khách hàng nào được xác nhận cho tour này.</p>';
                    if (data.customers && data.customers.length > 0) {
                        html = '<div class="customer-list">';
                        $.each(data.customers, function(index, customer) {
                            var statusBadgeClass = customer.booking_status === 'confirmed' ? 'success' : 'warning';
                            var checkinBadgeClass = 'secondary';
                            var checkinText = '⏳ Chưa Check-In';

                            if (customer.checkin_status === 'checked_in') {
                                checkinBadgeClass = 'success';
                                checkinText = '✓ Đã Check-In';
                            } else if (customer.checkin_status === 'absent') {
                                checkinBadgeClass = 'danger';
                                checkinText = '✗ Vắng Mặt';
                            }

                            html += '<div class="card mb-3 border-left-info">' +
                                '<div class="card-body">' +
                                '<div class="d-flex justify-content-between align-items-start mb-2">' +
                                '<h6 class="mb-0"><i class="fas fa-user-circle"></i> ' + (customer.full_name || 'N/A') + '</h6>' +
                                '<div class="badge-group">' +
                                '<span class="badge badge-' + statusBadgeClass + ' mr-1">' + (customer.booking_status || 'N/A') + '</span>' +
                                '<span class="badge badge-' + checkinBadgeClass + '">' + checkinText + '</span>' +
                                '</div>' +
                                '</div>' +
                                '<hr class="my-2">' +
                                '<div class="row">' +
                                '<div class="col-md-6">' +
                                '<small class="d-block text-muted mb-1"><i class="fas fa-phone"></i> <strong>Điện Thoại:</strong> ' + (customer.phone || 'N/A') + '</small>' +
                                '<small class="d-block text-muted mb-1"><i class="fas fa-envelope"></i> <strong>Email:</strong> ' + (customer.email || 'N/A') + '</small>' +
                                '<small class="d-block text-muted"><i class="fas fa-map-marker-alt"></i> <strong>Địa Chỉ:</strong> ' + (customer.address ? customer.address.substring(0, 30) + '...' : 'N/A') + '</small>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                '<small class="d-block text-muted mb-1"><i class="fas fa-users"></i> <strong>Số Người:</strong> ' + (customer.number_of_people || 1) + ' người</small>' +
                                '<small class="d-block text-muted mb-1"><i class="fas fa-credit-card"></i> <strong>Tổng Tiền:</strong> ' + (customer.total_price ? new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(customer.total_price) : 'N/A') + '</small>' +
                                '<small class="d-block text-muted"><i class="fas fa-calendar"></i> <strong>Đặt Ngày:</strong> ' + (customer.booking_date ? new Date(customer.booking_date).toLocaleDateString('vi-VN') : 'N/A') + '</small>' +
                                '</div>' +
                                '</div>' +
                                (customer.special_requests ? '<div class="mt-2 alert alert-warning alert-sm p-2 mb-0"><small><strong>Yêu Cầu Đặc Biệt:</strong> ' + customer.special_requests + '</small></div>' : '') +
                                '</div>' +
                                '</div>';
                        });
                        html += '</div>';
                    }
                    modalContent.html(html);
                } else {
                    modalContent.html('<div class="alert alert-danger">Lỗi: ' + data.message + '</div>');
                }
            },
            error: function() {
                modalContent.html('<div class="alert alert-danger">Không thể tải dữ liệu. Vui lòng thử lại.</div>');
            }
        });
    });
});
</script>
<style>
/* Card Styling */
.hover-card {
    transition: all 0.3s ease;
    border-radius: 10px;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

.card-header.bg-gradient {
    border-radius: 10px 10px 0 0;
    padding: 1.25rem;
}

/* Button Styling */
.btn-outline-info:hover {
    background-color: #17a2b8;
    color: white;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    color: white;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}

.btn-outline-success:hover {
    background-color: #28a745;
    color: white;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: white;
}

.btn-sm {
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-sm:hover {
    transform: translateY(-2px);
}

/* Price Section */
.price-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 15px;
    border-radius: 8px;
    text-align: center;
}

/* Card Footer */
.card-footer {
    padding: 1rem;
    border-top: 1px solid #e3e6f0;
    border-radius: 0 0 10px 10px;
}

/* Customer List Styling */
.customer-list .card {
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.customer-list .card:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.customer-list .card-body {
    padding: 1.25rem;
}

.customer-list hr {
    margin: 0.75rem 0;
}

.customer-list small {
    font-size: 0.85rem;
}

.customer-list .badge-group {
    display: flex;
    gap: 4px;
}

.customer-list .alert-sm {
    font-size: 0.85rem;
    padding: 0.5rem !important;
}

/* Responsive Grid */
@media (max-width: 768px) {
    .row > .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .customer-list .row {
        flex-direction: column;
    }

    .customer-list .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>