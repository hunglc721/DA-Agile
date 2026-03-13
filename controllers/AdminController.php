<?php
class AdminController extends BaseController
{
    private $user;
    private $tour;
    private $booking;
    private $tourDiary;

    public function __construct() {
        $this->user = new User();
        $this->tour = new Tour();
        $this->booking = new Booking();
        $this->tourDiary = new TourDiary();
    }

    public function dashboard() {
        // Kiểm tra đăng nhập và quyền admin
        $this->checkLogin();
        if (!isset($_SESSION['user']['is_admin']) || !$_SESSION['user']['is_admin']) {
            die('<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này</p>');
        }

        // 1. Thống kê cơ bản
        $tours = $this->tour->findAll();
        $users = $this->user->findAll();
        $bookings = $this->booking->findAll();
        
        $stats = [
            'total_tours' => count($tours),
            'total_users' => count($users),
            'total_bookings' => count($bookings),
            'pending_bookings' => 0,
            'ongoing_tours' => 0,
            'new_users' => 0
        ];

        // Đếm booking chờ xử lý
        if (!empty($bookings)) {
            foreach ($bookings as $b) {
                if ($b['status'] === 'pending') {
                    $stats['pending_bookings']++;
                }
            }
        }

        // 2. Biểu đồ Doanh Thu
        $revenueRaw = $this->booking->getMonthlyRevenue(date('Y'));
        $revenueData = array_fill(1, 12, 0);
        $totalRevenue = 0;
        
        if (!empty($revenueRaw)) {
            foreach ($revenueRaw as $item) {
                $month = (int)$item['month'];
                $revenue = (int)$item['revenue'];
                $revenueData[$month] = $revenue;
                $totalRevenue += $revenue;
            }
        }
        $stats['total_revenue'] = $totalRevenue;
        $stats['monthly_revenue'] = $totalRevenue / 12; // Trung bình tháng

        // 3. Biểu đồ Trạng Thái
        $statusRaw = $this->booking->getStatusStatistics();
        $statusData = ['labels' => [], 'data' => []];
        
        if (!empty($statusRaw)) {
            foreach ($statusRaw as $item) {
                $statusData['labels'][] = ucfirst($item['status']);
                $statusData['data'][] = (int)$item['count'];
            }
        }

        view('main', [
            'title' => 'Bảng Điều Khiển',
            'page' => 'dashboard',
            'content_view' => 'admin/dashboard',
            'stats' => $stats,
            'revenueData' => array_values($revenueData),
            'statusData' => $statusData,
            'bookings' => array_slice($bookings, 0, 5) // Lấy 5 booking gần nhất
        ]);
    }

    public function communication() {
        // Kiểm tra đăng nhập và quyền admin
        $this->checkLogin();
        if (!isset($_SESSION['user']['is_admin']) || !$_SESSION['user']['is_admin']) {
            die('<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này</p>');
        }

        // Lấy tất cả tin nhắn từ HDV
        $messages = $this->getAllMessagesForAdmin();

        view('main', [
            'title' => 'Trao Đổi Với Hướng Dẫn Viên',
            'page' => 'communication',
            'content_view' => 'admin/communication',
            'messages' => $messages
        ]);
    }

    private function getAllMessagesForAdmin() {
        try {
            $db = new BaseModel();
            $query = "
                SELECT m.*, 
                       u.full_name as sender_name,
                       u.phone,
                       u.email
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                WHERE (
                    (m.receiver_id = ? AND m.receiver_type = 'admin') OR
                    (m.sender_id = ? AND m.receiver_type = 'guide')
                )
                ORDER BY m.created_at ASC
            ";
            return $db->fetchAll($query, [$_SESSION['user']['id'], $_SESSION['user']['id']]);
        } catch (Exception $e) {
            error_log("Error fetching messages: " . $e->getMessage());
            return [];
        }
    }

    public function admin_send_message() {
        // Kiểm tra đăng nhập và quyền admin
        $this->checkLogin();
        if (!isset($_SESSION['user']['is_admin']) || !$_SESSION['user']['is_admin']) {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện hành động này';
            $this->redirect('communication');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Phương thức không được hỗ trợ';
            $this->redirect('communication');
        }

        $receiver_id = $_POST['receiver_id'] ?? null;
        $message = $_POST['message'] ?? null;

        if (!$receiver_id || !$message) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
            $this->redirect('communication');
        }

        try {
            $db = new BaseModel();
            $query = "
                INSERT INTO messages (sender_id, receiver_id, receiver_type, content, created_at)
                VALUES (?, ?, 'guide', ?, NOW())
            ";
            $db->query($query, [$_SESSION['user']['id'], $receiver_id, $message]);
            $_SESSION['success'] = 'Gửi tin nhắn thành công';
        } catch (Exception $e) {
            error_log("Error sending message: " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi khi gửi tin nhắn';
        }

        $this->redirect('communication');
    }

    /**
     * Xem tất cả báo cáo sự cố
     */
    public function incidentReports()
    {
        // Kiểm tra đăng nhập và quyền admin
        $this->checkLogin();
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

            $result = $this->tourDiary->query($query, $params);
            $incidents = ($result && is_object($result)) ? $result->fetchAll(PDO::FETCH_ASSOC) : [];

            // Tính toán thống kê
            $statsQuery = "SELECT 
                          COUNT(*) as total,
                          COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as pending,
                          COALESCE(SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END), 0) as in_progress,
                          COALESCE(SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END), 0) as resolved
                        FROM tour_diaries
                        WHERE diary_type = 'incident'";
            
            $statsResult = $this->tourDiary->query($statsQuery);
            $stats = ($statsResult && is_object($statsResult)) ? $statsResult->fetch(PDO::FETCH_ASSOC) : ['total' => 0, 'pending' => 0, 'in_progress' => 0, 'resolved' => 0];

            view('main', [
                'title' => 'Báo Cáo Sự Cố',
                'page' => 'incident_reports',
                'content_view' => 'admin/incident_reports',
                'incidents' => $incidents,
                'stats' => $stats
            ]);

        } catch (Exception $e) {
            error_log("Error in incidentReports: " . $e->getMessage() . " | " . $e->getTraceAsString());
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: index.php?action=dashboard');
            exit;
        }
    }

}
?>