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

    // Hiển thị form đăng ký
    public function register()
    {
        // Nếu đã đăng nhập, chuyển hướng về dashboard
        if (isset($_SESSION['user'])) {
            redirect('/');
            return;
        }

        view('register', [
            'title' => 'Đăng Ký',
            'page' => 'register'
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

    // Xử lý đăng ký
    public function handleRegister()
    {
        try {
            // Kiểm tra phương thức POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('register');
                return;
            }

            // Lấy dữ liệu từ form
            $full_name = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $password_confirm = trim($_POST['password_confirm'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            // Xác thực dữ liệu
            if (empty($full_name) || empty($email) || empty($password) || empty($password_confirm)) {
                $_SESSION['error'] = 'Vui lòng điền tất cả các trường bắt buộc';
                redirect('register');
                return;
            }

            // Kiểm tra email hợp lệ
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Email không hợp lệ';
                redirect('register');
                return;
            }

            // Kiểm tra mật khẩu khớp
            if ($password !== $password_confirm) {
                $_SESSION['error'] = 'Mật khẩu không khớp';
                redirect('register');
                return;
            }

            // Kiểm tra độ dài mật khẩu
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                redirect('register');
                return;
            }

            // Kiểm tra email đã tồn tại
            $sql_check = "SELECT id FROM users WHERE email = ?";
            $existing_user = $this->userModel->fetchOne($sql_check, [$email]);
            if ($existing_user) {
                $_SESSION['error'] = 'Email này đã được đăng ký';
                redirect('register');
                return;
            }

            // Tạo user mới
            $username = explode('@', $email)[0];

            // Kiểm tra username đã tồn tại
            $sql_check_user = "SELECT id FROM users WHERE username = ?";
            $existing_username = $this->userModel->fetchOne($sql_check_user, [$username]);
            if ($existing_username) {
                // Nếu username đã tồn tại, thêm timestamp để tạo unique
                $username = $username . '_' . time();
            }

            $sql_insert = "INSERT INTO users (username, full_name, email, password, phone, is_admin, created_at)
                          VALUES (?, ?, ?, ?, ?, 0, NOW())";

            try {
                $stmt = $this->userModel->query($sql_insert, [
                    $username,
                    $full_name,
                    $email,
                    $password,
                    $phone
                ]);

                if ($stmt && $stmt->rowCount() > 0) {
                    $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                    redirect('login');
                } else {
                    $_SESSION['error'] = 'Đăng ký thất bại. Vui lòng thử lại.';
                    redirect('register');
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = 'Lỗi đăng ký: ' . $e->getMessage();
                redirect('register');
            }

        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi đăng ký: ' . $e->getMessage();
            redirect('register');
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

        // Redirect về trang chủ
        header('Location: ' . BASE_URL . 'views/client/index.html');
        exit();
    }
}
?>

