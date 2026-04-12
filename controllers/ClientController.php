<?php
class ClientController
{
    /**
     * Hiển thị trang chủ client
     */
    public function index()
    {
        view('client/index', [
            'title' => 'Trang Chủ - Travel Manager',
            'page' => 'client/index'
        ]);
    }

    /**
     * Hiển thị trang About
     */
    public function about()
    {
        view('client/about', [
            'title' => 'Về Chúng Tôi - Travel Manager',
            'page' => 'client/about'
        ]);
    }

    /**
     * Hiển thị trang Tour
     */
    public function tour()
    {
        view('client/tour', [
            'title' => 'Tour - Travel Manager',
            'page' => 'client/tour'
        ]);
    }

    /**
     * Hiển thị trang Hotel
     */
    public function hotel()
    {
        view('client/hotel', [
            'title' => 'Khách Sạn - Travel Manager',
            'page' => 'client/hotel'
        ]);
    }

    /**
     * Hiển thị trang Contact
     */
    public function contact()
    {
        view('client/contact', [
            'title' => 'Liên Hệ - Travel Manager',
            'page' => 'client/contact'
        ]);
    }

    /**
     * Hiển thị trang Hotel Single
     */
    public function hotelSingle()
    {
        view('client/hotel-single', [
            'title' => 'Chi Tiết Khách Sạn - Travel Manager',
            'page' => 'client/hotel-single'
        ]);
    }

    /**
     * Hiển thị dashboard khách hàng
     */
    public function dashboard()
    {
        requireClient();

        $user = getCurrentUser();
        $bookingModel = new Booking();
        $tourModel = new Tour();

        // Lấy booking của khách hàng
        $sql = "SELECT b.*, t.name as tour_name, t.price
                FROM bookings b
                JOIN tours t ON b.tour_id = t.id
                WHERE b.user_id = ?
                ORDER BY b.created_at DESC
                LIMIT 10";
        $bookings = $bookingModel->fetchAll($sql, [$user['id']]);

        // Tính toán thống kê
        $sqlStats = "SELECT
                    COUNT(*) as total_bookings,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_count,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(total_price) as total_spent
                    FROM bookings
                    WHERE user_id = ?";
        $stats = $bookingModel->fetchOne($sqlStats, [$user['id']]) ?? [];

        view('client/dashboard', [
            'title' => 'Trang Cá Nhân - Du Lịch Thông Minh',
            'page' => 'client/dashboard',
            'user' => $user,
            'bookings' => $bookings,
            'stats' => $stats
        ]);
    }

    /**
     * Hiển thị trang hồ sơ cá nhân
     */
    public function profile()
    {
        requireClient();

        $user = getCurrentUser();
        $userModel = new User();
        $userInfo = $userModel->find($user['id']);

        view('client/profile', [
            'title' => 'Hồ Sơ Cá Nhân - Du Lịch Thông Minh',
            'page' => 'client/profile',
            'user' => $userInfo
        ]);
    }

    /**
     * Cập nhật hồ sơ cá nhân
     */
    public function updateProfile()
    {
        requireClient();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client_profile');
        }

        $user = getCurrentUser();
        $userModel = new User();

        $data = [
            'full_name' => clean($_POST['full_name'] ?? ''),
            'phone' => clean($_POST['phone'] ?? ''),
            'email' => clean($_POST['email'] ?? '')
        ];

        // Kiểm tra validation
        $errors = [];
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Vui lòng nhập tên đầy đủ';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }
        if (!empty($data['phone']) && !preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại phải có 10-11 chữ số';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('client_profile');
        }

        // Cập nhật password nếu có
        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 6) {
                $_SESSION['errors'] = ['password' => 'Mật khẩu phải có ít nhất 6 ký tự'];
                redirect('client_profile');
            }
            $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }

        try {
            $userModel->update($user['id'], $data);

            // Cập nhật session
            $_SESSION['user']['full_name'] = $data['full_name'];
            $_SESSION['user']['email'] = $data['email'];
            $_SESSION['user']['phone'] = $data['phone'] ?? '';

            $_SESSION['success'] = 'Cập nhật hồ sơ thành công!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        redirect('client_profile');
    }
}
?>
