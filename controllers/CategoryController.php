<?php
class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new TourCategory(); // Đảm bảo bạn đã có model này
    }

    // 1. READ: Danh sách danh mục
    public function index()
    {
        try {
            $categories = $this->categoryModel->findAll();
            
            // DEBUG LOG
            error_log("DEBUG: CategoryController index() - Found " . count($categories) . " categories");
            
            view('main', [
                'title' => 'Quản Lý Danh Mục Tour',
                'page' => 'categories',
                'content_view' => 'categories/index',
                'categories' => $categories
            ]);
        } catch (Exception $e) {
            error_log("ERROR in CategoryController: " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=dashboard');
        }
    }

    // 2. CREATE: Form tạo mới
    public function create()
    {
        view('main', [
            'title' => 'Thêm Danh Mục Mới',
            'page' => 'categories',
            'content_view' => 'categories/create'
        ]);
    }

    // 3. STORE: Lưu vào DB
    public function store()
    {
        try {
            $name = $_POST['name'] ?? '';
            if (empty($name)) {
                throw new Exception('Tên danh mục không được để trống');
            }

            // Gọi model để insert (Giả sử model có hàm create hoặc query insert)
            $sql = "INSERT INTO tour_categories (name, description) VALUES (?, ?)";
            $this->categoryModel->query($sql, [$name, $_POST['description'] ?? '']);

            $_SESSION['success'] = 'Thêm danh mục tour thành công!';
            redirect('index.php?action=tour_categories');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            redirect('index.php?action=tour_categories_create');
        }
    }

    // 4. EDIT: Form chỉnh sửa
    public function edit()
    {
        try {
            $id = $_GET['id'] ?? null;
            $category = $this->categoryModel->find($id); // Giả sử BaseModel có find()
            
            if (!$category) throw new Exception('Danh mục không tồn tại');

            view('main', [
                'title' => 'Sửa Danh Mục',
                'page' => 'categories',
                'content_view' => 'categories/edit',
                'category' => $category
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=tour_categories');
        }
    }

    // 5. UPDATE: C\u1eadp nh\u1eadt DB
    public function update()
    {
        try {
            $id = $_POST['id'];
            $name = $_POST['name'];
            
            $sql = "UPDATE tour_categories SET name = ?, description = ? WHERE id = ?";
            $this->categoryModel->query($sql, [$name, $_POST['description'] ?? '', $id]);

            $_SESSION['success'] = 'Cập nhật danh mục thành công!';
            redirect('index.php?action=tour_categories');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tour_categories_edit&id=' . ($_POST['id'] ?? ''));
        }
    }

    // 6. DELETE: Xóa danh mục
    public function delete()
    {
        try {
            $id = $_GET['id'] ?? $_POST['id'] ?? null;
            
            if (!$id) {
                throw new Exception('Danh mục không tìm thấy');
            }
            
            // Kiểm tra xem danh mục có đang chứa tour nào không
            $sqlCheck = "SELECT COUNT(*) as count FROM tours WHERE category_id = ?";
            $check = $this->categoryModel->fetchOne($sqlCheck, [$id]);

            if ($check['count'] > 0) {
                $_SESSION['error'] = 'Không thể xóa danh mục đang có tour!';
                redirect('index.php?action=tour_categories');
                return;
            }

            $this->categoryModel->query("DELETE FROM tour_categories WHERE id = ?", [$id]);
            $_SESSION['success'] = 'Xóa danh mục thành công!';
            redirect('index.php?action=tour_categories');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tour_categories');
        }
    }
}
?>