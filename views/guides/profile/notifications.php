<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell"></i> Thông Báo
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($notifications)): ?>
                        <div class="list-group">
                            <?php foreach ($notifications as $notification): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= htmlspecialchars($notification['title']) ?></h6>
                                        <small><?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Bạn không có thông báo nào.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
