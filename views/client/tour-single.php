<?php
// Hiển thị trang chi tiết tour
try {
    if (!isset($tour) || !$tour) {
        echo '<div class="alert alert-danger">Tour không tồn tại</div>';
        exit;
    }

    $tourId = $tour['id'];
    $tourImages = $tour['images'] ?? [];
    $guides = $tour['guides'] ?? [];
    $itinerary = $itinerary ?? [];
    $relatedTours = $relatedTours ?? [];
    $departures = $departures ?? [];

    // Lấy ảnh chính hoặc ảnh đầu tiên
    $mainImage = '';
    if (!empty($tourImages)) {
        foreach ($tourImages as $img) {
            if (isset($img['is_main']) && $img['is_main']) {
                $mainImage = $img['image_url'];
                break;
            }
        }
        if (!$mainImage && isset($tourImages[0])) {
            $mainImage = $tourImages[0]['image_url'];
        }
    }
    if (!$mainImage) {
        $mainImage = '/assets/css/frontend/images/destination-1.jpg';
    }
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <title><?php echo htmlspecialchars($tour['name']); ?> - Du Lịch Thông Minh</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Alex+Brush" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/frontend/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/frontend/animate.css">
    <link rel="stylesheet" href="/assets/css/frontend/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/frontend/owl.theme.default.min.css">
    <link rel="stylesheet" href="/assets/css/frontend/magnific-popup.css">
    <link rel="stylesheet" href="/assets/css/frontend/aos.css">
    <link rel="stylesheet" href="/assets/css/frontend/ionicons.min.css">
    <link rel="stylesheet" href="/assets/css/frontend/bootstrap-datepicker.css">
    <link rel="stylesheet" href="/assets/css/frontend/jquery.timepicker.css">
    <link rel="stylesheet" href="/assets/css/frontend/flaticon.css">
    <link rel="stylesheet" href="/assets/css/frontend/icomoon.css">
    <link rel="stylesheet" href="/assets/css/frontend/style.css">
    <link rel="stylesheet" href="/assets/css/frontend/fonts-fix.css">
  </head>
  <body>

  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="/index.php?action=client">Du Lịch Thông Minh</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="/index.php?action=client" class="nav-link">Trang chủ</a></li>
          <li class="nav-item"><a href="/index.php?action=client_about" class="nav-link">Giới thiệu</a></li>
          <li class="nav-item active"><a href="/index.php?action=client_tour" class="nav-link">Du lịch</a></li>
          <li class="nav-item"><a href="/index.php?action=client_hotel" class="nav-link">Khách sạn</a></li>
          <li class="nav-item"><a href="/index.php?action=client_contact" class="nav-link">Liên hệ</a></li>
          <?php if (isLoggedIn()): ?>
            <li class="nav-item cta"><a href="/index.php?action=client_dashboard" class="nav-link"><i class="icon-user"></i> <?php echo htmlspecialchars(getCurrentUser()['full_name']); ?></a></li>
          <?php else: ?>
            <li class="nav-item cta"><a href="/index.php?action=login" class="nav-link"><span><i class="icon-sign-in"></i> Đăng nhập</span></a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

    <div class="hero-wrap" style="background-image: url('<?php echo htmlspecialchars($mainImage); ?>'); height: 500px;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" style="height: 500px;">
          <div class="col-md-9 ftco-animate text-center">
            <p class="breadcrumbs">
              <span class="mr-2"><a href="/index.php?action=client">Trang chủ</a></span>
              <span class="mr-2"><a href="/index.php?action=client_tour">Du lịch</a></span>
              <span><?php echo htmlspecialchars($tour['name']); ?></span>
            </p>
            <h1 class="mb-3 bread">Chi tiết tour</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section ftco-degree-bg">
      <div class="container">
        <div class="row">
          <!-- SIDEBAR -->
          <div class="col-lg-3 sidebar">
            <div class="sidebar-wrap bg-light ftco-animate">
              <h3 class="heading mb-4">📍 Khởi hành</h3>
              <div class="list-group">
                <?php if (!empty($departures)): ?>
                  <?php foreach ($departures as $dep): ?>
                    <div class="list-group-item">
                      <p class="mb-1"><strong><?php echo date('d/m/Y', strtotime($dep['departure_date'])); ?></strong></p>
                      <small class="text-muted">
                        Còn: <strong><?php echo htmlspecialchars($dep['available_slots'] ?? 0); ?></strong> chỗ
                      </small>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-muted">Hiện chưa có ngày khởi hành</p>
                <?php endif; ?>
              </div>
            </div>

            <div class="sidebar-wrap bg-light ftco-animate mt-4">
              <h3 class="heading mb-4">📊 Thông tin</h3>
              <ul class="list-unstyled">
                <li class="mb-3">
                  <strong>Mã tour:</strong><br>
                  <code><?php echo htmlspecialchars($tour['tour_code'] ?? 'N/A'); ?></code>
                </li>
                <li class="mb-3">
                  <strong>Thời gian:</strong><br>
                  <?php
                    if (!empty($tour['duration'])) {
                      echo htmlspecialchars($tour['duration']) . ' ngày';
                    } else {
                      echo 'Chưa xác định';
                    }
                  ?>
                </li>
                <li class="mb-3">
                  <strong>Sức chứa:</strong><br>
                  <?php echo htmlspecialchars($tour['max_capacity'] ?? 0); ?> người
                </li>
                <li class="mb-3">
                  <strong>Điểm bắt đầu:</strong><br>
                  <?php echo htmlspecialchars($tour['start_location'] ?? 'N/A'); ?>
                </li>
                <li class="mb-3">
                  <strong>Loại:</strong><br>
                  <span class="badge badge-info">
                    <?php
                      $type = $tour['tour_type'] ?? 'domestic';
                      echo $type == 'domestic' ? 'Trong nước' : ($type == 'international' ? 'Quốc tế' : 'Tuỳ chỉnh');
                    ?>
                  </span>
                </li>
                <?php if (!empty($tour['booking_count'])): ?>
                  <li class="mb-3">
                    <strong>Đa người đã đặt:</strong><br>
                    <strong class="text-success"><?php echo htmlspecialchars($tour['booking_count']); ?> đơn</strong>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </div>

          <!-- MAIN CONTENT -->
          <div class="col-lg-9">
            <!-- IMAGES CAROUSEL -->
            <?php if (!empty($tourImages)): ?>
            <div class="row mb-4">
              <div class="col-md-12 ftco-animate">
                <div class="single-slider owl-carousel">
                  <?php if (!empty($tourImages)): ?>
                    <?php foreach ($tourImages as $img): ?>
                    <div class="item">
                      <div class="hotel-img" style="background-image: url('<?php echo htmlspecialchars(!empty($img['image_url']) ? $img['image_url'] : '/assets/css/frontend/images/destination-1.jpg'); ?>');" style="height: 400px;"></div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="item">
                      <div class="hotel-img" style="background-image: url('/assets/css/frontend/images/destination-1.jpg');" style="height: 400px;"></div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <!-- TOUR DETAILS -->
            <div class="row mb-5">
              <div class="col-md-12 hotel-single ftco-animate">
                <span class="badge badge-primary mb-2">Tour nổi bật</span>
                <h2 class="mb-2"><?php echo htmlspecialchars($tour['name']); ?></h2>

                <p class="rate mb-4">
                  <span class="loc">
                    <a href="#"> <i class="icon-map"></i>
                    <?php echo htmlspecialchars($tour['start_location'] ?? 'Việt Nam'); ?>
                    <?php if (!empty($tour['destinations'])): ?>
                      → ... → <?php echo htmlspecialchars(substr($tour['destinations'], 0, 50)); ?>...
                    <?php endif; ?>
                    </a>
                  </span>
                  <span class="star ml-3">
                    <i class="icon-star"></i>
                    <i class="icon-star"></i>
                    <i class="icon-star"></i>
                    <i class="icon-star"></i>
                    <i class="icon-star-o"></i>
                    Chưa có đánh giá
                  </span>
                </p>

                <div class="price-box bg-light p-4 mb-4">
                  <h4 class="text-center">
                    <span class="h2 text-danger">
                      <?php echo number_format($tour['price'], 0, ',', '.'); ?> ₫
                    </span>
                    <small class="text-muted"> / người</small>
                  </h4>
                </div>

                <h5 class="mt-4 mb-3">📝 Mô tả tour</h5>
                <p><?php echo htmlspecialchars($tour['description'] ?? 'Chưa cập nhật mô tả'); ?></p>

                <?php if (!empty($guides)): ?>
                <div class="mt-4 mb-4">
                  <h5>👨‍💼 Hướng dẫn viên</h5>
                  <div class="row">
                    <?php foreach ($guides as $guide): ?>
                    <div class="col-md-6 mb-3">
                      <div class="card">
                        <div class="card-body">
                          <p class="card-title"><strong><?php echo htmlspecialchars($guide['full_name']); ?></strong></p>
                          <p class="card-text mb-1"><small class="text-muted">
                            ☎️ <?php echo htmlspecialchars($guide['phone'] ?? 'N/A'); ?>
                          </small></p>
                        </div>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- ITINERARY -->
            <?php if (!empty($itinerary)): ?>
            <div class="row mb-5">
              <div class="col-md-12 hotel-single ftco-animate">
                <h4 class="mb-4">📅 Lịch trình chi tiết</h4>

                <?php foreach ($itinerary as $day): ?>
                <div class="card mb-3">
                  <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                      Ngày <?php echo htmlspecialchars($day['day_number']); ?>
                      <?php if (!empty($day['title'])): ?>
                        - <?php echo htmlspecialchars($day['title']); ?>
                      <?php endif; ?>
                    </h5>
                  </div>
                  <div class="card-body">
                    <p><?php echo nl2br(htmlspecialchars($day['description'] ?? 'Chưa cập nhật')); ?></p>

                    <?php if (!empty($day['activities'])): ?>
                    <div class="mt-3">
                      <strong>Hoạt động:</strong><br>
                      <?php echo nl2br(htmlspecialchars($day['activities'])); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($day['accommodation'])): ?>
                    <div class="mt-2">
                      <strong>Lưu trú:</strong><br>
                      <?php echo htmlspecialchars($day['accommodation']); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($day['meals'])): ?>
                    <div class="mt-2">
                      <strong>Bữa ăn:</strong><br>
                      <?php echo htmlspecialchars($day['meals']); ?>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

            <!-- DESTINATIONS -->
            <?php if ($tour['destinations']): ?>
            <div class="row mb-5">
              <div class="col-md-12 hotel-single ftco-animate">
                <h4 class="mb-4">🗺️ Điểm đến</h4>
                <p><?php echo nl2br(htmlspecialchars($tour['destinations'])); ?></p>
              </div>
            </div>
            <?php endif; ?>

            <!-- POLICY -->
            <?php if ($tour['policy']): ?>
            <div class="row mb-5">
              <div class="col-md-12 hotel-single ftco-animate">
                <h4 class="mb-4">📋 Chính sách & Điều khoản</h4>
                <div class="alert alert-info">
                  <?php echo nl2br(htmlspecialchars($tour['policy'])); ?>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <!-- BOOKING FORM -->
            <div class="row mb-5">
              <div class="col-md-12 hotel-single ftco-animate">
                <h4 class="mb-4">🎫 Đặt tour</h4>
                <form action="/index.php?action=client_booking_submit" method="POST" class="needs-validation">
                  <input type="hidden" name="tour_id" value="<?php echo htmlspecialchars($tourId); ?>">

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label"><strong>Loại đặt tour</strong></label>
                      <div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="booking_type" id="retail" value="retail" checked>
                          <label class="form-check-label" for="retail">
                            Lẻ (cá nhân/gia đình)
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="booking_type" id="group" value="group">
                          <label class="form-check-label" for="group">
                            Nhóm (công ty/tổ chức)
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6 mb-3">
                      <label class="form-label"><strong>Ngày khởi hành</strong></label>
                      <select name="departure_id" class="form-control">
                        <option value="">-- Chọn ngày khởi hành --</option>
                        <?php foreach ($departures as $dep): ?>
                        <option value="<?php echo htmlspecialchars($dep['id']); ?>">
                          <?php echo date('d/m/Y', strtotime($dep['departure_date'])); ?>
                          (Còn <?php echo htmlspecialchars($dep['available_slots'] ?? 0); ?> chỗ)
                        </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="col-md-6 mb-3">
                      <label class="form-label"><strong>Số người</strong></label>
                      <input type="number" name="number_of_people" class="form-control" min="1" value="1" required>
                      <small class="form-text text-muted">Tối đa: <?php echo htmlspecialchars($tour['max_capacity'] ?? 0); ?> người</small>
                    </div>

                    <div class="col-md-6 mb-3">
                      <label class="form-label"><strong>Tổng tiền (ước tính)</strong></label>
                      <div class="input-group">
                        <input type="text" class="form-control" id="totalPrice" value="<?php echo number_format($tour['price'], 0, ',', '.'); ?> ₫" readonly>
                      </div>
                    </div>

                    <div class="col-md-12 mb-3">
                      <label class="form-label"><strong>Yêu cầu đặc biệt</strong></label>
                      <textarea name="special_requests" class="form-control" rows="3" placeholder="Ví dụ: Yêu cầu ghế cửa, ăn chay,..."></textarea>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <?php if (isLoggedIn()): ?>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                          <i class="icon-shopping-cart"></i> Đặt tour ngay
                        </button>
                      <?php else: ?>
                        <a href="/index.php?action=login" class="btn btn-primary btn-lg w-100">
                          <i class="icon-sign-in"></i> Đăng nhập để đặt
                        </a>
                      <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                      <a href="/index.php?action=client_tour" class="btn btn-secondary btn-lg w-100">
                        <i class="icon-arrow-left"></i> Quay lại
                      </a>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!-- RELATED TOURS -->
            <?php if (!empty($relatedTours)): ?>
            <div class="row mb-5">
              <div class="col-md-12 hotel-single ftco-animate">
                <h4 class="mb-4">🎒 Tour liên quan</h4>
                <div class="row">
                  <?php foreach ($relatedTours as $related): ?>
                  <div class="col-md-4 mb-4">
                    <div class="destination">
                      <a href="/index.php?action=client_tour_single&id=<?php echo htmlspecialchars($related['id']); ?>"
                         class="img img-2"
                         style="background-image: url('/assets/css/frontend/images/destination-1.jpg');"></a>
                      <div class="text p-3">
                        <div class="d-flex">
                          <div class="one">
                            <h5><a href="/index.php?action=client_tour_single&id=<?php echo htmlspecialchars($related['id']); ?>">
                              <?php echo htmlspecialchars(substr($related['name'], 0, 30)); ?>...
                            </a></h5>
                            <p class="rate">
                              <i class="icon-star"></i>
                              <i class="icon-star"></i>
                              <i class="icon-star"></i>
                              <i class="icon-star"></i>
                              <i class="icon-star-o"></i>
                            </p>
                          </div>
                          <div class="two">
                            <span class="price"><?php echo number_format($related['price'], 0, ',', '.'); ?> ₫</span>
                          </div>
                        </div>
                        <p class="text-muted text-truncate"><?php echo htmlspecialchars(substr($related['description'] ?? '', 0, 60)); ?></p>
                        <hr>
                        <p class="bottom-area d-flex">
                          <span><i class="icon-map-o"></i> <?php echo htmlspecialchars(substr($related['start_location'] ?? 'Việt Nam', 0, 20)); ?></span>
                          <span class="ml-auto"><a href="/index.php?action=client_tour_single&id=<?php echo htmlspecialchars($related['id']); ?>">Xem</a></span>
                        </p>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Du Lịch Thông Minh</h2>
              <p>Khám phá những điểm đến tuyệt đẹp nhất của Việt Nam với những trải nghiệm không quên.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-5">
              <h2 class="ftco-heading-2">Thông tin</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">Về chúng tôi</a></li>
                <li><a href="#" class="py-2 d-block">Dịch vụ</a></li>
                <li><a href="#" class="py-2 d-block">Điều khoản &amp; Điều kiện</a></li>
                <li><a href="#" class="py-2 d-block">Trở thành đối tác</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Hỗ trợ</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">FAQ</a></li>
                <li><a href="#" class="py-2 d-block">Phương thức thanh toán</a></li>
                <li><a href="#" class="py-2 d-block">Liên hệ</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Liên hệ</h2>
              <div class="block-23 mb-3">
                <ul>
                  <li><span class="icon icon-map-marker"></span><span class="text">123 Đường Nguyễn Huệ, Q.1, TP.HCM</span></li>
                  <li><a href="tel:+84283829291"><span class="icon icon-phone"></span><span class="text">+84 28 3829 2910</span></a></li>
                  <li><a href="mailto:info@dulichthongminh.vn"><span class="icon icon-envelope"></span><span class="text">info@dulichthongminh.vn</span></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
            <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> Du Lịch Thông Minh</p>
          </div>
        </div>
      </div>
    </footer>

  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

  <script src="/assets/css/frontend/js/jquery.min.js"></script>
  <script src="/assets/css/frontend/js/popper.min.js"></script>
  <script src="/assets/css/frontend/js/bootstrap.min.js"></script>
  <script src="/assets/css/frontend/js/jquery.easing.1.3.js"></script>
  <script src="/assets/css/frontend/js/jquery.waypoints.min.js"></script>
  <script src="/assets/css/frontend/js/jquery.stellar.min.js"></script>
  <script src="/assets/css/frontend/js/owl.carousel.min.js"></script>
  <script src="/assets/css/frontend/js/jquery.magnific-popup.min.js"></script>
  <script src="/assets/css/frontend/js/aos.js"></script>
  <script src="/assets/css/frontend/js/jquery.animateNumber.min.js"></script>
  <script src="/assets/css/frontend/js/bootstrap-datepicker.js"></script>
  <script src="/assets/css/frontend/js/jquery.timepicker.min.js"></script>
  <script src="/assets/css/frontend/js/scrollax.min.js"></script>
  <script src="/assets/css/frontend/js/range.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&loading=async"></script>
  <script src="/assets/css/frontend/js/google-map.js"></script>
  <script src="/assets/css/frontend/js/main.js"></script>

  <script>
    // Tính toán tổng tiền
    document.querySelectorAll('input[name="number_of_people"], select[name="departure_id"]').forEach(el => {
      el.addEventListener('change', function() {
        const price = <?php echo (int)$tour['price']; ?>;
        const qty = parseInt(document.querySelector('input[name="number_of_people"]').value) || 1;
        document.getElementById('totalPrice').value = new Intl.NumberFormat('vi-VN').format(price * qty) + ' ₫';
      });
    });
  </script>
  </body>
</html>
<?php } catch (Exception $e) {
    echo '<div class="alert alert-danger">Lỗi: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>
