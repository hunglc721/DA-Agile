<div class="container-fluid">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-800"><i class="fas fa-file-contract"></i> Chính Sách & Điều Khoản</h1>
            <p class="text-muted mb-0">
                <span class="badge badge-info"><?= htmlspecialchars($tour['tour_code'] ?? 'N/A') ?></span>
                <span class="badge badge-primary" style="margin-left: 10px;"><?= htmlspecialchars($tour['name'] ?? 'N/A') ?></span>
            </p>
        </div>
        <a href="index.php?action=tours_detail&id=<?= $tour['id'] ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại Chi Tiết Tour
        </a>
    </div>

    <!-- TABS -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active text-white" id="policies-tab" data-toggle="tab" href="#policies" role="tab">
                        <i class="fas fa-list"></i> Chính Sách
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" id="inclusions-tab" data-toggle="tab" href="#inclusions" role="tab">
                        <i class="fas fa-check-circle"></i> Bao Gồm/Không Bao Gồm
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <!-- POLICIES TAB -->
            <div class="tab-pane fade show active" id="policies" role="tabpanel">
                <div class="mb-3">
                    <button class="btn btn-success" onclick="location.href='index.php?action=policy_create&tour_id=<?= $tour['id'] ?>'">
                        <i class="fas fa-plus"></i> Thêm Chính Sách Mới
                    </button>
                </div>

                <?php if (!empty($policies)): ?>
                    <div class="accordion" id="policiesAccordion">
                        <?php $policyTypes = ['cancellation' => 'Hủy Tour', 'refund' => 'Hoàn Tiền', 'payment' => 'Thanh Toán', 'inclusion' => 'Bao Gồm', 'exclusion' => 'Không Bao Gồm', 'other' => 'Khác']; ?>
                        
                        <?php foreach ($policyTypes as $type => $typeName): ?>
                            <?php $typePolicies = array_filter($policies, fn($p) => $p['policy_type'] === $type); ?>
                            <?php if (!empty($typePolicies)): ?>
                                <div class="card mb-2 border">
                                    <div class="card-header p-0" id="heading_<?= $type ?>">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left p-3" type="button" data-toggle="collapse" data-target="#collapse_<?= $type ?>">
                                                <strong><?= $typeName ?></strong> 
                                                <span class="badge badge-info float-right"><?= count($typePolicies) ?></span>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapse_<?= $type ?>" class="collapse" data-parent="#policiesAccordion">
                                        <div class="card-body">
                                            <?php foreach ($typePolicies as $policy): ?>
                                                <div class="mb-3 pb-3 border-bottom" <?= end($typePolicies) === $policy ? 'style="border-bottom: none;"' : '' ?>>
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="font-weight-bold"><?= htmlspecialchars($policy['title']) ?></h6>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-info" onclick="editPolicy(<?= $policy['id'] ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-danger" onclick="deletePolicy(<?= $policy['id'] ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="mb-0 text-muted"><?= nl2br(htmlspecialchars(substr($policy['content'], 0, 200))) ?>...</p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Chưa có chính sách nào
                    </div>
                <?php endif; ?>
            </div>

            <!-- INCLUSIONS TAB -->
            <div class="tab-pane fade" id="inclusions" role="tabpanel">
                <div class="mb-3">
                    <button class="btn btn-success" onclick="location.href='index.php?action=inclusion_create&tour_id=<?= $tour['id'] ?>'">
                        <i class="fas fa-plus"></i> Thêm Bao Gồm/Không Bao Gồm Mới
                    </button>
                </div>

                <?php if (!empty($inclusions)): ?>
                    <div class="row">
                        <!-- BƯỚC INCLUDED -->
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="m-0 font-weight-bold"><i class="fas fa-check-circle"></i> Bao Gồm Trong Tour</h6>
                                </div>
                                <div class="card-body">
                                    <?php $included = array_filter($inclusions, fn($i) => $i['type'] === 'included'); ?>
                                    <?php if (!empty($included)): ?>
                                        <ul class="list-unstyled">
                                            <?php foreach ($included as $item): ?>
                                                <li class="mb-2 pb-2 border-bottom">
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-check text-success mr-2 mt-1" style="flex-shrink: 0;"></i>
                                                        <div class="flex-grow-1">
                                                            <span><?= htmlspecialchars($item['title']) ?></span>
                                                            <div class="btn-group btn-group-xs float-right">
                                                                <button class="btn btn-sm btn-info" onclick="editInclusion(<?= $item['id'] ?>)">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger" onclick="deleteInclusion(<?= $item['id'] ?>)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <small class="text-muted">Không có mục bao gồm</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- EXCLUDED -->
                        <div class="col-md-6">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="m-0 font-weight-bold"><i class="fas fa-times-circle"></i> Không Bao Gồm</h6>
                                </div>
                                <div class="card-body">
                                    <?php $excluded = array_filter($inclusions, fn($i) => $i['type'] === 'excluded'); ?>
                                    <?php if (!empty($excluded)): ?>
                                        <ul class="list-unstyled">
                                            <?php foreach ($excluded as $item): ?>
                                                <li class="mb-2 pb-2 border-bottom">
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-times text-danger mr-2 mt-1" style="flex-shrink: 0;"></i>
                                                        <div class="flex-grow-1">
                                                            <span><?= htmlspecialchars($item['title']) ?></span>
                                                            <div class="btn-group btn-group-xs float-right">
                                                                <button class="btn btn-sm btn-info" onclick="editInclusion(<?= $item['id'] ?>)">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger" onclick="deleteInclusion(<?= $item['id'] ?>)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <small class="text-muted">Không có mục không bao gồm</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GHI CHÚ -->
                    <?php $notes = array_filter($inclusions, fn($i) => $i['type'] === 'note'); ?>
                    <?php if (!empty($notes)): ?>
                        <div class="card border-warning mt-3">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle"></i> Ghi Chú</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <?php foreach ($notes as $item): ?>
                                        <li class="mb-2 pb-2 border-bottom">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-info-circle text-warning mr-2 mt-1" style="flex-shrink: 0;"></i>
                                                <div class="flex-grow-1">
                                                    <span><?= htmlspecialchars($item['title']) ?></span>
                                                    <div class="btn-group btn-group-xs float-right">
                                                        <button class="btn btn-sm btn-info" onclick="editInclusion(<?= $item['id'] ?>)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="deleteInclusion(<?= $item['id'] ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Chưa có bao gồm/không bao gồm nào
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function editPolicy(id) {
    location.href = 'index.php?action=policy_edit&id=' + id;
}

function deletePolicy(id) {
    if (confirm('Bạn chắc chắn muốn xóa chính sách này?')) {
        location.href = 'index.php?action=policy_delete&id=' + id;
    }
}

function editInclusion(id) {
    location.href = 'index.php?action=inclusion_edit&id=' + id;
}

function deleteInclusion(id) {
    if (confirm('Bạn chắc chắn muốn xóa mục này?')) {
        location.href = 'index.php?action=inclusion_delete&id=' + id;
    }
}
</script>
