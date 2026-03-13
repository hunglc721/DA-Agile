<?php
class TourDiaryController
{
    private $tourDiaryModel;
    private $tourModel;
    private $guideModel;

    public function __construct() {
        $this->tourDiaryModel = new TourDiary();
        $this->tourModel = new Tour();
        $this->guideModel = new Guide();
    }

    public function index() {
        $allTours = $this->tourModel->findAll();
        $tour_id = $_GET['tour_id'] ?? null;
        $diaries = [];
        $currentTour = null;
        $guides = $this->guideModel->findAll(['status' => 'active']);

        if ($tour_id) {
            $currentTour = $this->tourModel->find($tour_id);
            if ($currentTour) {
                $diaries = $this->tourDiaryModel->findByTour($tour_id);
            }
        }

        view('main', [
            'title' => 'Nhật Ký & Sự Cố',
            'page' => 'tour_diary',
            'content_view' => 'admin/tour_diary',
            'tours' => $allTours,
            'tour' => $currentTour,
            'diaries' => $diaries,
            'tour_id' => $tour_id,
            'guides' => $guides
        ]);
    }

    public function store() {
        try {
            $tour_id = $_POST['tour_id'];
            $imagePaths = $this->handleUpload();

            $weather = trim($_POST['weather'] ?? '');
            $health = trim($_POST['health_status'] ?? '');
            $activities = trim($_POST['special_activities'] ?? '');
            $feedback = trim($_POST['feedback'] ?? '');
            $coord = trim($_POST['rating_coordination'] ?? '');
            $spirit = trim($_POST['rating_spirit'] ?? '');
            $guideId = (int)($_POST['guide_id'] ?? 0);
            $guideName = '';
            if ($guideId) { $g = $this->guideModel->find($guideId); $guideName = $g['full_name'] ?? ''; }

            $base = trim($_POST['content']);
            $parts = [];
            if ($guideName !== '') { $parts[] = 'HDV: ' . $guideName; }
            if ($weather !== '') { $parts[] = 'Thời tiết: ' . $weather; }
            if ($health !== '') { $parts[] = 'Sức khỏe khách: ' . $health; }
            if ($activities !== '') { $parts[] = 'Hoạt động đặc biệt: ' . $activities; }
            if ($feedback !== '') { $parts[] = 'Phản hồi khách: ' . $feedback; }
            if ($coord !== '') { $parts[] = 'Đánh giá phối hợp: ' . $coord . '/5'; }
            if ($spirit !== '') { $parts[] = 'Tinh thần làm việc: ' . $spirit . '/5'; }
            $fullContent = trim(implode("\n", array_filter([$base, $parts ? implode("\n", $parts) : ''])));

            $data = [
                'tour_id' => $tour_id,
                'diary_type' => $_POST['diary_type'],
                'title' => $_POST['title'] ?? 'Ghi chú',
                'content' => $fullContent,
                'handling' => $_POST['handling'] ?? null,
                'location' => $_POST['location'] ?? '',
                'images' => !empty($imagePaths) ? json_encode($imagePaths) : null,
                'created_by' => 1
            ];

            $this->tourDiaryModel->create($data);
            $_SESSION['success'] = 'Đã thêm nhật ký!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        redirect('/tour_diary?tour_id=' . $_POST['tour_id']);
    }

    public function update() {
        try {
            $id = $_POST['id'];
            $oldEntry = $this->tourDiaryModel->find($id);
            $newImages = $this->handleUpload();
            
            $weather = trim($_POST['weather'] ?? '');
            $health = trim($_POST['health_status'] ?? '');
            $activities = trim($_POST['special_activities'] ?? '');
            $feedback = trim($_POST['feedback'] ?? '');
            $coord = trim($_POST['rating_coordination'] ?? '');
            $spirit = trim($_POST['rating_spirit'] ?? '');
            $guideId = (int)($_POST['guide_id'] ?? 0);
            $guideName = '';
            if ($guideId) { $g = $this->guideModel->find($guideId); $guideName = $g['full_name'] ?? ''; }
            $base = trim($_POST['content']);
            $parts = [];
            if ($guideName !== '') { $parts[] = 'HDV: ' . $guideName; }
            if ($weather !== '') { $parts[] = 'Thời tiết: ' . $weather; }
            if ($health !== '') { $parts[] = 'Sức khỏe khách: ' . $health; }
            if ($activities !== '') { $parts[] = 'Hoạt động đặc biệt: ' . $activities; }
            if ($feedback !== '') { $parts[] = 'Phản hồi khách: ' . $feedback; }
            if ($coord !== '') { $parts[] = 'Đánh giá phối hợp: ' . $coord . '/5'; }
            if ($spirit !== '') { $parts[] = 'Tinh thần làm việc: ' . $spirit . '/5'; }
            $fullContent = trim(implode("\n", array_filter([$base, $parts ? implode("\n", $parts) : ''])));

            $data = [
                'diary_type' => $_POST['diary_type'],
                'title' => $_POST['title'],
                'content' => $fullContent,
                'handling' => $_POST['handling'] ?? null,
                'location' => $_POST['location'] ?? '',
                'images' => !empty($newImages) ? json_encode($newImages) : $oldEntry['images']
            ];

            $this->tourDiaryModel->update($id, $data);
            $_SESSION['success'] = 'Cập nhật thành công!';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        redirect('/tour_diary?tour_id=' . $_POST['tour_id']);
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? null;
        if ($id) {
            $this->tourDiaryModel->delete($id);
            $_SESSION['success'] = 'Đã xóa bản ghi!';
        }
        redirect('/tour_diary?tour_id=' . $tour_id);
    }

    private function handleUpload() {
        $uploadedFiles = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $targetDir = ROOT_PATH . "assets/uploads/diaries/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

            foreach ($_FILES['images']['name'] as $key => $name) {
                $tmpName = $_FILES['images']['tmp_name'][$key];
                $fileName = time() . '_' . basename($name);
                if (move_uploaded_file($tmpName, $targetDir . $fileName)) {
                    $uploadedFiles[] = 'assets/uploads/diaries/' . $fileName;
                }
            }
        }
        return $uploadedFiles;
    }

    /**
     * Xem tất cả báo cáo sự cố
     */
    public function incidents()
    {
        // Kiểm tra đăng nhập và quyền admin
        session_start();
        if (!isset($_SESSION['user']['id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        if (!isset($_SESSION['user']['is_admin']) || !$_SESSION['user']['is_admin']) {
            die('<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này</p>');
        }

        try {
            // Lấy tiêu chí lọc
            $status = $_GET['status'] ?? null;
            $from_date = $_GET['from_date'] ?? null;
            $to_date = $_GET['to_date'] ?? null;
            $search = $_GET['search'] ?? null;

            // Query lấy tất cả báo cáo sự cố
            $query = "SELECT 
                        td.id, td.title, td.content, td.created_at, td.status, 
                        t.id as tour_id, t.name as tour_name,
                        u.id as guide_id, u.full_name as guide_name, u.phone as guide_phone
                      FROM tour_diaries td
                      JOIN tours t ON td.tour_id = t.id
                      JOIN users u ON td.created_by = u.id
                      WHERE td.diary_type = 'incident'";
            
            $params = [];

            if ($status) {
                $query .= " AND td.status = ?";
                $params[] = $status;
            }

            if ($from_date) {
                $query .= " AND DATE(td.created_at) >= ?";
                $params[] = $from_date;
            }

            if ($to_date) {
                $query .= " AND DATE(td.created_at) <= ?";
                $params[] = $to_date;
            }

            if ($search) {
                $query .= " AND (td.title LIKE ? OR td.content LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            $query .= " ORDER BY td.created_at DESC";

            $result = $this->tourDiaryModel->query($query, $params);
            $incidents = ($result && is_object($result)) ? $result->fetchAll(PDO::FETCH_ASSOC) : [];

            // Tính toán thống kê
            $statsQuery = "SELECT 
                          COUNT(*) as total,
                          COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as pending,
                          COALESCE(SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END), 0) as in_progress,
                          COALESCE(SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END), 0) as resolved
                        FROM tour_diaries
                        WHERE diary_type = 'incident'";
            
            $statsResult = $this->tourDiaryModel->query($statsQuery);
            $stats = ($statsResult && is_object($statsResult)) ? $statsResult->fetch(PDO::FETCH_ASSOC) : ['total' => 0, 'pending' => 0, 'in_progress' => 0, 'resolved' => 0];

            view('main', [
                'title' => 'Báo Cáo Sự Cố',
                'page' => 'incident_reports',
                'content_view' => 'admin/incident_reports',
                'incidents' => $incidents,
                'stats' => $stats
            ]);

        } catch (Exception $e) {
            error_log("Error in incidents: " . $e->getMessage() . " | " . $e->getTraceAsString());
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: index.php?action=dashboard');
            exit;
        }
    }
}
?>
