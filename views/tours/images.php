<div class="container-fluid">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h2 mb-1 text-gray-800"><i class="fas fa-images"></i> Quản Lý Hình Ảnh Tour</h1>
            <p class="text-muted mb-0">
                <span class="badge badge-info"><?= htmlspecialchars($tour['tour_code'] ?? 'N/A') ?></span>
                <span class="badge badge-primary" style="margin-left: 10px;"><?= htmlspecialchars($tour['name'] ?? 'N/A') ?></span>
            </p>
        </div>
        <a href="index.php?action=tours_detail&id=<?= $tour['id'] ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại Chi Tiết Tour
        </a>
    </div>

    <!-- TỔNG HỢP -->
    <div class="row mb-4">
        <div class="col-lg-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted text-uppercase small">Tổng Hình Ảnh</h6>
                    <h2 class="text-primary font-weight-bold"><?= count($images ?? []) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted text-uppercase small">Ảnh Chính</h6>
                    <h2 class="text-success font-weight-bold">
                        <?= count(array_filter($images ?? [], fn($img) => $img['is_main'] == 1)) ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body">
                    <button class="btn btn-primary w-100" onclick="location.href='index.php?action=tours_upload_image&tour_id=<?= $tour['id'] ?>'">
                        <i class="fas fa-cloud-upload-alt"></i> Tải Lên Hình Ảnh Mới
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- DANH SÁCH HÌNH ẢNH -->
        <div class="col-lg-9">
            <div class="card shadow">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-layer-group"></i> Các Hình Ảnh của Tour</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($images)): ?>
                        <div class="row" id="imageGallery">
                            <?php foreach ($images as $idx => $image): ?>
                                <div class="col-md-4 mb-3 image-item" data-image-id="<?= $image['id'] ?>">
                                    <div class="card shadow-sm border">
                                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                                            <img src="<?= htmlspecialchars($image['image_url'] ?? 'assets/img/placeholder.png') ?>" 
                                                 alt="Tour Image" 
                                                 class="img-fluid" 
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                            
                                            <!-- Badge Ảnh Chính -->
                                            <?php if ($image['is_main'] == 1): ?>
                                                <span class="badge badge-success position-absolute" style="top: 10px; right: 10px;">
                                                    <i class="fas fa-star"></i> Ảnh Chính
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="card-body p-2">
                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                <?php if ($image['is_main'] != 1): ?>
                                                    <button type="button" class="btn btn-warning btn-sm" 
                                                            onclick="setMainImage(<?= $image['id'] ?>, <?= $tour['id'] ?>)"
                                                            title="Đặt làm ảnh chính">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <button type="button" class="btn btn-info btn-sm" 
                                                        onclick="viewImage('<?= htmlspecialchars($image['image_url']) ?>')"
                                                        title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="deleteImage(<?= $image['id'] ?>, <?= $tour['id'] ?>)"
                                                        title="Xóa ảnh">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-image"></i> Chưa có hình ảnh nào. 
                            <a href="index.php?action=tours_upload_image&tour_id=<?= $tour['id'] ?>" class="alert-link">Tải lên hình ảnh</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ẢNH CHÍNH -->
        <div class="col-lg-3">
            <div class="card shadow sticky-top" style="top: 20px;">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-crown"></i> Ảnh Chính</h6>
                </div>
                <div class="card-body">
                    <?php 
                        $mainImage = array_filter($images ?? [], fn($img) => $img['is_main'] == 1);
                        $mainImage = reset($mainImage);
                    ?>
                    <?php if ($mainImage): ?>
                        <img src="<?= htmlspecialchars($mainImage['image_url']) ?>" 
                             alt="Main Image" 
                             class="img-fluid rounded mb-3" 
                             style="width: 100%; height: auto;">
                        <p class="text-muted small text-center">
                            Cập nhật: <?= date('d/m/Y H:i', strtotime($mainImage['created_at'] ?? '')) ?>
                        </p>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Chưa có ảnh chính
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xem Ảnh -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xem Hình Ảnh</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function viewImage(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    $('#imageModal').modal('show');
}

function setMainImage(imageId, tourId) {
    if (confirm('Đặt hình ảnh này làm ảnh chính?')) {
        location.href = 'index.php?action=tours_set_main_image&id=' + imageId + '&tour_id=' + tourId;
    }
}

function deleteImage(imageId, tourId) {
    if (confirm('Bạn chắc chắn muốn xóa hình ảnh này?')) {
        location.href = 'index.php?action=tours_delete_image&id=' + imageId + '&tour_id=' + tourId;
    }
}
</script>
