<?php
class TourDetailController
{
    private $tourModel;
    private $tourImageModel;
    private $tourPolicyModel;
    private $tourItineraryModel;
    private $tourPricingModel;
    private $tourInclusionModel;
    private $supplierModel;

    public function __construct()
    {
        $this->tourModel = new Tour();
        $this->tourImageModel = new TourImage();
        $this->tourPolicyModel = new TourPolicy();
        $this->tourItineraryModel = new TourItinerary();
        $this->tourPricingModel = new TourPricing();
        $this->tourInclusionModel = new TourInclusion();
        $this->supplierModel = new Supplier();
    }

    /**
     * Hiển thị chi tiết tour
     */
    public function detail()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            $tour = $this->tourModel->findWithDetails($tourId);
            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            // Lấy nhà cung cấp
            if (!empty($tour['supplier_id'])) {
                $tour['supplier'] = $this->supplierModel->find($tour['supplier_id']);
            }

            // Lấy chính sách
            $policies = $this->tourPolicyModel->findByTour($tourId);
            $inclusionsByType = [];
            $inclusions = $this->tourInclusionModel->findByTour($tourId);
            foreach (array_unique(array_column($inclusions, 'type')) as $type) {
                $inclusionsByType[$type] = array_filter($inclusions, fn($i) => $i['type'] === $type);
            }

            view('main', [
                'title' => 'Chi Tiết Tour',
                'page' => 'tours',
                'content_view' => 'tours/detail',
                'tour' => $tour,
                'images' => $tour['images'] ?? [],
                'policies' => $policies,
                'inclusions' => $inclusions,
                'inclusionsByType' => $inclusionsByType
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Hiển thị lịch trình
     */
    public function itinerary()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            $tour = $this->tourModel->find($tourId);
            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            $itinerary = $this->tourItineraryModel->findByTour($tourId);

            view('main', [
                'title' => 'Lịch Trình Tour',
                'page' => 'tours',
                'content_view' => 'tours/itinerary',
                'tour' => $tour,
                'itinerary' => $itinerary
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Hiển thị bảng giá
     */
    public function pricing()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            $tour = $this->tourModel->find($tourId);
            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            $pricings = $this->tourPricingModel->findByTour($tourId);

            view('main', [
                'title' => 'Bảng Giá Tour',
                'page' => 'tours',
                'content_view' => 'tours/pricing',
                'tour' => $tour,
                'pricings' => $pricings
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Hiển thị hình ảnh
     */
    public function images()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            $tour = $this->tourModel->find($tourId);
            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            $images = $this->tourImageModel->findByTour($tourId);

            view('main', [
                'title' => 'Hình Ảnh Tour',
                'page' => 'tours',
                'content_view' => 'tours/images',
                'tour' => $tour,
                'images' => $images
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Hiển thị chính sách và bao gồm
     */
    public function policies()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            $tour = $this->tourModel->find($tourId);
            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('index.php?action=tours');
            }

            $policies = $this->tourPolicyModel->findByTour($tourId);
            $inclusions = $this->tourInclusionModel->findByTour($tourId);

            view('main', [
                'title' => 'Chính Sách Tour',
                'page' => 'tours',
                'content_view' => 'tours/policies',
                'tour' => $tour,
                'policies' => $policies,
                'inclusions' => $inclusions
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Đặt ảnh chính
     */
    public function setMainImage()
    {
        try {
            $imageId = $_GET['id'] ?? null;
            $tourId = $_GET['tour_id'] ?? null;

            if (!$imageId || !$tourId) {
                throw new Exception('Dữ liệu không hợp lệ');
            }

            $this->tourImageModel->setMainImage($tourId, $imageId);
            $_SESSION['success'] = 'Đặt ảnh chính thành công';
            redirect('index.php?action=tours_images&id=' . $tourId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Xóa ảnh
     */
    public function deleteImage()
    {
        try {
            $imageId = $_GET['id'] ?? null;
            $tourId = $_GET['tour_id'] ?? null;

            if (!$imageId) {
                throw new Exception('Dữ liệu không hợp lệ');
            }

            $this->tourImageModel->delete($imageId);
            $_SESSION['success'] = 'Xóa ảnh thành công';
            redirect('index.php?action=tours_images&id=' . $tourId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Tạo chính sách
     */
    public function createPolicy()
    {
        try {
            $tourId = $_GET['tour_id'] ?? null;
            if (!$tourId) {
                throw new Exception('Tour không tồn tại');
            }

            $tour = $this->tourModel->find($tourId);
            if (!$tour) {
                throw new Exception('Tour không tồn tại');
            }

            view('main', [
                'title' => 'Thêm Chính Sách',
                'page' => 'tours',
                'content_view' => 'tours/policy_create',
                'tour' => $tour
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=tours');
        }
    }

    /**
     * Lưu chính sách
     */
    public function storePolicy()
    {
        try {
            $data = $_POST;

            if (empty($data['title']) || empty($data['content'])) {
                throw new Exception('Vui lòng nhập đủ thông tin');
            }

            $errors = $this->tourPolicyModel->validate($data);
            if (!empty($errors)) {
                throw new Exception(implode(', ', $errors));
            }

            $this->tourPolicyModel->create($data);
            $_SESSION['success'] = 'Thêm chính sách thành công';
            redirect('index.php?action=tours_policies&id=' . $data['tour_id']);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=tours');
        }
    }
}
?>
