<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-comments"></i> Phản Hồi Khách Hàng
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($feedbacks)): ?>
                        <div class="row">
                            <?php foreach ($feedbacks as $feedback): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-primary">
                                        <div class="card-body">
                                            <h6 class="card-title"><?= htmlspecialchars($feedback['tour_name']) ?></h6>
                                            <p class="text-muted">
                                                <small>Từ: <?= htmlspecialchars($feedback['full_name']) ?></small>
                                            </p>
                                            <?php if (!empty($feedback['rating'])): ?>
                                            <div class="rating mb-2">
                                                <?php for ($i = 0; $i < intval($feedback['rating']); $i++): ?>
                                                    <i class="fas fa-star text-warning"></i>
                                                <?php endfor; ?>
                                                <span class="ms-2"><?= intval($feedback['rating']) ?>/5</span>
                                            </div>
                                            <?php endif; ?>
                                            <p class="card-text"><?= htmlspecialchars($feedback['content']) ?></p>
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($feedback['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Bạn chưa có phản hồi nào từ khách hàng.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
