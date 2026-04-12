<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">
                    <i class="fas fa-map-location-dot"></i> Lịch Trình Tour: <?= htmlspecialchars($tour['tour_name'] ?? '') ?>
                </h1>
                <a href="index.php?action=guide_assigned_tours" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay Lại
                </a>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <?php if (!empty($itinerary)): ?>
                        <?php foreach ($itinerary as $day): ?>
                        <div class="card mb-4 border-left-primary">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-check"></i> 
                                    Ngày <?= htmlspecialchars($day['day_number'] ?? '') ?>: <?= htmlspecialchars($day['title'] ?? '') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="mb-1"><strong>HOẠT ĐỘNG</strong></p>
                                        <p><?= htmlspecialchars($day['activity'] ?? 'N/A') ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>BỮA ĂN</strong></p>
                                        <p class="text-info">
                                            <i class="fas fa-utensils"></i> 
                                            <?= htmlspecialchars($day['meals'] ?? 'N/A') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Chưa có lịch trình nào cho tour này.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

