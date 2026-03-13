<?php
class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Hiển thị danh sách người dùng
     */
    public function index()
    {
        try {
            if (!Auth::isAdmin()) {
                throw new Exception('Bạn không có quyền truy cập');
            }

            $filters = [
                'role' => $_GET['role'] ?? null,
                'search' => $_GET['search'] ?? null,
            ];

            $users = $this->userModel->findAll($filters);

            $data = [
                'title' => 'Quản Lý Người Dùng',
                'page' => 'users',
                'content_view' => 'users/index',
                'users' => $users,
                'filters' => $filters
            ];

            view('main', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('/');
        }
    }

    /**
     * Hiển thị form tạo người dùng
     */
    public function create()
    {
        try {
            if (!Auth::isAdmin()) {
                throw new Exception('Bạn không có quyền truy cập');
            }

            $data = [
                'title' => 'Tạo Người Dùng Mới',
                'page' => 'users',
                'content_view' => 'users/create'
            ];

            view('main', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('/');
        }
    }

    /**
     * Lưu người dùng mới
     */
    public function store()
    {
        try {
            if (!Auth::isAdmin()) {
                throw new Exception('Bạn không có quyền truy cập');
            }

            // Validate dữ liệu
            $errors = $this->validateUser($_POST);
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                redirect('users_create');
                exit;
            }

            // Tạo người dùng
            $userId = $this->userModel->create($_POST);

            $_SESSION['success'] = 'Tạo người dùng thành công!';
            redirect('users');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('users_create');
        }
    }

    /**
     * Hiển thị chi tiết người dùng
     */
    public function show()
    {
        try {
            if (!Auth::isAdmin()) {
                throw new Exception('Bạn không có quyền truy cập');
            }

            $id = $_GET['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Người dùng không tìm thấy');
            }

            $user = $this->userModel->find($id);
            if (!$user) {
                throw new Exception('Người dùng không tồn tại');
            }

            $data = [
                'title' => 'Chi Tiết Người Dùng - ' . ($user['full_name'] ?? $user['email']),
                'page' => 'users',
                'content_view' => 'users/show',
                'user' => $user
            ];

            view('main', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('users');
        }
    }

    /**
     * Hiển thị form edit người dùng
     */
    public function edit()
    {
        try {
            if (!Auth::isAdmin()) {
                throw new Exception('Bạn không có quyền truy cập');
            }

            $id = $_GET['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Người dùng không tìm thấy');
            }

            $user = $this->userModel->find($id);
            if (!$user) {
                throw new Exception('Người dùng không tồn tại');
            }

            $data = [
                'title' => 'Chỉnh Sửa Người Dùng - ' . ($user['full_name'] ?? $user['email']),
                'page' => 'users',
                'content_view' => 'users/edit',
                'user' => $user
            ];

            view('main', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('users');
        }
    }

    /**
     * Cập nhật người dùng
     */
    public function update()
    {
        try {
            if (!Auth::isAdmin()) {
                throw new Exception('Bạn không có quyền truy cập');
            }

            $id = $_POST['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Người dùng không tìm thấy');
            }

            $user = $this->userModel->find($id);
            if (!$user) {
                throw new Exception('Người dùng không tồn tại');
            }

            // Chuẩn bị dữ liệu update
            $updateData = [
                'full_name' => $_POST['full_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? ''
            ];

            // Nếu có password mới, thêm vào
            if (!empty($_POST['password'])) {
                if (strlen($_POST['password']) < 6) {
                    throw new Exception('Mật khẩu phải có ít nhất 6 ký tự');
                }
                if ($_POST['password'] !== $_POST['password_confirm']) {
                    throw new Exception('Mật khẩu xác nhận không khớp');
                }
                $updateData['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }

            if (!empty($_POST['status'])) {
                $updateData['status'] = $_POST['status'];
            }

            // Cập nhật người dùng
            $this->userModel->update($id, $updateData);

            $_SESSION['success'] = 'Cập nhật người dùng thành công!';
            redirect('users');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('users_edit&id=' . ($_POST['id'] ?? ''));
        }
    }

    /**
     * Xóa người dùng
     */
    public function delete()
    {
        try {
            if (!Auth::isAdmin()) {
                throw new Exception('Bạn không có quyền truy cập');
            }

            $id = $_POST['id'] ?? $_GET['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Người dùng không tìm thấy');
            }

            $user = $this->userModel->find($id);
            if (!$user) {
                throw new Exception('Người dùng không tồn tại');
            }

            // Kiểm tra xem có phải admin không
            if ($user['role'] === 'admin' || $user['is_admin']) {
                throw new Exception('Không thể xóa tài khoản admin');
            }

            $this->userModel->delete($id);

            $_SESSION['success'] = 'Xóa người dùng thành công!';
            redirect('users');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('users');
        }
    }

    /**
     * Validate dữ liệu người dùng
     */
    private function validateUser($data)
    {
        $errors = [];

        if (empty($data['full_name'])) {
            $errors[] = 'Tên người dùng là bắt buộc';
        }

        if (empty($data['email'])) {
            $errors[] = 'Email là bắt buộc';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }

        if (empty($data['password'])) {
            $errors[] = 'Mật khẩu là bắt buộc';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        if (empty($data['password_confirm'])) {
            $errors[] = 'Xác nhận mật khẩu là bắt buộc';
        }

        if (($data['password'] ?? '') !== ($data['password_confirm'] ?? '')) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }

        return $errors;
    }
}
?>
