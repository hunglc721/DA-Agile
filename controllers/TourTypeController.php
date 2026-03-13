<?php
class TourTypeController
{
    private $tourTypeModel;

    public function __construct()
    {
        $this->tourTypeModel = new TourType();
    }

    /**
     * Hiển thị danh sách loại tour
     */
    public function index()
    {
        try {
            $tourTypes = $this->tourTypeModel->findAllIncludeInactive();
            
            $data = [
                'title' => 'Quản Lý Loại Tour',
                'page' => 'tour_types',
                'content_view' => 'tour_types/index',
                'tourTypes' => $tourTypes
            ];

            view('main', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=dashboard');
        }
    }

    /**
     * Hiển thị form tạo loại tour
     */
    public function create()
    {
        try {
            view('main', [
                'title' => 'Thêm Loại Tour',
                'page' => 'tour_types',
                'content_view' => 'tour_types/create'
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=tour_types');
        }
    }

    /**
     * Lưu loại tour mới
     */
    public function store()
    {
        try {
            $data = $_POST;

            // Validate
            $errors = $this->tourTypeModel->validate($data);
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                redirect('index.php?action=tour_types_create');
                return;
            }

            // Tạo loại tour
            $typeId = $this->tourTypeModel->create($data);

            $_SESSION['success'] = 'Thêm loại tour thành công!';
            redirect('index.php?action=tour_types');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tour_types_create');
        }
    }

    /**
     * Hiển thị form chỉnh sửa loại tour
     */
    public function edit()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Loại tour không tìm thấy');
            }

            $tourType = $this->tourTypeModel->findAllIncludeInactive();
            $current = null;
            foreach ($tourType as $t) {
                if ($t['id'] == $id) {
                    $current = $t;
                    break;
                }
            }

            if (!$current) {
                throw new Exception('Loại tour không tồn tại');
            }

            view('main', [
                'title' => 'Chỉnh Sửa Loại Tour: ' . $current['name'],
                'page' => 'tour_types',
                'content_view' => 'tour_types/edit',
                'tourType' => $current
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tour_types');
        }
    }

    /**
     * Cập nhật loại tour
     */
    public function update()
    {
        try {
            $id = $_POST['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Loại tour không tìm thấy');
            }

            $data = $_POST;
            unset($data['id']);

            // Validate
            $errors = $this->tourTypeModel->validate($data, true, $id);
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                redirect('index.php?action=tour_types_edit&id=' . $id);
                return;
            }

            // Cập nhật
            $this->tourTypeModel->update($id, $data);

            $_SESSION['success'] = 'Cập nhật loại tour thành công!';
            redirect('index.php?action=tour_types');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tour_types');
        }
    }

    /**
     * Bật/Tắt loại tour
     */
    public function toggleActive()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Loại tour không tìm thấy');
            }

            $this->tourTypeModel->toggleActive($id);

            $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
            redirect('index.php?action=tour_types');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tour_types');
        }
    }

    /**
     * Xóa loại tour
     */
    public function delete()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Loại tour không tìm thấy');
            }

            $this->tourTypeModel->delete($id);

            $_SESSION['success'] = 'Xóa loại tour thành công!';
            redirect('index.php?action=tour_types');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tour_types');
        }
    }
}
?>
