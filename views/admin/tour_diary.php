<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <form action="" method="GET" class="d-flex">
            <input type="hidden" name="action" value="tour_diary">
            <select name="tour_id" class="form-control mr-2" onchange="this.form.submit()">
                <option value="">-- Chọn Tour Để Xem Nhật Ký --</option>
                <?php foreach ($tours as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= ($tour_id == $t['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['name']) ?> (ID: <?= $t['id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    
    <div class="col-md-6 text-right">
        <?php if ($tour_id): ?>
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalDiary" onclick="resetForm()">
                <i class="fas fa-plus"></i> Viết Nhật Ký / Báo Cáo
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if (!$tour_id): ?>
    <div class="alert alert-info text-center">
        <h4><i class="fas fa-arrow-left"></i> Vui lòng chọn một tour từ danh sách phía trên để bắt đầu quản lý.</h4>
    </div>
<?php else: ?>
    
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đang diễn ra</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $tour['name'] ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-plane fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Dòng Thời Gian</h6>
        </div>
        <div class="card-body">
            <?php if (empty($diaries)): ?>
                <p class="text-center text-muted">Chưa có ghi chép nào cho tour này.</p>
            <?php else: ?>
                <div class="timeline">
                    <?php foreach ($diaries as $d): ?>
                        <div class="card mb-3 border-left-<?= $d['diary_type'] == 'incident' ? 'danger' : ($d['diary_type'] == 'feedback' ? 'warning' : 'primary') ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0 text-<?= $d['diary_type'] == 'incident' ? 'danger' : 'primary' ?>">
                                        <?php if($d['diary_type']=='journey'): ?> <i class="fas fa-bus"></i> Hành Trình
                                        <?php elseif($d['diary_type']=='incident'): ?> <i class="fas fa-exclamation-triangle"></i> Sự Cố
                                        <?php else: ?> <i class="fas fa-comment"></i> Phản Hồi Khách <?php endif; ?>
                                        : <?= htmlspecialchars($d['title']) ?>
                                    </h5>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($d['created_at'])) ?></small>
                                </div>

                                <?php
                                    $lines = preg_split('/\r?\n/', trim((string)$d['content']));
                                    $parts = ['hdv'=>'','weather'=>'','health'=>'','activities'=>'','handling'=>'','feedback'=>'','coord'=>'','spirit'=>''];
                                    $raw = [];
                                    foreach ($lines as $ln) {
                                        $l = trim($ln);
                                        if ($l === '') continue;
                                        if (stripos($l,'HDV:')===0) { $parts['hdv'] = trim(substr($l,4)); }
                                        elseif (stripos($l,'Thời tiết:')===0) { $parts['weather'] = trim(substr($l,11)); }
                                        elseif (stripos($l,'Sức khỏe khách:')===0) { $parts['health'] = trim(substr($l,15)); }
                                        elseif (stripos($l,'Hoạt động đặc biệt:')===0) { $parts['activities'] = trim(substr($l,20)); }
                                        elseif (stripos($l,'Phản hồi khách:')===0) { $parts['feedback'] = trim(substr($l,15)); }
                                        elseif (stripos($l,'Đánh giá phối hợp:')===0) { $parts['coord'] = trim(substr($l,18)); }
                                        elseif (stripos($l,'Tinh thần làm việc:')===0) { $parts['spirit'] = trim(substr($l,18)); }
                                        else { $raw[] = $l; }
                                    }
                                ?>
                                <?php if (!empty($parts['hdv'])): ?><div class="mb-2"><strong>HDV:</strong> <?= htmlspecialchars($parts['hdv']) ?></div><?php endif; ?>
                                <?php if (!empty($parts['weather'])): ?><div class="mb-1">Thời tiết: <span class="font-weight-bold"><?= htmlspecialchars($parts['weather']) ?></span></div><?php endif; ?>
                                <?php if (!empty($parts['health'])): ?><div class="mb-1">Sức khỏe khách: <span class="font-weight-bold"><?= htmlspecialchars($parts['health']) ?></span></div><?php endif; ?>
                                <?php if (!empty($parts['activities'])): ?><div class="mb-1">Hoạt động đặc biệt: <span class="font-weight-bold"><?= htmlspecialchars($parts['activities']) ?></span></div><?php endif; ?>
                                <?php if (!empty($parts['feedback'])): ?><div class="mb-1">Phản hồi khách: <span class="font-weight-bold"><?= htmlspecialchars($parts['feedback']) ?></span></div><?php endif; ?>
                                <?php if (!empty($parts['coord']) || !empty($parts['spirit'])): ?><div class="mb-1">Đánh giá HDV: <?php if(!empty($parts['coord'])): ?><span>Phối hợp <?= htmlspecialchars($parts['coord']) ?></span><?php endif; ?><?php if(!empty($parts['spirit'])): ?><span class="ml-2">Tinh thần <?= htmlspecialchars($parts['spirit']) ?></span><?php endif; ?></div><?php endif; ?>
                                <?php if (!empty($raw)): ?><p class="card-text"><strong>Nội dung:</strong> <?= nl2br(htmlspecialchars(implode("\n", $raw))) ?></p><?php endif; ?>
                                
                                <?php if ($d['location']): ?>
                                    <p class="card-text"><small><i class="fas fa-map-marker-alt"></i> Tại: <?= htmlspecialchars($d['location']) ?></small></p>
                                <?php endif; ?>

                                <?php if ($d['handling']): ?>
                                    <div class="alert alert-light border">
                                        <strong><i class="fas fa-tools"></i> Đã xử lý:</strong> <?= nl2br(htmlspecialchars($d['handling'])) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($d['images'])): 
                                    $imgs = json_decode($d['images'], true);
                                    if(is_array($imgs)):
                                ?>
                                    <div class="row">
                                        <?php foreach($imgs as $img): ?>
                                            <div class="col-md-2 col-4 mb-2">
                                                <img src="<?= $img ?>" class="img-fluid rounded border" onclick="window.open(this.src)" style="cursor:pointer; height:100px; object-fit:cover;">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; endif; ?>

                                <div class="mt-2 text-right">
                                    <button class="btn btn-sm btn-info" onclick='editDiary(<?= json_encode($d) ?>)'>Sửa</button>
                                    <a href="<?= url('tour_diary_delete') ?>&id=<?= $d['id'] ?>&tour_id=<?= $tour_id ?>" 
                                       class="btn btn-sm btn-danger" onclick="return confirm('Xóa nhật ký này?')">Xóa</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<div class="modal fade" id="modalDiary" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= url('tour_diary_store') ?>" method="POST" enctype="multipart/form-data" id="diaryForm">
                <input type="hidden" name="tour_id" value="<?= $tour_id ?>">
                <input type="hidden" name="id" id="diary_id"> <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Thêm Nhật Ký Mới</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Hướng dẫn viên</label>
                        <select name="guide_id" id="diary_guide_id" class="form-control">
                            <option value="">-- Chọn HDV --</option>
                            <?php foreach (($guides ?? []) as $g): ?>
                                <option value="<?= (int)$g['id'] ?>"><?= htmlspecialchars($g['full_name'] ?? ('HDV #'.$g['id'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Loại tin</label>
                            <select name="diary_type" id="diary_type" class="form-control" onchange="toggleIncident(this.value)">
                                <option value="journey">📍 Hành trình (Check-in)</option>
                                <option value="incident">⚠️ Sự cố phát sinh</option>
                                <option value="feedback">💬 Phản hồi khách hàng</option>
                            </select>
                        </div>
                        <div class="col-md-8 form-group">
                            <label>Tiêu đề (ngắn gọn)</label>
                            <input type="text" name="title" id="diary_title" class="form-control" placeholder="Vd: Check-in khách sạn, Xe hỏng lốp..." required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Vị trí hiện tại</label>
                        <input type="text" name="location" id="diary_location" class="form-control" placeholder="Vd: Nha Trang, Khánh Hòa">
                    </div>

                    <div class="form-group">
                        <label>Nội dung chi tiết</label>
                        <textarea name="content" id="diary_content" rows="4" class="form-control" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group"><label>Thời tiết</label><input type="text" name="weather" id="diary_weather" class="form-control"></div>
                        <div class="col-md-4 form-group"><label>Sức khỏe khách</label><input type="text" name="health_status" id="diary_health" class="form-control"></div>
                        <div class="col-md-4 form-group"><label>Hoạt động đặc biệt</label><input type="text" name="special_activities" id="diary_activities" class="form-control"></div>
                    </div>

                    <div class="form-group"><label>Phản hồi khách</label><textarea name="feedback" id="diary_feedback" rows="2" class="form-control" placeholder="Ý kiến nhận xét của khách..."></textarea></div>

                    <div class="form-group d-none" id="handling_group">
                        <label class="text-danger font-weight-bold">Hướng xử lý / Kết quả</label>
                        <textarea name="handling" id="diary_handling" rows="2" class="form-control" placeholder="Đã gọi cứu hộ, đã đổi phòng cho khách..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group"><label>Đánh giá phối hợp</label>
                            <select name="rating_coordination" id="diary_coordination" class="form-control"><option value="">—</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select>
                        </div>
                        <div class="col-md-6 form-group"><label>Tinh thần làm việc</label>
                            <select name="rating_spirit" id="diary_spirit" class="form-control"><option value="">—</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Hình ảnh đính kèm (Chọn nhiều ảnh)</label>
                        <input type="file" name="images[]" class="form-control-file" multiple accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu Lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Hàm bật tắt form Sự cố (Giữ nguyên)
    function toggleIncident(type) {
        if (type === 'incident') {
            $('#handling_group').removeClass('d-none');
        } else {
            $('#handling_group').addClass('d-none');
        }
    }

    // --- SỬA HÀM NÀY ---
    // Hàm reset form để Thêm mới
    function resetForm() {
        // Sử dụng PHP để in ra URL chính xác
        $('#diaryForm').attr('action', '<?= url('tour_diary_store') ?>'); 
        $('#modalTitle').text('Thêm Nhật Ký Mới');
        $('#diary_id').val('');
        $('#diary_title').val('');
        $('#diary_content').val('');
        $('#diary_location').val('');
        $('#diary_handling').val('');
        $('#diary_guide_id').val('');
        $('#diary_weather').val('');
        $('#diary_health').val('');
        $('#diary_activities').val('');
        $('#diary_feedback').val('');
        $('#diary_coordination').val('');
        $('#diary_spirit').val('');
        $('#diary_type').val('journey').change();
    }

    // --- SỬA HÀM NÀY ---
    // Hàm điền dữ liệu để Sửa
    function editDiary(data) {
        // Mở modal
        $('#modalDiary').modal('show');
        
        // Sử dụng PHP để in ra URL chính xác
        $('#diaryForm').attr('action', '<?= url('tour_diary_update') ?>'); 
        $('#modalTitle').text('Cập Nhật Nhật Ký');

        // Điền dữ liệu
        $('#diary_id').val(data.id);
        $('#diary_type').val(data.diary_type).change();
        $('#diary_title').val(data.title);
        $('#diary_content').val(data.content);
        $('#diary_location').val(data.location);
        $('#diary_handling').val(data.handling);

        // Parse content to prefill extra fields
        try {
            const lines = (data.content || '').split(/\r?\n/).map(s=>s.trim());
            const findVal = (prefix) => {
                const ln = lines.find(l => l.toLowerCase().startsWith(prefix.toLowerCase()));
                return ln ? ln.substring(prefix.length).trim() : '';
            };
            const hdv = findVal('HDV:');
            const weather = findVal('Thời tiết:');
            const health = findVal('Sức khỏe khách:');
            const activities = findVal('Hoạt động đặc biệt:');
            const feedback = findVal('Phản hồi khách:');
            const coord = findVal('Đánh giá phối hợp:');
            const spirit = findVal('Tinh thần làm việc:');
            $('#diary_weather').val(weather.replace(/^:/,''));
            $('#diary_health').val(health.replace(/^:/,''));
            $('#diary_activities').val(activities.replace(/^:/,''));
            $('#diary_feedback').val(feedback.replace(/^:/,''));
            $('#diary_coordination').val(coord.replace('/5',''));
            $('#diary_spirit').val(spirit.replace('/5',''));
            // HDV: không thể map ngược ID, giữ trống để người dùng chọn nếu cần
            $('#diary_guide_id').val('');
        } catch (e) {}
    }
</script>
