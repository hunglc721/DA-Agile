<div class="container-fluid">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-800"><i class="fas fa-map-marked-alt"></i> Lịch Trình Chi Tiết</h1>
            <p class="text-muted mb-0">
                <span class="badge badge-info"><?= htmlspecialchars($tour['tour_code'] ?? 'N/A') ?></span>
                <span class="badge badge-primary" style="margin-left: 10px;"><?= htmlspecialchars($tour['name'] ?? 'N/A') ?></span>
            </p>
        </div>
        <a href="index.php?action=tours_detail&id=<?= $tour['id'] ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại Chi Tiết Tour
        </a>
    </div>

    <!-- THÔNG TIN TOUR -->
    <div class="row mb-4">
        <div class="col-lg-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted text-uppercase small">Tổng Số Ngày</h6>
                    <h2 class="text-primary font-weight-bold"><?= count($itinerary ?? []) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted text-uppercase small">Điểm Khởi Hành</h6>
                    <p class="mb-0 font-weight-bold"><?= htmlspecialchars($tour['start_location'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted text-uppercase small">Ngày Khởi Hành</h6>
                    <p class="mb-0 font-weight-bold"><?= date('d/m/Y', strtotime($tour['start_date'] ?? date('Y-m-d'))) ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted text-uppercase small">Loại Lưu Trú</h6>
                    <p class="mb-0 font-weight-bold"><?= htmlspecialchars($tour['accommodation_type'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- TIMELINE VIEW -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-calendar-alt"></i> Lịch Trình Chi Tiết</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($itinerary)): ?>
                        <div class="timeline">
                            <?php foreach ($itinerary as $day): ?>
                                <div class="timeline-item mb-5 pb-4 border-bottom">
                                    <!-- Day Header -->
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="badge badge-primary rounded-circle p-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold;">
                                            <?= $day['day_number'] ?>
                                        </div>
                                        <div class="ml-3 flex-grow-1">
                                            <h6 class="font-weight-bold mb-1">
                                                <?= date('d/m/Y', strtotime($tour['start_date']) + ($day['day_number'] - 1) * 86400) ?>
                                            </h6>
                                            <h5 class="mb-0"><?= htmlspecialchars($day['title']) ?></h5>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary" onclick="editDay(<?= $day['id'] ?>)">
                                                <i class="fas fa-edit"></i> Sửa
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="deleteDay(<?= $day['id'] ?>)">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Day Content -->
                                    <div class="ml-5">
                                        <!-- Activities -->
                                        <div class="mb-3">
                                            <p class="text-muted small font-weight-bold mb-1">📋 Hoạt Động:</p>
                                            <p class="ml-2 mb-2"><?= nl2br(htmlspecialchars($day['activity'])) ?></p>
                                        </div>

                                        <!-- Meals -->
                                        <div class="mb-3">
                                            <p class="text-muted small font-weight-bold mb-1">🍽️ Ăn Uống:</p>
                                            <div class="ml-2">
                                                <?php
                                                $meals = explode(',', $day['meals']);
                                                foreach ($meals as $meal):
                                                    $meal = trim($meal);
                                                    if (!empty($meal)):
                                                ?>
                                                    <span class="badge badge-info mr-2 mb-2"><?= htmlspecialchars($meal) ?></span>
                                                <?php endif; endforeach; ?>
                                            </div>
                                        </div>

                                        <!-- Accommodation -->
                                        <?php if (!empty($day['accommodation'])): ?>
                                            <div class="mb-3">
                                                <p class="text-muted small font-weight-bold mb-1">🏨 Lưu Trú:</p>
                                                <p class="ml-2 mb-0">
                                                    <strong><?= htmlspecialchars($day['accommodation']) ?></strong>
                                                </p>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Additional Info -->
                                        <?php if (!empty($day['notes'])): ?>
                                            <div class="alert alert-light mt-3 mb-0">
                                                <p class="small mb-0">
                                                    <strong>📝 Ghi Chú:</strong> <?= nl2br(htmlspecialchars($day['notes'])) ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Chưa có lịch trình. Hãy thêm ngày mới!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <!-- ADD NEW DAY -->
            <div class="card shadow mb-4 border-primary">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-plus"></i> Thêm Ngày Mới</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=tours_itinerary_store" method="POST" id="addDayForm">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

                        <div class="form-group">
                            <label for="day_number"><strong>Ngày Thứ:</strong></label>
                            <input type="number" class="form-control" id="day_number" name="day_number" 
                                   value="<?= (max(array_column($itinerary, 'day_number')) ?? 0) + 1 ?>" min="1" required>
                        </div>

                        <div class="form-group">
                            <label for="title"><strong>Tiêu Đề Ngày:</strong></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   placeholder="VD: Hà Nội - Hạ Long" required>
                        </div>

                        <div class="form-group">
                            <label for="activity"><strong>Hoạt Động:</strong></label>
                            <textarea class="form-control" id="activity" name="activity" rows="4" 
                                      placeholder="Mô tả chi tiết hoạt động trong ngày..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="meals"><strong>Ăn Uống:</strong></label>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="meal_breakfast" name="meals" value="Sáng">
                                <label class="custom-control-label" for="meal_breakfast">Sáng</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="meal_lunch" name="meals" value="Trưa">
                                <label class="custom-control-label" for="meal_lunch">Trưa</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="meal_dinner" name="meals" value="Chiều">
                                <label class="custom-control-label" for="meal_dinner">Chiều</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="accommodation"><strong>Lưu Trú:</strong></label>
                            <input type="text" class="form-control" id="accommodation" name="accommodation" 
                                   placeholder="VD: Vinpearl Resort Hạ Long">
                        </div>

                        <div class="form-group">
                            <label for="notes"><strong>Ghi Chú Thêm:</strong></label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Thông tin thêm..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-plus"></i> Thêm Ngày
                        </button>
                    </form>
                </div>
            </div>

            <!-- TOUR SUMMARY -->
            <div class="card shadow">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle"></i> Thông Tin Tour</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Tên Tour:</strong><br>
                        <?= htmlspecialchars($tour['name']) ?>
                    </p>
                    <p class="mb-2">
                        <strong>Thời Lượng:</strong><br>
                        <?= $tour['duration'] ?> ngày
                    </p>
                    <p class="mb-2">
                        <strong>Ngày Khởi Hành:</strong><br>
                        <?= date('d/m/Y', strtotime($tour['start_date'])) ?>
                    </p>
                    <p class="mb-2">
                        <strong>Tổng Ngày Lịch Trình:</strong><br>
                        <span class="badge badge-primary"><?= count($itinerary) ?>/<?= $tour['duration'] ?> ngày</span>
                    </p>
                    <div class="progress mt-3" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: <?= count($itinerary) > 0 ? (count($itinerary) / $tour['duration'] * 100) : 0 ?>%"
                             aria-valuenow="<?= count($itinerary) ?>" aria-valuemin="0" aria-valuemax="<?= $tour['duration'] ?>">
                            <?= number_format((count($itinerary) / $tour['duration'] * 100), 0) ?>%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editDay(dayId) {
    window.location.href = 'index.php?action=tours_itinerary_edit&id=' + dayId + '&tour_id=' + <?= $tour['id'] ?>;
}

function deleteDay(dayId) {
    if (confirm('Bạn có chắc chắn muốn xóa ngày này không?')) {
        window.location.href = 'index.php?action=tours_itinerary_delete&id=' + dayId + '&tour_id=' + <?= $tour['id'] ?>;
    }
}

// Handle checkbox for meals
document.querySelectorAll('input[name="meals"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const meals = Array.from(document.querySelectorAll('input[name="meals"]:checked'))
            .map(c => c.value)
            .join(', ');
        // This will be handled by form submission
    });
});
</script>

<style>
.timeline {
    position: relative;
}

.timeline-item {
    position: relative;
    padding-bottom: 2rem;
}

.badge {
    font-size: 16px;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
