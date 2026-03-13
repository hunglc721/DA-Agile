<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Khởi Hành</h1>
    <a href="<?= url('departures_create') ?>" class="btn btn-primary">Tạo lịch khởi hành</a>
 </div>

<div class="card shadow mb-4">
  <div class="card-header py-3">
                              Hành động
  </div>
                            <div class="dropdown-menu" aria-labelledby="actionsDropdown<?php echo $d['id'] ?? ''; ?>">
                              <a class="dropdown-item" href="<?php echo url('departures_assign?id=' . ($d['id'] ?? '')); ?>">Phân bổ nhân sự</a>
                              <a class="dropdown-item" href="<?php echo url('departures_edit?id=' . ($d['id'] ?? '')); ?>">Sửa</a>
                              <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo $d['id'] ?? ''; ?>" data-name="<?= escape($d['tour_name'] ?? '') ?>">Xóa</a>
          <select name="tour_id" class="form-control">
            <option value="">Tất cả tour</option>
            <?php foreach ($tours as $t): ?>
              <option value="<?= $t['id'] ?>" <?= ((isset($_GET['tour_id']) && $_GET['tour_id']==$t['id'])?'selected':'') ?>><?= escape($t['name']) ?> (<?= escape($t['tour_code'] ?? '') ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group col-md-3">
          <input type="date" name="from" class="form-control" value="<?= escape($_GET['from'] ?? '') ?>" placeholder="Từ ngày">
        </div>
        <div class="form-group col-md-3">
          <input type="date" name="to" class="form-control" value="<?= escape($_GET['to'] ?? '') ?>" placeholder="Đến ngày">
        </div>
        <div class="form-group col-md-2">
          <select name="status" class="form-control">
            <option value="">Trạng thái</option>
            <?php foreach (['scheduled','ongoing','finished','cancelled'] as $st): ?>
              <option value="<?= $st ?>" <?= ((isset($_GET['status']) && $_GET['status']==$st)?'selected':'') ?>><?= $st ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group col-md-1">
          <button class="btn btn-info w-100" type="submit">Lọc</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php if (!empty($_SESSION['success'])): ?><div class="alert alert-success"><?= $_SESSION['success'] ?></div><?php unset($_SESSION['success']); endif; ?>
<?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= $_SESSION['error'] ?></div><?php unset($_SESSION['error']); endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách lịch khởi hành</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tour</th>
                        <th>Ngày khởi hành</th>
                        <th>Sức chứa</th>
                        <th>Chỗ trống</th>
                        <th>Tỷ lệ</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($departures)): ?>
                    <tr><td colspan="7" class="text-center text-muted">Chưa có lịch khởi hành nào</td></tr>
                    <?php endif; ?>
                    <?php foreach ($departures as $d): ?>
                    <tr>
                        <td>
                            <strong><?= escape($d['tour_name'] ?? 'N/A') ?></strong><br>
                            <small class="text-muted"><?= escape($d['tour_code'] ?? '') ?></small>
                        </td>
                        <td><?= escape($d['departure_date'] ?? 'N/A') ?></td>
                        <td><?= (int)($d['capacity'] ?? 0) ?></td>
                        <td><?= (int)($d['available_slots'] ?? 0) ?></td>
                        <td>
                            <?php 
                                $cap = (int)($d['capacity'] ?? 1);
                                $avail = (int)($d['available_slots'] ?? 0);
                                $booked = $cap - $avail;
                                $percent = $cap > 0 ? round(($booked / $cap) * 100) : 0;
                            ?>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" role="progressbar" style="width: <?= $percent ?>%" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"><?= $percent ?>%</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-<?= ($d['status'] === 'scheduled' ? 'primary' : ($d['status'] === 'ongoing' ? 'success' : ($d['status'] === 'finished' ? 'secondary' : 'danger'))) ?>">
                                <?= escape($d['status'] ?? 'unknown') ?>
                            </span>
                        </td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="actionsDropdown<?php echo $d['id'] ?? ''; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Hành động
                            </button>
                              <div class="dropdown-menu" aria-labelledby="actionsDropdown<?php echo $d['id'] ?? ''; ?>">
                                <a class="dropdown-item" href="<?php echo url('departures_assign?id=' . ($d['id'] ?? '')); ?>">Phân bổ nhân sự</a>
                                <a class="dropdown-item" href="<?php echo url('departures_edit?id=' . ($d['id'] ?? '')); ?>">Sửa</a>
                                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo $d['id'] ?? ''; ?>" data-name="<?= escape($d['tour_name'] ?? '') ?>">Xóa</a>
                            </div>
                          </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Xóa lịch khởi hành</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Bạn có chắc muốn xóa lịch <strong id="deleteModalName"> </strong>?</p>
      </div>
      <div class="modal-footer">
        <form id="deleteForm" method="POST" action="<?php echo url('departures_delete'); ?>">
            <input type="hidden" name="id" id="delete_id" value="">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-danger">Xóa</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // uses jQuery because admin template already loads it
    if (typeof $ !== 'undefined') {
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name') || '';
            $('#delete_id').val(id);
            $('#deleteModalName').text(name);
        });
    }
});
</script>
