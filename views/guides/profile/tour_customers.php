<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i> Danh Sách Khách Hàng: <?= htmlspecialchars($tour['name']) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($customers)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Họ và Tên</th>
                                        <th>Số Điện Thoại</th>
                                        <th>Email</th>
                                        <th>Số Người</th>
                                        <th>Yêu Cầu Đặc Biệt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $customer): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($customer['full_name']) ?></strong></td>
                                            <td><?= htmlspecialchars($customer['phone'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($customer['email'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($customer['number_of_people']) ?></td>
                                            <td><?= htmlspecialchars($customer['special_requests'] ?? 'Không có') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Chưa có khách hàng nào được xác nhận cho tour này.
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="?action=guide_assigned_tours" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay Lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>