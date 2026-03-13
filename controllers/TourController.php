<?php
class TourController
{
    private $tourModel;
    private $categoryModel;
    private $tourTypeModel;

    public function __construct()
    {
        $this->tourModel = new Tour();
        $this->categoryModel = new TourCategory();
        $this->tourTypeModel = new TourType();
    }

    /**
     * Hiển thị danh sách tất cả tour
     */
    public function index()
    {
        try {
            error_log("DEBUG: TourController index() called");
            
            $filters = [
                'category_id' => $_GET['category_id'] ?? null,
                'tour_type' => $_GET['tour_type'] ?? null,
                'status' => $_GET['status'] ?? null,
                'search' => $_GET['search'] ?? null,
            ];

            error_log("DEBUG: Filters = " . print_r($filters, true));

            $tours = $this->tourModel->findAll($filters);
            
            error_log("DEBUG: Found " . count($tours) . " tours");
            
            // Lấy tất cả danh mục để hiển thị bộ lọc
            $categories = $this->categoryModel->findAll();
            
            error_log("DEBUG: Found " . count($categories) . " categories");
            
            // Lấy tất cả loại tour
            $tourTypes = $this->tourTypeModel->findAll();
            
            error_log("DEBUG: Found " . count($tourTypes) . " tourTypes");

            $data = [
                'title' => 'Quản Lý Tour',
                'page' => 'tours',
                'content_view' => 'tours/index',
                'tours' => $tours,
                'categories' => $categories,
                'tourTypes' => $tourTypes,
                'filters' => $filters
            ];

            view('main', $data);
        } catch (Exception $e) {
            error_log("ERROR in TourController index(): " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('/');
        }
    }

    /**
     * Hiển thị form tạo tour mới
     */
    public function create() {
        try {
            // Lấy danh mục tour
            $categories = $this->categoryModel->findAll();
            
            // Lấy loại tour
            $tourTypes = $this->tourTypeModel->findAll();

            view('main', [
                'title' => 'Thêm Tour Mới',
                'page' => 'tours',
                'content_view' => 'tours/create',
                'categories' => $categories,
                'tourTypes' => $tourTypes
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Lưu tour mới vào database
     */
    public function store() {
        try {
            $data = $_POST;

            // 1. Validate cơ bản (Bổ sung các trường bắt buộc mới)
            if (empty($data['tour_code']) || empty($data['name']) || empty($data['price'])) {
                throw new Exception("Vui lòng nhập đủ Mã tour, Tên và Giá bán.");
            }
            
            // Set mặc định số chỗ còn lại bằng tổng số chỗ khi tạo mới
            $data['available_slots'] = $data['max_capacity'];

            // 2. Gọi Model để tạo (Model đã update ở bước trước)
            $tourId = $this->tourModel->create($data);

            // 3. Xử lý upload ảnh (Giữ nguyên logic của bạn)
            if (!empty($_FILES['images']) && $_FILES['images']['error'][0] === UPLOAD_ERR_OK) {
                $this->handleImageUpload($tourId, $_FILES['images']);
            }

            $_SESSION['success'] = 'Thêm tour mới thành công!';
            redirect('index.php?action=tours_show&id=' . $tourId); // Redirect về trang chi tiết
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            // Lưu lại dữ liệu cũ để điền lại form
            $_SESSION['old'] = $_POST; 
            redirect('index.php?action=tours_create');
        }
    }

    /**
     * Hiển thị chi tiết tour
     */
    public function show()
    {
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('Tour không tồn tại');
            }

            // 1. Lấy thông tin tour cơ bản
            $tour = $this->tourModel->find($id);
            
            if (!$tour) {
                throw new Exception('Tour không tồn tại');
            }

            // 2. Lấy lịch trình
            $itinerary = $this->tourModel->fetchAll(
                "SELECT * FROM tour_itineraries WHERE tour_id = ? ORDER BY day_number ASC",
                [$id]
            );

            // 3. Lấy hình ảnh
            $images = $this->tourModel->fetchAll(
                "SELECT * FROM tour_images WHERE tour_id = ? ORDER BY is_main DESC, created_at ASC",
                [$id]
            );

            // 4. Lấy danh sách khách hàng cho booking
            $userModel = new User();
            $customers = $userModel->fetchAll(
                "SELECT * FROM users WHERE is_admin = 0 OR is_admin IS NULL ORDER BY full_name ASC"
            );

            view('main', [
                'title' => htmlspecialchars($tour['name'] ?? 'Chi Tiết Tour'),
                'page' => 'tours',
                'content_view' => 'tours/detail',
                'tour' => $tour,
                'itinerary' => $itinerary,
                'images' => $images,
                'customers' => $customers
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Hiển thị form edit tour
     */
    public function edit()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Tour không tìm thấy');
            }

            $tour = $this->tourModel->find($id);
            if (!$tour) {
                throw new Exception('Tour không tồn tại');
            }

            // Lấy danh mục tour
            $categories = $this->categoryModel->findAll();
            
            // Lấy loại tour
            $tourTypes = $this->tourTypeModel->findAll();

            $data = [
                'title' => 'Chỉnh Sửa Tour - ' . $tour['name'],
                'page' => 'tours',
                'content_view' => 'tours/edit',
                'tour' => $tour,
                'categories' => $categories,
                'tourTypes' => $tourTypes
            ];

            view('main', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Cập nhật tour
     */
    public function update()
    {
        try {
            $id = $_POST['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Tour không tìm thấy');
            }

            // Validate dữ liệu
            $errors = $this->tourModel->validate($_POST);
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
               redirect('index.php?action=tours_edit&id=' . $id);
            }

            // Cập nhật tour
            $this->tourModel->update($id, $_POST);

            // Xử lý upload ảnh nếu có
            if (!empty($_FILES['images']) && $_FILES['images']['error'][0] === UPLOAD_ERR_OK) {
                $this->handleImageUpload($id, $_FILES['images']);
            }

            $_SESSION['success'] = 'Cập nhật tour thành công!';
            redirect('index.php?action=tours_show&id=' . $id);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours_show&id=' . ($_POST['id'] ?? ''));
        }
    }

    /**
     * Xóa tour
     */
    public function delete()
    {
        try {
            $id = $_POST['id'] ?? null;
            if (empty($id)) {
                throw new Exception('Tour không tìm thấy');
            }

            // Kiểm tra có booking
            $bookingModel = new Booking();
            $sql = "SELECT COUNT(*) as count FROM bookings WHERE tour_id = ?";
            $result = $bookingModel->fetchOne($sql, [$id]);
            
            if ($result['count'] > 0) {
                throw new Exception('Không thể xóa tour này vì đã có khách đặt');
            }

            $this->tourModel->delete($id);

            $_SESSION['success'] = 'Xóa tour thành công!';
            redirect('index.php?action=tours');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Xử lý upload ảnh tour
     */
    private function handleImageUpload($tourId, $files)
    {
        $uploadDir = PATH_ASSETS_UPLOADS . 'tours/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $sql = "INSERT INTO tour_images (tour_id, image_url, is_main) VALUES (?, ?, ?)";
        
        foreach ($files['name'] as $key => $filename) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newFilename = time() . '-' . $tourId . '-' . uniqid() . '.' . $ext;
                $filepath = $uploadDir . $newFilename;

                if (move_uploaded_file($files['tmp_name'][$key], $filepath)) {
                    $isMain = ($key === 0) ? 1 : 0;
                    $this->tourModel->query($sql, [$tourId, 'tours/' . $newFilename, $isMain]);
                }
            }
        }
    }

    /**
     * Lấy tour theo danh mục (API)
     */
    public function getByCategory()
    {
        try {
            $categoryId = $_GET['category_id'] ?? null;
            if (empty($categoryId)) {
                throw new Exception('Danh mục không hợp lệ');
            }

            $tours = $this->tourModel->findByCategory($categoryId);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $tours]);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * Kiểm tra slot còn trống
     */
    public function checkAvailability()
    {
        try {
            $tourId = $_GET['tour_id'] ?? null;
            $requiredSlots = $_GET['slots'] ?? 1;

            if (empty($tourId)) {
                throw new Exception('Tour không hợp lệ');
            }

            $hasSlots = $this->tourModel->hasAvailableSlots($tourId, $requiredSlots);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'available' => $hasSlots]);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * Lọc tour theo danh mục
     */
    public function filterByCategory($categoryId)
    {
        try {
            $filters = [
                'category_id' => $categoryId,
                'status' => 'active',
                'search' => $_GET['search'] ?? null,
            ];

            $tours = $this->tourModel->findAll($filters);
            
            // Lấy tất cả danh mục
            $categoryModel = new TourCategory();
            $categories = $categoryModel->findAll();
            
            // Lấy tên danh mục hiện tại
            $currentCategory = $categoryModel->find($categoryId);

            $data = [
                'title' => 'Quản Lý Tour - ' . ($currentCategory['name'] ?? 'Danh Mục'),
                'page' => 'tours',
                'content_view' => 'tours/index',
                'tours' => $tours,
                'categories' => $categories,
                'filters' => $filters,
                'current_category' => $currentCategory
            ];

            view('main', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('/');
        }
    }
}
?>
