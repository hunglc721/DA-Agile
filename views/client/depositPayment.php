<?php
// Trang thanh toán cọc
if (!isset($booking) || !$booking) {
    echo '<div class="alert alert-danger">Không tìm thấy đơn đặt tour</div>';
    exit;
}

$currentUser = getCurrentUser();
$tourModel = new Tour();
$tour = $tourModel->find($booking['tour_id']);
$depositAmount = $depositAmount ?? ($booking['total_price'] * 0.3);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title><?php echo htmlspecialchars($title ?? 'Thanh Toán Cọc'); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/frontend/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/frontend/animate.css">
    <link rel="stylesheet" href="/assets/css/frontend/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/frontend/magnific-popup.css">
    <link rel="stylesheet" href="/assets/css/frontend/ionicons.min.css">
    <link rel="stylesheet" href="/assets/css/frontend/style.css">
    <style>
        .payment-card {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .payment-card:hover {
            border-color: #FF6B6B;
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
        }
        .payment-card.active {
            border-color: #FF6B6B;
            background-color: #fff5f5;
        }
        .price-highlight {
            font-size: 28px;
            font-weight: bold;
            color: #FF6B6B;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .btn-pay {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="/">Du Lịch Thông Minh</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="/client/dashboard" class="nav-link">Tài Khoản</a></li>
                <li class="nav-item"><a href="/logout" class="nav-link">Đăng Xuất</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="mb-4"><i class="fa fa-credit-card"></i> Thanh Toán Cọc</h2>

            <!-- Thông tin booking -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông Tin Đặt Tour</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Mã Đơn:</strong><br>
                            <span class="text-muted">#<?php echo $booking['id']; ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng Thái:</strong><br>
                            <span class="badge badge-warning">Chờ Thanh Toán</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tour:</strong><br>
                            <span class="text-muted"><?php echo htmlspecialchars($tour['name'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Số Người:</strong><br>
                            <span class="text-muted"><?php echo $booking['number_of_people']; ?> người</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin thanh toán -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Chi Tiết Thanh Toán</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Tổng Tiền:</strong>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php echo number_format($booking['total_price'], 0, ',', '.'); ?> ₫
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Cọc (30%):</strong>
                        </div>
                        <div class="col-md-6 text-right">
                            <strong class="text-danger"><?php echo number_format($depositAmount, 0, ',', '.'); ?> ₫</strong>
                        </div>
                    </div>
                    <div class="info-box">
                        <i class="fa fa-info-circle"></i> 
                        Bạn cần thanh toán cọc 30% để xác nhận đơn đặt tour. Phần còn lại có thể thanh toán sau.
                    </div>
                </div>
            </div>

            <!-- Phương thức thanh toán -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Chọn Phương Thức Thanh Toán</h5>
                </div>
                <div class="card-body">
                    <form id="paymentForm">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">

                        <!-- Thẻ Tín Dụng -->
                        <div class="payment-card active" data-method="card">
                            <div class="form-check">
                                <input class="form-check-input payment-method" type="radio" name="payment_method" id="card" value="card" checked>
                                <label class="form-check-label" for="card">
                                    <i class="fa fa-credit-card"></i> <strong>Thẻ Tín Dụng / Thẻ Ghi Nợ</strong>
                                    <br><small class="text-muted">Visa, Mastercard, JCB</small>
                                </label>
                            </div>
                        </div>

                        <!-- Chuyển Khoản Ngân Hàng -->
                        <div class="payment-card" data-method="bank">
                            <div class="form-check">
                                <input class="form-check-input payment-method" type="radio" name="payment_method" id="bank" value="bank">
                                <label class="form-check-label" for="bank">
                                    <i class="fa fa-university"></i> <strong>Chuyển Khoản Ngân Hàng</strong>
                                    <br><small class="text-muted">Chuyển khoản trực tiếp từ ngân hàng</small>
                                </label>
                            </div>
                        </div>

                        <!-- Ví Điện Tử -->
                        <div class="payment-card" data-method="wallet">
                            <div class="form-check">
                                <input class="form-check-input payment-method" type="radio" name="payment_method" id="wallet" value="wallet">
                                <label class="form-check-label" for="wallet">
                                    <i class="fa fa-mobile"></i> <strong>Ví Điện Tử (Momo, ZaloPay)</strong>
                                    <br><small class="text-muted">Thanh toán qua Momo hoặc ZaloPay</small>
                                </label>
                            </div>
                        </div>

                        <div class="price-highlight">
                            <?php echo number_format($depositAmount, 0, ',', '.'); ?> ₫
                        </div>

                        <button type="submit" class="btn btn-danger btn-pay">
                            <i class="fa fa-lock"></i> Tiếp Tục Thanh Toán Cọc
                        </button>
                    </form>
                </div>
            </div>

            <div class="alert alert-info">
                <strong><i class="fa fa-shield"></i> Bảo Mật:</strong> 
                Mọi giao dịch của bạn được bảo vệ bởi các công ty thanh toán đáng tin cậy.
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Thông Tin Tài Khoản</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tên:</strong> <?php echo htmlspecialchars($currentUser['full_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($currentUser['email']); ?></p>
                    <p><strong>Điện Thoại:</strong> <?php echo htmlspecialchars($currentUser['phone'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Hỗ Trợ Khách Hàng</h5>
                </div>
                <div class="card-body">
                    <p><i class="fa fa-phone"></i> <strong>Hotline:</strong> 1900 1234</p>
                    <p><i class="fa fa-envelope"></i> <strong>Email:</strong> support@dulich.vn</p>
                    <p><i class="fa fa-clock"></i> <strong>Giờ Làm Việc:</strong> 8:00 - 18:00</p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="ftco-footer-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2 class="ftco-heading-2">Du Lịch Thông Minh</h2>
                <p>Khám phá những điểm đến tuyệt vời cùng chúng tôi.</p>
            </div>
            <div class="col-md-4 pl-md-5 pl-3">
                <h2 class="ftco-heading-2">Links</h2>
                <ul class="list-unstyled">
                    <li><a href="/">Trang Chủ</a></li>
                    <li><a href="/tours">Tours</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h2 class="ftco-heading-2">Liên Hệ</h2>
                <div class="block-23 mb-3">
                    <ul>
                        <li><span class="icon icon-map-marker"></span><span class="text">TP. Hồ Chí Minh</span></li>
                        <li><a href="tel:+841234567890"><span class="icon icon-phone"></span><span class="text">+84 1234 567 890</span></a></li>
                        <li><a href="mailto:info@dulich.vn"><span class="icon icon-envelope"></span><span class="text">info@dulich.vn</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="/assets/css/frontend/js/jquery.min.js"></script>
<script src="/assets/css/frontend/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Xử lý click trên payment card
    $('.payment-card').on('click', function() {
        $('.payment-card').removeClass('active');
        $(this).addClass('active');
        $(this).find('input[type="radio"]').prop('checked', true);
    });

    // Xử lý submit form
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
        
        $.ajax({
            url: '<?php echo BASE_URL; ?>?action=client_process_deposit_payment',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    alert('✓ ' + response.message);
                    window.location.href = response.redirect;
                } else {
                    alert('✗ ' + response.message);
                }
            },
            error: function(xhr) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    alert('✗ ' + response.message);
                } catch(e) {
                    alert('✗ Có lỗi xảy ra. Vui lòng thử lại.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
</body>
</html>
