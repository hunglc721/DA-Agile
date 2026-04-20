<!-- Contact Hero Section -->
<div class="contact-hero">
    <div class="container-contact">
        <h1>
            <i class="fas fa-envelope"></i> Liên Hệ Với Chúng Tôi
        </h1>
        <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn. Hãy liên hệ ngay hôm nay!</p>
    </div>
</div>

<!-- Main Content -->
<div class="container-contact" style="margin-bottom: 80px;">
    <!-- Contact Info Cards -->
    <div class="contact-info-section">
        <div class="contact-info-grid">
            <!-- Address Card -->
            <div class="contact-card">
                <div class="contact-card-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Địa Chỉ</h3>
                <p>123 Đường Nguyễn Huệ<br>Quận 1, TP. Hồ Chí Minh<br>Việt Nam</p>
            </div>

            <!-- Phone Card -->
            <div class="contact-card">
                <div class="contact-card-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3>Điện Thoại</h3>
                <p>
                    <a href="tel:+84283829291">+84 28 3829 2910</a><br>
                    <a href="tel:+84909876543">+84 90 9876 543</a><br>
                    <small style="color: #999;">Thứ 2-7: 8h-18h</small>
                </p>
            </div>

            <!-- Email Card -->
            <div class="contact-card">
                <div class="contact-card-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p>
                    <a href="mailto:info@dulichthongminh.vn">info@dulichthongminh.vn</a><br>
                    <a href="mailto:support@dulichthongminh.vn">support@dulichthongminh.vn</a><br>
                    <small style="color: #999;">Hỗ trợ 24/7</small>
                </p>
            </div>

            <!-- Website Card -->
            <div class="contact-card">
                <div class="contact-card-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h3>Website</h3>
                <p>
                    <a href="#">dulichthongminh.vn</a><br>
                    <small style="color: #999;">Truy cập 24/7</small>
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Form and Map -->
    <div class="contact-form-section">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0;">
            <!-- Form -->
            <div class="form-wrapper">
                <h2 class="section-title">Gửi Tin Nhắn</h2>
                <p class="form-subtitle">Điền thông tin dưới đây, chúng tôi sẽ phản hồi trong 24 giờ</p>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?= escape($_SESSION['success']) ?></span>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <div><?= escape($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <form method="POST" action="<?= url('client_contact_submit') ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Họ Tên *</label>
                            <input type="text" id="name" name="name" placeholder="Nhập họ tên..." required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số Điện Thoại *</label>
                            <input type="tel" id="phone" name="phone" placeholder="0812345678" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" placeholder="email@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Chủ Đề *</label>
                            <input type="text" id="subject" name="subject" placeholder="Chủ đề liên hệ..." required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">Nội Dung *</label>
                        <textarea id="message" name="message" placeholder="Nhập nội dung tin nhắn..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Gửi Tin Nhắn
                    </button>
                </form>
            </div>

            <!-- Map -->
            <div class="map-wrapper" id="map"></div>
        </div>
    </div>
</div>

<style>
    .contact-hero {
        background: linear-gradient(135deg, #1e40af 0%, #667eea 100%);
        color: white;
        padding: 80px 20px;
        text-align: center;
        margin-bottom: 60px;
    }

    .contact-hero h1 {
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 15px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .contact-hero p {
        font-size: 16px;
        opacity: 0.95;
        margin: 0;
    }

    .container-contact {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .contact-info-section {
        margin-bottom: 60px;
    }

    .contact-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .contact-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-top: 4px solid #ff6b35;
    }

    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .contact-card-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 32px;
        color: white;
    }

    .contact-card h3 {
        color: #1e40af;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .contact-card p {
        color: #666;
        font-size: 14px;
        line-height: 1.6;
        margin: 10px 0;
    }

    .contact-card a {
        color: #ff6b35;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }

    .contact-card a:hover {
        color: #ff8c42;
    }

    .contact-form-section {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 60px;
    }

    .form-wrapper {
        padding: 50px;
    }

    .section-title {
        font-size: 28px;
        color: #1e40af;
        font-weight: 700;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 28px;
        background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
        border-radius: 2px;
    }

    .form-subtitle {
        color: #999;
        font-size: 14px;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        transition: all 0.3s;
        background: #f9f9f9;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #ff6b35;
        background: white;
        box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 150px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
        color: white;
        border: none;
        padding: 14px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
        border-left: 4px solid #10b981;
        color: #059669;
    }

    .alert-error {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
        border-left: 4px solid #ef4444;
        color: #dc2626;
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

    .map-wrapper {
        height: 400px;
        background: #e5e7eb;
        position: relative;
    }

    #map {
        width: 100%;
        height: 100%;
    }

    @media (max-width: 768px) {
        .contact-hero {
            padding: 50px 20px;
        }

        .contact-hero h1 {
            font-size: 32px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-wrapper {
            padding: 30px;
        }

        .map-wrapper {
            height: 300px;
        }

        .contact-info-grid {
            grid-template-columns: 1fr;
        }

        .contact-form-section > div {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    // Initialize map
    function initMap() {
        const location = { lat: 10.7769, lng: 106.7009 }; // Ho Chi Minh City
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: location,
            mapTypeControl: false,
            fullscreenControl: false
        });

        const marker = new google.maps.Marker({
            position: location,
            map: map,
            title: 'Du Lịch Thông Minh'
        });

        const infoWindow = new google.maps.InfoWindow({
            content: '<div style="color: #333; padding: 10px;"><strong>Du Lịch Thông Minh</strong><br>123 Đường Nguyễn Huệ, Q.1, TP.HCM</div>'
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
    }

    // Load map when page is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        initMap();
    }
</script>
