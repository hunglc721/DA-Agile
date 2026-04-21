<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="?action=guide_dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-compass"></i>
        </div>
        <div class="sidebar-brand-text mx-3">HDV Panel</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <?php $currentAction = $_GET['action'] ?? ''; ?>
    <!-- Nav Item - Hồ Sơ Cá Nhân -->
    <!-- <li class="nav-item <?= $currentAction === 'guide_profile' ? 'active' : '' ?>">
        <a class="nav-link" href="?action=guide_profile">
            <i class="fas fa-fw fa-user"></i>
            <span>Hồ Sơ Cá Nhân</span></a>
    </li> -->

    <!-- Nav Item - Tour Được Phân Công -->
    <li class="nav-item <?= $currentAction === 'guide_assigned_tours' ? 'active' : '' ?>">
        <a class="nav-link" href="?action=guide_assigned_tours">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Tour Được Phân Công</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Chức năng
    </div>

    <!-- Nav Item - Phản Hồi Khách Hàng -->
    <li class="nav-item">
        <a class="nav-link" href="?action=guide_customer_feedback">
            <i class="fas fa-fw fa-comments"></i>
            <span>Phản Hồi Khách Hàng</span></a>
    </li>

    <!-- Nav Item - Thông Báo -->
    <li class="nav-item">
        <a class="nav-link" href="?action=guide_notifications">
            <i class="fas fa-fw fa-bell"></i>
            <span>Thông Báo</span></a>
    </li>

    <!-- Nav Item - Trao đổi với Admin -->
    <li class="nav-item">
        <a class="nav-link" href="?action=guide_communication">
            <i class="fas fa-fw fa-headset"></i>
            <span>Trao Đổi Với Admin</span></a>
    </li>

</ul>
<!-- End of Sidebar -->