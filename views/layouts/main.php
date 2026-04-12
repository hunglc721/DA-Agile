<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?? 'Hệ Thống Quản Lý Tour' ?></title>
    <link href="<?= asset('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= asset('css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- Frontend CSS Files (Client - Priority First) -->
    <link href="<?= asset('css/frontend/style.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/animate.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/aos.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/bootstrap-datepicker.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/flaticon.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/icomoon.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/ionicons.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/jquery.timepicker.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/magnific-popup.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/open-iconic-bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/owl.carousel.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/owl.theme.default.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/fonts-fix.css') ?>" rel="stylesheet">

    <!-- Backend CSS Files (Admin & Guide) -->
    <link href="<?= asset('css/backend/admin_communication.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/admin_dashboard.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/admin_incident_reports.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/admin_tour_financial_report.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/guides_assigned_tours.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/guides_checkin.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/guides_communication.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/guides_dashboard.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/guides_final_report.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/guides_incident_report.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/guides_itinerary.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/guides_tour_customers.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/backend/guides_tour_detail.css') ?>" rel="stylesheet">

    <!-- Move scripts to head to ensure libraries are loaded before content views -->
    <script src="<?= asset('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= asset('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('js/sb-admin-2.min.js') ?>"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php
            // Hiển thị sidebar phù hợp dựa trên loại user
            if (isset($_SESSION['user'])) {
                if (!empty($_SESSION['user']['is_admin'])) {
                    // Admin sidebar
                    include_once PATH_VIEW . 'admin/admin_sidebar.php';
                } elseif (isset($_SESSION['guide_id'])) {
                    // HDV/Guide sidebar
                    include_once PATH_VIEW . 'guides/profile/guide_sidebar.php';
                } else {
                    // Client - không có sidebar, chỉ có navbar chính
                }
            }
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <!-- Thông tin user -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php
                                    if (isset($_SESSION['user'])) {
                                        $userType = '';
                                        if (!empty($_SESSION['user']['is_admin'])) {
                                            $userType = '[Admin]';
                                        } elseif (isset($_SESSION['guide_id'])) {
                                            $userType = '[HDV]';
                                        } else {
                                            $userType = '[User]';
                                        }
                                        echo htmlspecialchars($_SESSION['user']['full_name']) . ' ' . $userType;
                                    } else {
                                        echo 'Khách';
                                    }
                                    ?>
                                </span>
                                <img class="img-profile rounded-circle" src="<?= asset('img/undraw_profile.svg') ?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="userDropdown">
                                <?php if (isset($_SESSION['user'])): ?>
                                    <a class="dropdown-item" href="<?= url('client_profile') ?>">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Hồ sơ cá nhân
                                    </a>
                                    <a class="dropdown-item" href="<?= url('client_dashboard') ?>">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Bảng điều khiển
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?= url('logout') ?>">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Đăng xuất
                                    </a>
                                <?php else: ?>
                                    <a class="dropdown-item" href="<?= url('login') ?>">
                                        <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Đăng nhập
                                    </a>
                                <?php endif; ?>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <?php
                    if (isset($content_view)) {
                        $file_path = PATH_VIEW . $content_view . '.php';
                        if (file_exists($file_path)) {
                            include $file_path;
                        } else {
                            echo "<div class='alert alert-danger'>Không tìm thấy file view: $content_view</div>";
                        }
                    }
                    ?>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; 2025</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

</body>
</html>