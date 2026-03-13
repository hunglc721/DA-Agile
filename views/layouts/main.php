<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?? 'Hệ Thống Quản Lý Tour' ?></title>
    <link href="<?= asset('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= asset('css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- Move scripts to head to ensure libraries are loaded before content views -->
    <script src="<?= asset('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= asset('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('js/sb-admin-2.min.js') ?>"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php 
            // Kiểm tra xem người dùng đã đăng nhập và có vai trò gì để hiển thị sidebar phù hợp
            if (isset($_SESSION['user'])) {
                if (!empty($_SESSION['user']['is_admin'])) {
                    // Nếu là Admin, hiển thị sidebar của Admin
                    include_once PATH_VIEW . 'admin/admin_sidebar.php';
                } elseif (isset($_SESSION['guide_id'])) {
                    // Nếu là HDV, hiển thị sidebar của HDV
                    include_once PATH_VIEW . 'guides/profile/guide_sidebar.php';
                }
            }
        ?>
        
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['full_name']) : 'Khách' ?>
                                </span>
                                <img class="img-profile rounded-circle" src="<?= asset('img/undraw_profile.svg') ?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="userDropdown">
                                <?php if (isset($_SESSION['user'])): ?>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Hồ sơ cá nhân
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Cài đặt
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