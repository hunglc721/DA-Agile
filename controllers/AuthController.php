<?php
class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Hiển thị form đăng nhập
    public function login()
    {
        // Nếu đã đăng nhập, chuyển hướng về dashboard phù hợp
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $sql_guide = "SELECT id FROM guides WHERE user_id = ?";
            $guideModel = new Guide();
            $guide = $guideModel->fetchOne($sql_guide, [$userId]);

            if ($_SESSION['user']['is_admin']) {
                redirect('dashboard');
            } elseif ($guide) {
                redirect('guide_dashboard');
            } else {
                redirect('/');
            }
            return;
        }

        view('login', [
            'title' => 'Đăng Nhập',
            'page' => 'login'
        ]);
    }

    // Xử lý đăng nhập
    public function handleLogin()
    {
        try {
            // Kiểm tra phương thức POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('login');
                return;
            }

            // Lấy dữ liệu từ form
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Xác thực dữ liệu
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Vui lòng nhập email và mật khẩu';
                redirect('login');
                return;
            }

            // Tìm user theo email
            $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
            $user = $this->userModel->fetchOne($sql, [$email]);

            if (!$user) {
                $_SESSION['error'] = 'Email hoặc mật khẩu không chính xác';
                redirect('login');
                return;
            }

            // Kiểm tra mật khẩu
            if ($password !== $user['password']) {
                $_SESSION['error'] = 'Email hoặc mật khẩu không chính xác';
                redirect('login');
                return;
            }

            // Lưu session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'is_admin' => $user['is_admin'] ?? 0
            ];

            $_SESSION['success'] = 'Đăng nhập thành công!';
            
            // Kiểm tra xem user có phải HDV không
            $sql_guide = "SELECT id FROM guides WHERE user_id = ?";
            $guideModel = new Guide();
            $guide = $guideModel->fetchOne($sql_guide, [$user['id']]);

            // Chuyển hướng dựa trên vai trò
            if ($user['is_admin']) {
                redirect('dashboard');
            } elseif ($guide) {
                redirect('guide_dashboard');
            } else {
                redirect('/');
            }

        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi đăng nhập: ' . $e->getMessage();
            redirect('login');
        }
    }

    // Đăng xuất
    public function logout()
    {
        session_destroy();
        $_SESSION = [];
        
        // Xóa cookie session nếu có
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        redirect('login');
    }
}
?>
