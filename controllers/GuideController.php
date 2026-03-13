<?php
class GuideController
{
    private $guideModel;
    private $userModel;

    public function __construct()
    {
        $this->guideModel = new Guide(); 
        $this->userModel = new User();
    }

    public function index()
    {
        try {
            $sql = "SELECT g.*, u.full_name, u.email, u.phone 
                    FROM guides g 
                    JOIN users u ON g.user_id = u.id";
            $guides = $this->guideModel->fetchAll($sql);

            view('main', [
                'title' => 'Quản Lý Hướng Dẫn Viên',
                'page' => 'guides',
                'content_view' => 'guides/index',
                'guides' => $guides
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/'); // Về trang chủ nếu lỗi
        }
    }

    public function create()
    {
        try {
            $sql = "SELECT * FROM users WHERE (is_admin = 0 OR is_admin IS NULL) AND id NOT IN (SELECT DISTINCT user_id FROM guides WHERE user_id IS NOT NULL) ORDER BY full_name ASC";
            $users = $this->userModel->fetchAll($sql);
            
            if (!is_array($users)) {
                $users = [];
            }

            view('main', [
                'title' => 'Thêm Hướng Dẫn Viên',
                'page' => 'guides',
                'content_view' => 'guides/create',
                'users' => $users
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guides');
        }
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('guides_create');
            return;
        }

        try {
            $userId = $_POST['user_id'] ?? null;
            $bio = $_POST['bio'] ?? '';
            $experience = $_POST['experience_years'] ?? 0;
            $languages = $_POST['languages'] ?? 'Tiếng Việt';

            if (empty($userId)) {
                throw new Exception('Bạn phải chọn một người dùng để gán làm hướng dẫn viên.');
            }

            // Sử dụng hàm create() từ model
            $data = [
                'user_id' => $userId,
                'bio' => $bio,
                'experience_years' => (int)$experience,
                'languages' => $languages,
                'status' => 'active'
            ];

            $guideId = $this->guideModel->create($data);
            
            if (!$guideId) {
                throw new Exception('Lỗi khi thêm hướng dẫn viên. Vui lòng kiểm tra dữ liệu.');
            }

            $_SESSION['success'] = 'Đã thêm hướng dẫn viên mới thành công!';
            redirect('guides'); 
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('guides_create'); 
        }
    }

    public function edit()
    {
        try {
            $id = $_GET['id'];
            $sql = "SELECT g.*, u.full_name FROM guides g JOIN users u ON g.user_id = u.id WHERE g.id = ?";
            $guide = $this->guideModel->fetchOne($sql, [$id]);

            if (!$guide) throw new Exception('Không tìm thấy HDV');

            view('main', [
                'title' => 'Cập Nhật Hướng Dẫn Viên',
                'page' => 'guides',
                'content_view' => 'guides/edit',
                'guide' => $guide
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('guides');
        }
    }

    public function update()
    {
        try {
            $id = $_POST['id'];
            $sql = "UPDATE guides SET bio = ?, experience_years = ?, languages = ? WHERE id = ?";
            $this->guideModel->query($sql, [
                $_POST['bio'], 
                $_POST['experience_years'], 
                $_POST['languages'], 
                $id
            ]);

            $_SESSION['success'] = 'Cập nhật thành công';
            redirect('guides');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('guides');
        }
    }

    public function delete()
    {
        try {
            $id = $_POST['id'];
            $this->guideModel->query("DELETE FROM guides WHERE id = ?", [$id]);
            $_SESSION['success'] = 'Đã xóa hướng dẫn viên';
            redirect('guides');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('guides');
        }
    }
}
?>