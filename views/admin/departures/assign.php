<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Phân Bổ Nhân Sự</h1>
    <div>
        <a href="<?php echo url('assignments'); ?>" class="btn btn-info">Xem tất cả phân bổ</a>
        <a href="<?php echo url('departures'); ?>" class="btn btn-secondary">Quay lại</a>
    </div>
</div>

<?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success alert-dismissible fade show" role="alert"><?= $_SESSION['success'] ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div><?php unset($_SESSION['success']); endif; ?>
<?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $_SESSION['error'] ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div><?php unset($_SESSION['error']); endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary text-white">
        <h6 class="m-0 font-weight-bold">Thông tin lịch khởi hành</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <p class="mb-1"><strong>Tour:</strong></p>
                <p class="text-primary font-weight-bold"><?= escape($departure['tour_name'] ?? 'N/A') ?></p>
                <small class="text-muted"><?= escape($departure['tour_code'] ?? '') ?></small>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Ngày khởi hành:</strong></p>
                <p class="text-success font-weight-bold"><?= escape($departure['departure_date'] ?? 'N/A') ?></p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Sức chứa:</strong></p>
                <p class="text-info font-weight-bold"><?= (int)($departure['capacity'] ?? 0) ?> chỗ</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
<div class="col-lg-6">
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-info text-white">
        <h6 class="m-0 font-weight-bold">Nhân sự đã phân bổ</h6>
    </div>
    <div class="card-body">
        <?php if (empty($assignments)): ?>
            <div class="text-muted text-center py-3">
                <i class="fas fa-info-circle"></i> Chưa phân bổ nhân sự nào
            </div>
        <?php else: ?>
            <div class="list-group">
            <?php foreach ($assignments as $a): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0"><?= escape($a['full_name'] ?? 'N/A') ?></h6>
                            <small class="text-muted">Trạng thái: <span class="badge badge-success"><?= escape($a['status'] ?? 'assigned') ?></span></small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger unassign-btn" data-departure-id="<?php echo $departure['id']; ?>" data-guide-id="<?php echo $a['guide_id']; ?>" data-guide-name="<?= escape($a['full_name'] ?? '') ?>">
                            <i class="fas fa-times"></i> Hủy
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>

<!-- Unassign confirmation modal -->
<div class="modal fade" id="unassignModal" tabindex="-1" role="dialog" aria-labelledby="unassignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unassignModalLabel">Hủy phân bổ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc muốn hủy phân bổ hướng dẫn viên <strong id="unassignGuideName"></strong> cho lịch này?</p>
            </div>
            <div class="modal-footer">
                <form id="unassignForm" method="POST" action="<?php echo url('departures_unassign'); ?>">
                        <input type="hidden" name="departure_id" id="unassign_departure_id" value="">
                        <input type="hidden" name="guide_id" id="unassign_guide_id" value="">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
        if (typeof $ !== 'undefined') {
                $(document).on('click', '.unassign-btn', function(e){
                        e.preventDefault();
                        var depId = $(this).data('departure-id');
                        var guideId = $(this).data('guide-id');
                        var guideName = $(this).data('guide-name') || '';
                        $('#unassign_departure_id').val(depId);
                        $('#unassign_guide_id').val(guideId);
                        $('#unassignGuideName').text(guideName);
                        $('#unassignModal').modal('show');
                });
        }
});
</script>
<div class="col-lg-6">
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-success text-white">
        <h6 class="m-0 font-weight-bold">Phân bổ nhân sự mới</h6>
    </div>
    <div class="card-body">
        <form action="<?php echo url('departures_store_assignment'); ?>" method="POST">
            <input type="hidden" name="departure_id" value="<?php echo $departure['id']; ?>">
            <div class="form-group">
                <label for="guide_select"><strong>Chọn hướng dẫn viên</strong></label>
                <select id="guide_select" name="guide_id" class="form-control" required>
                    <option value="">-- Chọn hướng dẫn viên --</option>
                    <?php foreach ($guides as $g): ?>
                        <option value="<?php echo $g['id']; ?>">
                            <?= escape($g['full_name'] ?? 'N/A') ?> 
                            <small>(<?= escape($g['languages'] ?? 'N/A') ?>, <?= (int)($g['experience_years'] ?? 0) ?> năm)</small>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">Chỉ hiển thị những hướng dẫn viên có sẵn</small>
            </div>
            <button type="submit" class="btn btn-success btn-block">
                <i class="fas fa-check"></i> Phân bổ
            </button>
        </form>
    </div>
</div>
</div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-warning text-dark">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-briefcase"></i> Dịch vụ tour</h6>
        <a href="<?php echo url('services'); ?>?tour_id=<?php echo (int)($departure['tour_id'] ?? 0); ?>" class="btn btn-sm btn-outline-dark"><i class="fas fa-plus"></i> Quản lý</a>
    </div>
    <div class="card-body">
        <?php if (empty($services)): ?>
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i> Chưa có dịch vụ nào được ghi nhận cho tour này.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light"><tr><th>Tên dịch vụ</th><th>Nhà cung cấp</th><th class="text-right">Chi phí</th></tr></thead>
                    <tbody>
                        <?php foreach ($services as $s): ?>
                            <tr>
                                <td><?= escape($s['name'] ?? 'N/A') ?></td>
                                <td><?= escape($s['vendor'] ?? 'N/A') ?></td>
                                <td class="text-right font-weight-bold"><?= number_format((float)($s['amount'] ?? 0), 0, '.', ',') ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
