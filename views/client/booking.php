<?php
$errors = $_SESSION['errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];

// Clear session data
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
?>

<style>
    .booking-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .booking-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .booking-header h1 {
        font-size: 32px;
        color: #667eea;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .booking-header p {
        color: #666;
        font-size: 16px;
    }

    /* Error Messages */
    .form-errors {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
        border-left: 4px solid #ef4444;
        color: #dc2626;
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        animation: slideIn 0.3s ease;
    }

    .form-errors h4 {
        margin: 0 0 10px 0;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
    }

    .form-errors ul {
        margin: 0;
        padding-left: 20px;
    }

    .form-errors li {
        margin: 5px 0;
        font-size: 14px;
    }

    /* Form Sections */
    .booking-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .booking-section h3 {
        font-size: 16px;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .booking-section h3 i {
        font-size: 20px;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background-color: rgba(102, 126, 234, 0.02);
    }

    .form-control-info {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }

    /* Radio Group */
    .radio-group {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-check input[type="radio"] {
        width: auto;
        margin: 0;
        cursor: pointer;
    }

    .form-check label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
    }

    /* Customer List */
    .customer-list {
        display: none;
        margin-top: 20px;
    }

    .customer-list.show {
        display: block;
    }

    .customer-row {
        background: #f9fafb;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid #e5e7eb;
        position: relative;
    }

    .customer-row h5 {
        margin-bottom: 15px;
        color: #333;
        font-size: 14px;
        font-weight: 600;
    }

    .customer-row .row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .customer-row .form-group {
        margin-bottom: 0;
    }

    .btn-remove-customer {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #ef4444;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
    }

    .btn-remove-customer:hover {
        background: #dc2626;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #666;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .btn-add {
        background: #10b981;
        color: white;
        margin-top: 15px;
    }

    .btn-add:hover {
        background: #059669;
    }

    /* Price Summary */
    .price-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
        text-align: center;
    }

    .price-summary .label {
        font-size: 12px;
        text-transform: uppercase;
        opacity: 0.9;
    }

    .price-summary .amount {
        font-size: 28px;
        font-weight: 700;
        margin: 5px 0;
    }

    .price-summary .note {
        font-size: 12px;
        opacity: 0.8;
        margin-top: 10px;
    }

    /* Slots Info */
    .slots-info {
        background: #f0f9ff;
        border-left: 4px solid #0ea5e9;
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 14px;
        color: #0369a1;
        margin: 15px 0;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="booking-container">
    <div class="booking-header">
        <h1>📅 Đặt Tour</h1>
        <p>Hãy chọn tour yêu thích và điền thông tin chi tiết</p>
    </div>

    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <h4>⚠️ Có lỗi trong biểu mẫu</h4>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo url('?action=client_booking_submit'); ?>" id="bookingForm">
        <!-- Section 1: Booking Type -->
        <div class="booking-section">
            <h3><i class="fa fa-list"></i> Loại Đặt Tour</h3>
            <div class="radio-group">
                <div class="form-check">
                    <input type="radio" id="retail" name="booking_type" value="retail"
                           <?php echo ($formData['booking_type'] ?? '') === 'retail' ? 'checked' : ''; ?> required>
                    <label for="retail">🧑 Đặt Cá Nhân (Retail)</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="group" name="booking_type" value="group"
                           <?php echo ($formData['booking_type'] ?? '') === 'group' ? 'checked' : ''; ?> required>
                    <label for="group">👥 Đặt Đoàn (Group)</label>
                </div>
            </div>
        </div>

        <!-- Section 2: Tour Selection -->
        <div class="booking-section">
            <h3><i class="fa fa-map"></i> Chọn Tour</h3>
            <div class="form-group">
                <label for="tour_id">Tours</label>
                <select name="tour_id" id="tour_id" required onchange="loadDepartures()">
                    <option value="">-- Chọn tour --</option>
                    <?php foreach ($tours as $tour): ?>
                        <option value="<?php echo $tour['id']; ?>"
                                data-price="<?php echo $tour['price']; ?>"
                                data-slots="<?php echo $tour['available_slots']; ?>"
                                <?php echo ($formData['tour_id'] ?? '') == $tour['id'] ? 'selected' : ''; ?>>
                            <?php echo $tour['name']; ?> (<?php echo number_format($tour['price'])?>đ/người)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-control-info">Chọn một tour từ danh sách có sẵn</div>
            </div>

            <!-- Tour Details (dynamic) -->
            <div id="tourDetails" style="display: none;">
                <div class="slots-info" id="slotsInfo"></div>
            </div>
        </div>

        <!-- Section 3: Departure Date -->
        <div class="booking-section">
            <h3><i class="fa fa-calendar"></i> Ngày Khởi Hành</h3>
            <div class="form-group">
                <label for="departure_id">Chọn Ngày</label>
                <select name="departure_id" id="departure_id">
                    <option value="">-- Chọn ngày khởi hành --</option>
                </select>
                <div class="form-control-info">Tùy chọn: Nếu không chọn, hệ thống sẽ gán ngày gần nhất</div>
            </div>
        </div>

        <!-- Section 4: Number of People -->
        <div class="booking-section">
            <h3><i class="fa fa-users"></i> Số Người Đi</h3>
            <div class="form-group">
                <label for="number_of_people">Số Lượng</label>
                <input type="number" name="number_of_people" id="number_of_people"
                       value="<?php echo $formData['number_of_people'] ?? '1'; ?>"
                       min="1" max="100" required onchange="updatePrice()">
                <div class="form-control-info">Nhập số lượng khách du lịch</div>
            </div>

            <!-- Price Summary -->
            <div class="price-summary">
                <div class="label">Tổng Tiền</div>
                <div class="amount" id="totalPrice">0đ</div>
                <div class="note">Giá = Giá tour × Số người</div>
            </div>
        </div>

        <!-- Section 5: Customer List -->
        <div class="booking-section">
            <h3><i class="fa fa-address-book"></i> Danh Sách Khách</h3>
            <div class="form-check">
                <input type="checkbox" id="addCustomerList" onchange="toggleCustomerList()">
                <label for="addCustomerList">Thêm danh sách khách chi tiết</label>
            </div>
            <div class="form-control-info">Tùy chọn: Nhấp để thêm thông tin chi tiết từng khách</div>

            <div class="customer-list" id="customerList">
                <div id="customerRows"></div>
                <button type="button" class="btn btn-add" onclick="addCustomerRow()">+ Thêm Khách</button>
            </div>

            <!-- Hidden input for customer list JSON -->
            <input type="hidden" name="customer_list" id="customerListInput" value="">
        </div>

        <!-- Section 6: Special Requests -->
        <div class="booking-section">
            <h3><i class="fa fa-comment"></i> Ghi Chú & Yêu Cầu Đặc Biệt</h3>
            <div class="form-group">
                <label for="special_requests">Ghi Chú</label>
                <textarea name="special_requests" id="special_requests" rows="5"
                          placeholder="VD: Yêu cầu chế độ ăn riêng, cần hỗ trợ di chuyển, ..."><?php echo $formData['special_requests'] ?? ''; ?></textarea>
                <div class="form-control-info">Nhập bất kỳ yêu cầu đặc biệt hoặc thông tin thêm</div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">✓ Đặt Tour Ngay</button>
            <a href="<?php echo url('?action=client'); ?>" class="btn btn-secondary">← Quay Lại</a>
        </div>
    </form>
</div>

<script>
// Load departures when tour changes
function loadDepartures() {
    const tourSelect = document.getElementById('tour_id');
    const tour = tourSelect.options[tourSelect.selectedIndex];
    const tourId = tourSelect.value;

    // Show slots info
    if (tourId) {
        document.getElementById('tourDetails').style.display = 'block';
        document.getElementById('slotsInfo').textContent = '✓ Còn ' + tour.dataset.slots + ' chỗ trống';
        updatePrice();
    } else {
        document.getElementById('tourDetails').style.display = 'none';
    }

    // Fetch departures (simplified - in production would be AJAX)
    // For now, just clear the dropdown
    const departureSelect = document.getElementById('departure_id');
    departureSelect.innerHTML = '<option value="">-- Chọn ngày khởi hành --</option>';
}

// Update price calculation
function updatePrice() {
    const tourSelect = document.getElementById('tour_id');
    const tour = tourSelect.options[tourSelect.selectedIndex];
    const numberOfPeople = parseInt(document.getElementById('number_of_people').value) || 1;
    const price = parseInt(tour.dataset.price) || 0;
    const total = price * numberOfPeople;

    document.getElementById('totalPrice').textContent = total.toLocaleString('vi-VN') + 'đ';
}

// Toggle customer list section
function toggleCustomerList() {
    const checkbox = document.getElementById('addCustomerList');
    const customerList = document.getElementById('customerList');
    if (checkbox.checked) {
        customerList.classList.add('show');
        addCustomerRow(); // Add one empty row
    } else {
        customerList.classList.remove('show');
        document.getElementById('customerRows').innerHTML = '';
        document.getElementById('customerListInput').value = '';
    }
}

// Add customer row
function addCustomerRow() {
    const container = document.getElementById('customerRows');
    const rowIndex = container.children.length;
    const html = `
        <div class="customer-row" data-index="${rowIndex}">
            <button type="button" class="btn-remove-customer" onclick="removeCustomerRow(${rowIndex})">✕ Xóa</button>
            <h5>Khách Hàng ${rowIndex + 1}</h5>
            <div class="row">
                <div class="form-group">
                    <label>Tên Đầy Đủ</label>
                    <input type="text" class="customer-name" placeholder="VD: Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="customer-email" placeholder="VD: nguyenvana@email.com" required>
                </div>
                <div class="form-group">
                    <label>Số Điện Thoại</label>
                    <input type="tel" class="customer-phone" placeholder="VD: 0901234567" pattern="[0-9]{10,11}" required>
                </div>
                <div class="form-group">
                    <label>Tuổi</label>
                    <input type="number" class="customer-age" placeholder="VD: 25" min="1" max="120" required>
                </div>
                <div class="form-group">
                    <label>Số CMND/Passport</label>
                    <input type="text" class="customer-id" placeholder="VD: 123456789">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

// Remove customer row
function removeCustomerRow(index) {
    const row = document.querySelector(`[data-index="${index}"]`);
    if (row) row.remove();
}

// Gather customer list data on submit
document.getElementById('bookingForm').addEventListener('submit', function() {
    const customerRows = document.querySelectorAll('.customer-row');
    const customers = [];

    customerRows.forEach(row => {
        const customer = {
            name: row.querySelector('.customer-name').value,
            email: row.querySelector('.customer-email').value,
            phone: row.querySelector('.customer-phone').value,
            age: row.querySelector('.customer-age').value,
            id_number: row.querySelector('.customer-id').value
        };
        customers.push(customer);
    });

    if (customers.length > 0) {
        document.getElementById('customerListInput').value = JSON.stringify(customers);
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updatePrice();
});
</script>
