<?php
// controllers/HomeController.php

class HomeController extends BaseController { // 1. Kế thừa BaseController
    
    public function index() {
        // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập.
        if (!isset($_SESSION['user'])) {
            $this->redirect('login');
            return;
        }

        // Sau khi đăng nhập, AuthController đã điều hướng đúng vai trò.
        // HomeController chỉ cần xử lý cho người dùng đã đăng nhập truy cập vào trang chủ.
        // Nếu là admin, chuyển đến dashboard của admin. Các vai trò khác sẽ được xử lý bởi controller của họ.
        if (isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']) {
            // Gọi hàm dashboard của AdminController để hiển thị đúng trang.
            (new AdminController())->dashboard();
        } else {
            // Nếu không phải admin (ví dụ: HDV cố tình truy cập trang chủ),
            // chuyển hướng thẳng đến dashboard của HDV để phá vỡ vòng lặp.
            $this->redirect('guide_dashboard');
        }
    }
    
    public function dashboard() {
        // Chuyển hướng đến action 'dashboard' của AdminController một cách tường minh.
        (new AdminController())->dashboard();
    }
    
    // Ví dụ các hàm khác
    public function tours() {
        $this->render('admin/tour_customers', ['title' => 'Quản lý Tour']);
    }
}
?>