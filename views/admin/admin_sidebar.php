<?php
// views/admin/admin_sidebar.php
$currentAction = $_GET['action'] ?? 'dashboard';
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?action=dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-plane-departure"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Quản Lý Tour</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item <?= ($currentAction == 'dashboard') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Bảng Điều Khiển</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">QUẢN LÝ</div>

    <li class="nav-item <?= ($currentAction == 'tour_schedule') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=tour_schedule">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Lịch Trình Tour</span></a>
    </li>

    <li class="nav-item <?= ($currentAction == 'departures' || $currentAction == 'departures_create' || $currentAction == 'departures_edit' || $currentAction == 'departures_assign' || $currentAction == 'assignments') ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDepartures"
            aria-expanded="<?= in_array($currentAction, ['departures','departures_create','departures_assign','assignments']) ? 'true' : 'false' ?>" aria-controls="collapseDepartures">
            <i class="fas fa-fw fa-plane-departure"></i>
            <span>Khởi Hành & Phân Bổ</span>
        </a>
        <div id="collapseDepartures" class="collapse <?= in_array($currentAction, ['departures','departures_create','departures_assign','assignments']) ? 'show' : '' ?>" aria-labelledby="headingDepartures" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item <?= ($currentAction == 'departures') ? 'active' : '' ?>" href="index.php?action=departures">
                    <i class="fas fa-fw fa-list"></i> Danh Sách Khởi Hành
                </a>
                <a class="collapse-item" href="index.php?action=departures_create">
                    <i class="fas fa-fw fa-plus-circle"></i> Tạo Khởi Hành
                </a>
                <div class="collapse-divider"></div>
                <a class="collapse-item <?= ($currentAction == 'assignments') ? 'active' : '' ?>" href="index.php?action=assignments">
                    <i class="fas fa-fw fa-user-friends"></i> Danh Sách Phân Bổ
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item <?= ($currentAction == 'tour_diary') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=tour_diary">
            <i class="fas fa-fw fa-book"></i>
            <span>Nhật Ký Tour</span></a>
    </li>

    <li class="nav-item <?= ($currentAction == 'incident_reports') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=incident_reports">
            <i class="fas fa-fw fa-exclamation-triangle text-danger"></i>
            <span>Báo Cáo Sự Cố</span></a>
    </li>

    <li class="nav-item <?= ($currentAction == 'tour_customers') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=tour_customers">
            <i class="fas fa-users me-2"></i>
            <span>DS Khách Đoàn</span></a>
    </li>

    <li class="nav-item <?= ($currentAction == 'checkin_attendance') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=checkin_attendance">
            <i class="fas fa-user-check me-2"></i>
            <span>Check-in & Điểm Danh</span></a>
    </li>

    <!-- 🎯 DANH MỤC TOUR (NEW) -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTourCategory"
            aria-expanded="<?= in_array($currentAction, ['tours', 'tour_categories', 'tour_types']) ? 'true' : 'false' ?>" aria-controls="collapseTourCategory">
            <i class="fas fa-fw fa-tags"></i> 
            <span>Danh Mục Tour</span>
        </a>
        <div id="collapseTourCategory" class="collapse <?= in_array($currentAction, ['tours', 'tour_categories', 'tour_types']) ? 'show' : '' ?>" aria-labelledby="headingTourCategory" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tour:</h6>
                <a class="collapse-item <?= ($currentAction == 'tours') ? 'active' : '' ?>" href="index.php?action=tours">
                    <i class="fas fa-fw fa-list"></i> Danh Sách Tour
                </a>
                <a class="collapse-item <?= ($currentAction == 'tours_create') ? 'active' : '' ?>" href="index.php?action=tours_create">
                    <i class="fas fa-fw fa-plus-circle"></i> Thêm Tour Mới
                </a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Phân Loại:</h6>
                <a class="collapse-item <?= ($currentAction == 'tour_categories') ? 'active' : '' ?>" href="index.php?action=tour_categories">
                    <i class="fas fa-fw fa-layer-group"></i> Danh Mục Tour
                </a>
                <a class="collapse-item <?= ($currentAction == 'tour_types') ? 'active' : '' ?>" href="index.php?action=tour_types">
                    <i class="fas fa-fw fa-cube"></i> Loại Tour
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item <?= ($_GET['action'] ?? '') == 'bookings' || ($_GET['action'] ?? '') == 'bookings_create' ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=bookings">
            <i class="fas fa-fw fa-ticket-alt"></i> <span>Bán Tour & Đặt Chỗ</span>
        </a>
    </li>

    <li class="nav-item <?= ($_GET['action'] ?? '') == 'admin_payments' || ($_GET['action'] ?? '') == 'admin_payments_show' ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=admin_payments">
            <i class="fas fa-fw fa-credit-card"></i> <span>Quản Lý Thanh Toán</span>
        </a>
    </li>

<!-- 🎯 BÁO CÁO VẬN HÀNH -->
<li class="nav-item <?= ($currentAction == 'tour_financial_report') ? 'active' : '' ?>">
    <a class="nav-link" href="index.php?action=tour_financial_report">
        <i class="fas fa-fw fa-chart-line"></i>
        <span>Báo Cáo Vận Hành</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGuide"
        aria-expanded="true" aria-controls="collapseGuide">
        <i class="fas fa-fw fa-id-card"></i> <span>Hướng Dẫn Viên</span>
    </a>
    <div id="collapseGuide" class="collapse" aria-labelledby="headingGuide" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Chức năng:</h6>
            <a class="collapse-item" href="index.php?action=guides">Danh sách HDV</a>
            <a class="collapse-item" href="index.php?action=guides_create">Thêm HDV mới</a>
        </div>
    </div>
</li>

    <li class="nav-item <?= ($currentAction == 'services' || $currentAction == 'services_edit') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=services">
            <i class="fas fa-fw fa-concierge-bell"></i>
            <span>Quản Lý Dịch Vụ</span></a>
    </li>

    <li class="nav-item <?= ($currentAction == 'communication') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php?action=communication">
            <i class="fas fa-fw fa-comments"></i>
            <span>Trao Đổi Với HDV</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">CẤU HÌNH HỆ THỐNG</div>

    <li class="nav-item <?= ($currentAction == 'users' || $currentAction == 'users_create' || $currentAction == 'users_edit') ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAccount"
            aria-expanded="<?= in_array($currentAction, ['users', 'users_create', 'users_edit']) ? 'true' : 'false' ?>" aria-controls="collapseAccount">
            <i class="fas fa-fw fa-user-lock"></i>
            <span>Quản Lý Tài Khoản</span>
        </a>
        <div id="collapseAccount" class="collapse <?= in_array($currentAction, ['users', 'users_create', 'users_edit']) ? 'show' : '' ?>" aria-labelledby="headingAccount" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item <?= ($currentAction == 'users') ? 'active' : '' ?>" href="index.php?action=users">
                    <i class="fas fa-fw fa-list"></i> Danh Sách Tài Khoản
                </a>
                <a class="collapse-item <?= ($currentAction == 'users_create') ? 'active' : '' ?>" href="index.php?action=users_create">
                    <i class="fas fa-fw fa-plus-circle"></i> Tạo Tài Khoản Mới
                </a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>