<?php
// controllers/HomeController.php

class HomeController extends BaseController {

    public function index() {
        // Nếu đã đăng nhập
        if (isset($_SESSION['user'])) {
            // Nếu là Admin → Dashboard Admin
            if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']) {
                (new AdminController())->dashboard();
            }
            // Nếu là Guide/HDV → Dashboard Guide
            elseif (isset($_SESSION['guide_id'])) {
                (new GuideProfileController())->dashboard();
            }
            // Nếu là Client/User thường → Trang client home
            else {
                view('client/index', [
                    'title' => 'Trang Chủ - Travel Manager',
                    'page' => 'client/index'
                ]);
            }
        }
        // Nếu chưa đăng nhập → Hiển thị trang client home
        else {
            view('client/index', [
                'title' => 'Trang Chủ - Travel Manager',
                'page' => 'client/index'
            ]);
        }
    }

    public function dashboard() {
        // Chuyển hướng đến action 'dashboard' của AdminController một cách tường minh
        (new AdminController())->dashboard();
    }

    // Ví dụ các hàm khác
    public function tours() {
        $this->render('admin/tour_customers', ['title' => 'Quản lý Tour']);
    }
}
?>
