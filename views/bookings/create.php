<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary text-white">
        <h6 class="m-0 font-weight-bold">BÁN TOUR & ĐẶT CHỖ</h6>
    </div>
    <div class="card-body">
        <!-- NAV TABS CHO KHÁCH LẺ / ĐOÀN -->
        <ul class="nav nav-tabs mb-4" id="bookingType" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="retail-tab" data-toggle="tab" href="#retail" role="tab">
                    <i class="fas fa-user"></i> Khách Lẻ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="group-tab" data-toggle="tab" href="#group" role="tab">
                    <i class="fas fa-users"></i> Đoàn (10+ người)
                </a>
            </li>
        </ul>

        <!-- FORM KHÁCH LẺ -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="retail" role="tabpanel">
                <form action="index.php?action=bookings_store" method="POST">
                    <input type="hidden" name="booking_type" value="retail">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>Chọn Khách Hàng</strong></label>
                            <select name="user_id" class="form-control" id="user_retail" required>
                                <option value="">-- Chọn khách hàng --</option>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?= $u['id'] ?>" data-phone="<?= $u['phone'] ?>" data-email="<?= $u['email'] ?>">
                                        <?= htmlspecialchars($u['full_name']) ?> (<?= $u['phone'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Nếu không tìm được khách, hãy tạo khách hàng mới trước</small>
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label><strong>Chọn Tour</strong></label>
                            <select name="tour_id" class="form-control tour-select" id="tour_retail" required onchange="updatePrice()">
                                <option value="">-- Chọn tour --</option>
                                <?php foreach ($tours as $t): ?>
                                    <option value="<?= $t['id'] ?>" 
                                            data-price="<?= $t['price'] ?>" 
                                            data-slots="<?= $t['available_slots'] ?>"
                                            data-name="<?= htmlspecialchars($t['name']) ?>">
                                        <?= htmlspecialchars($t['name']) ?> (Còn <?= $t['available_slots'] ?> chỗ - <?= number_format($t['price']) ?>đ/người)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label><strong>Số Lượng Người</strong></label>
                            <input type="number" name="number_of_people" class="form-control" id="people_retail" min="1" value="1" required onchange="updatePrice()">
                            <small class="text-danger" id="slots_warning_retail"></small>
                        </div>

                        <div class="col-md-4 form-group">
                            <label><strong>Giá/Người</strong></label>
                            <input type="text" class="form-control" id="price_per_person" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label><strong>Tổng Tiền</strong></label>
                            <input type="text" class="form-control text-success font-weight-bold" id="total_price_display" value="0 đ" readonly style="font-size: 16px;">
                            <input type="hidden" name="total_price" id="total_price_hidden">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ghi Chú / Yêu Cầu Đặc Biệt (Tùy Chọn)</label>
                        <textarea name="special_requests" class="form-control" rows="2" placeholder="VD: Yêu cầu chỗ ngồi riêng, cần guide ngoại ngữ..."></textarea>
                    </div>

                    <div class="alert alert-info">
                        <strong>Quy Trình:</strong> Booking sẽ ở trạng thái "Chờ xác nhận". Bạn cần duyệt đơn trước khi khách thanh toán.
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check-circle"></i> XÁC NHẬN ĐẶT TOUR
                    </button>
                    <a href="index.php?action=bookings" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times-circle"></i> HỦY
                    </a>
                </form>
            </div>

            <!-- FORM ĐOÀN -->
            <div class="tab-pane fade" id="group" role="tabpanel">
                <form action="index.php?action=bookings_store" method="POST">
                    <input type="hidden" name="booking_type" value="group">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>Tên Đoàn</strong></label>
                            <input type="text" name="group_name" class="form-control" placeholder="VD: Đoàn du lịch công ty ABC" required>
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label><strong>Người Liên Hệ</strong></label>
                            <select name="user_id" class="form-control" id="user_group" required>
                                <option value="">-- Chọn người liên hệ --</option>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?= $u['id'] ?>" data-phone="<?= $u['phone'] ?>" data-email="<?= $u['email'] ?>">
                                        <?= htmlspecialchars($u['full_name']) ?> (<?= $u['phone'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>Chọn Tour</strong></label>
                            <select name="tour_id" class="form-control tour-select" id="tour_group" required onchange="updatePrice()">
                                <option value="">-- Chọn tour --</option>
                                <?php foreach ($tours as $t): ?>
                                    <option value="<?= $t['id'] ?>" 
                                            data-price="<?= $t['price'] ?>" 
                                            data-slots="<?= $t['available_slots'] ?>"
                                            data-name="<?= htmlspecialchars($t['name']) ?>">
                                        <?= htmlspecialchars($t['name']) ?> (Còn <?= $t['available_slots'] ?> chỗ - <?= number_format($t['price']) ?>đ/người)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label><strong>Số Lượng Người Tối Thiểu: 10</strong></label>
                            <input type="number" name="number_of_people" class="form-control" id="people_group" min="10" value="10" required onchange="updatePrice()">
                            <small class="text-danger" id="slots_warning_group"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label><strong>Giá/Người</strong></label>
                            <input type="text" class="form-control" id="group_price_per_person" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label><strong>Giảm Giá Đoàn (%)</strong></label>
                            <input type="number" name="group_discount" class="form-control" id="group_discount" min="0" max="50" value="0" step="0.5" onchange="updatePrice()">
                        </div>

                        <div class="col-md-4 form-group">
                            <label><strong>Tổng Tiền</strong></label>
                            <input type="text" class="form-control text-success font-weight-bold" id="group_total_price_display" value="0 đ" readonly style="font-size: 16px;">
                            <input type="hidden" name="total_price" id="group_total_price_hidden">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Danh Sách Khách (JSON - Tùy Chọn)</strong></label>
                        <textarea name="customer_list" class="form-control" rows="3" placeholder='VD: [{"name":"Nguyễn Văn A","phone":"0901234567"},{"name":"Trần Thị B","phone":"0912345678"}]'></textarea>
                        <small class="form-text text-muted">Để trống nếu chưa có danh sách chi tiết</small>
                    </div>

                    <div class="form-group">
                        <label>Ghi Chú / Yêu Cầu Đặc Biệt</label>
                        <textarea name="special_requests" class="form-control" rows="3" placeholder="Yêu cầu riêng của đoàn..."></textarea>
                    </div>

                    <div class="alert alert-warning">
                        <strong><i class="fas fa-star"></i> Ưu Điểm Đặt Đoàn:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Chiết khấu tối đa 50% tùy theo quy mô</li>
                            <li>Hỗ trợ guide riêng & dịch vụ khác nhau</li>
                            <li>Linh hoạt trong lịch trình & thời gian</li>
                            <li>Ưu tiên trong booking lịch tour</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check-circle"></i> XÁC NHẬN ĐẶT ĐOÀN
                    </button>
                    <a href="index.php?action=bookings" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times-circle"></i> HỦY
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updatePrice() {
    const activeTab = document.querySelector('.nav-link.active').id;
    
    if (activeTab === 'retail-tab') {
        const tourSelect = document.getElementById('tour_retail');
        const peopleInput = document.getElementById('people_retail');
        const selectedOption = tourSelect.options[tourSelect.selectedIndex];
        
        if (selectedOption.value) {
            const price = parseInt(selectedOption.dataset.price);
            const slots = parseInt(selectedOption.dataset.slots);
            const people = parseInt(peopleInput.value) || 1;
            
            document.getElementById('price_per_person').value = Number(price).toLocaleString('vi-VN') + ' đ';
            document.getElementById('total_price_display').value = Number(price * people).toLocaleString('vi-VN') + ' đ';
            document.getElementById('total_price_hidden').value = price * people;
            
            if (people > slots) {
                document.getElementById('slots_warning_retail').textContent = `⚠️ Chỉ còn ${slots} chỗ!`;
                document.querySelector('button[type="submit"]').disabled = true;
            } else {
                document.getElementById('slots_warning_retail').textContent = '';
                document.querySelector('button[type="submit"]').disabled = false;
            }
        }
    } else if (activeTab === 'group-tab') {
        const tourSelect = document.getElementById('tour_group');
        const peopleInput = document.getElementById('people_group');
        const discountInput = document.getElementById('group_discount');
        const selectedOption = tourSelect.options[tourSelect.selectedIndex];
        
        if (selectedOption.value) {
            const price = parseInt(selectedOption.dataset.price);
            const slots = parseInt(selectedOption.dataset.slots);
            const people = parseInt(peopleInput.value) || 10;
            const discount = parseInt(discountInput.value) || 0;
            
            const subtotal = price * people;
            const discountAmount = (subtotal * discount) / 100;
            const total = subtotal - discountAmount;
            
            document.getElementById('group_price_per_person').value = Number(price).toLocaleString('vi-VN') + ' đ';
            document.getElementById('group_total_price_display').value = Number(Math.round(total)).toLocaleString('vi-VN') + ' đ';
            document.getElementById('group_total_price_hidden').value = Math.round(total);
            
            if (people > slots) {
                document.getElementById('slots_warning_group').textContent = `⚠️ Chỉ còn ${slots} chỗ!`;
                document.querySelector('button[type="submit"]').disabled = true;
            } else {
                document.getElementById('slots_warning_group').textContent = '';
                document.querySelector('button[type="submit"]').disabled = false;
            }
        }
    }
}

// Update khi chuyển tab
document.querySelectorAll('.nav-link').forEach(tab => {
    tab.addEventListener('click', function() {
        setTimeout(updatePrice, 100);
    });
});
</script>