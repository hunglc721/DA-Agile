<?php
class GuideProfileController
{
    private $guideModel;
    private $userModel;
    private $tourModel;
    private $tourDepartureModel;
    private $reportModel;
    private $tourDiaryModel;

    public function __construct()
    {
        $this->guideModel = new Guide();
        $this->userModel = new User();
        $this->tourModel = new Tour();
        $this->tourDepartureModel = new TourDeparture();
        $this->reportModel = new Report();
        $this->tourDiaryModel = new TourDiary();

        // Kiểm tra quyền HDV
        $this->requireGuideRole();
    }

    /**
     * Kiểm tra user có phải là HDV không
     */
    private function requireGuideRole()
    {
        if (!isset($_SESSION['user'])) {
            redirect('login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $sql = "SELECT * FROM guides WHERE user_id = ?";
        $guide = $this->guideModel->fetchOne($sql, [$userId]);

        if (!$guide) {
            // Thay vì chuyển hướng, hiển thị lỗi rõ ràng để debug.
            // Điều này phá vỡ vòng lặp chuyển hướng.
            $userName = $_SESSION['user']['full_name'] ?? 'N/A';
            die("<h1>Lỗi Xác Thực Quyền</h1><p>Tài khoản '{$userName}' (User ID: {$userId}) đã đăng nhập thành công nhưng không được tìm thấy trong bảng 'guides'. Vui lòng kiểm tra lại cơ sở dữ liệu.</p>");
        }

        $_SESSION['guide_id'] = $guide['id'];
    }

    /**
     * Dashboard HDV - Trang chủ
     */
    public function dashboard()
    {
        // Chức năng dashboard không còn được sử dụng.
        // Chuyển hướng HDV đến trang danh sách tour được phân công làm trang chính.
        $this->assignedTours();
    }

    /**
     * Xem phản hồi khách hàng
     */
    public function customerFeedback()
    {
        try {
            $guideId = $_SESSION['guide_id'];

            // Lấy các feedback từ khách hàng từ tour_diaries
            // Lấy feedback từ các tour được phân công cho HDV
            $sql = "SELECT td.*, t.name as tour_name, u.full_name,
                    CAST(SUBSTRING_INDEX(td.title, ':', 1) AS UNSIGNED) as rating
                    FROM tour_diaries td
                    JOIN tours t ON td.tour_id = t.id
                    JOIN tour_assignments ta ON t.id = ta.tour_id
                    JOIN users u ON td.created_by = u.id
                    WHERE ta.guide_id = ? AND td.diary_type = 'feedback'
                    ORDER BY td.created_at DESC";
            $feedbacks = $this->guideModel->fetchAll($sql, [$guideId]);

            // Nếu không có feedback từ tour_assignments, hãy cố gắng tìm feedback được tạo bởi guide này cho tour nào đó
            if (empty($feedbacks)) {
                $sql_alt = "SELECT td.*, t.name as tour_name, u.full_name,
                            CAST(SUBSTRING_INDEX(td.title, ':', 1) AS UNSIGNED) as rating
                            FROM tour_diaries td
                            JOIN tours t ON td.tour_id = t.id
                            JOIN guides g ON td.created_by = g.user_id
                            JOIN users u ON td.created_by = u.id
                            WHERE g.id = ? AND td.diary_type = 'feedback'
                            ORDER BY td.created_at DESC";
                $feedbacks = $this->guideModel->fetchAll($sql_alt, [$guideId]);
            }

            view('main', [
                'title' => 'Phản Hồi Khách Hàng',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/customer_feedback',
                'feedbacks' => $feedbacks ?? []
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_dashboard');
        }
    }

    /**
     * Xem thông báo
     */
    public function notifications()
    {
        try {
            $userId = $_SESSION['user']['id'];

            // Lấy tất cả thông báo
            $sql = "SELECT * FROM notifications 
                    WHERE user_id = ?
                    ORDER BY created_at DESC";
            $notifications = $this->userModel->fetchAll($sql, [$userId]);

            // Đánh dấu tất cả là đã đọc
            $sql_update = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0";
            $this->userModel->query($sql_update, [$userId]);

            view('main', [
                'title' => 'Thông Báo',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/notifications',
                'notifications' => $notifications ?? []
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_dashboard');
        }
    }

    /**
     * Xem tour được phân công
     */
    public function assignedTours()
    {
        try {
            $guideId = $_SESSION['guide_id'];

            // Lấy các tour được phân công cho HDV với số khách hàng
            $sql = "SELECT ta.id as assignment_id, ta.assignment_date, ta.status as assignment_status, t.*,
                    (SELECT COUNT(*) FROM bookings WHERE tour_id = t.id AND status = 'confirmed') as customer_count
                    FROM tour_assignments ta
                    JOIN tours t ON ta.tour_id = t.id
                    WHERE ta.guide_id = ?
                    ORDER BY ta.assignment_date DESC";
            $tours = $this->guideModel->fetchAll($sql, [$guideId]);

            view('main', [
                'title' => 'Tour Được Phân Công',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/assigned_tours',
                'tours' => $tours ?? []
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_dashboard');
        }
    }

    /**
     * Xem chi tiết tour đã được phân bổ
     */
    public function tour_detail()
    {
        try {
            error_log("guide_tour_detail called with tourId: " . ($_GET['id'] ?? 'null'));
            
            $tourId = $_GET['id'] ?? null;
            
            if (!$tourId) {
                throw new Exception('ID tour không hợp lệ');
            }

            $guideId = $_SESSION['guide_id'] ?? null;
            
            if (!$guideId) {
                throw new Exception('Guide ID không hợp lệ');
            }

            error_log("Current guide_id: " . $guideId);

            // Kiểm tra tour có được phân công cho HDV này không
            $checkSql = "SELECT ta.id FROM tour_assignments ta 
                        WHERE ta.tour_id = ? AND ta.guide_id = ?";
            $assigned = $this->guideModel->fetchOne($checkSql, [$tourId, $guideId]);
            error_log("Assignment check result: " . json_encode($assigned));
            
            if (!$assigned) {
                throw new Exception('Bạn không có quyền xem tour này');
            }

            // Lấy chi tiết tour
            $tour = $this->tourModel->find($tourId);
            if (!$tour) {
                throw new Exception('Không tìm thấy tour');
            }

            // Lấy hình ảnh tour
            $imagesSql = "SELECT * FROM tour_images WHERE tour_id = ? ORDER BY id ASC";
            try {
                $images = $this->tourModel->fetchAll($imagesSql, [$tourId]);
            } catch (Exception $e) {
                error_log("Images query error: " . $e->getMessage());
                $images = [];
            }
            if (!$images) $images = [];

            // Lấy thông tin lịch trình
            $itinerarySql = "SELECT * FROM tour_itineraries WHERE tour_id = ? ORDER BY day_number ASC";
            try {
                $itinerary = $this->tourModel->fetchAll($itinerarySql, [$tourId]);
            } catch (Exception $e) {
                error_log("Itinerary query error: " . $e->getMessage());
                $itinerary = [];
            }
            if (!$itinerary) $itinerary = [];

            // Lấy danh sách khách hàng
            $customerSql = "SELECT DISTINCT u.id, u.full_name as customer_name, u.email as customer_email, u.phone as customer_phone, b.id as booking_id, b.status as booking_status
                           FROM bookings b
                           JOIN users u ON b.customer_id = u.id
                           WHERE b.tour_id = ?
                           ORDER BY u.full_name";
            try {
                $customers = $this->tourModel->fetchAll($customerSql, [$tourId]);
            } catch (Exception $e) {
                error_log("Customers query error: " . $e->getMessage());
                $customers = [];
            }
            if (!$customers) {
                $customers = [];
            }

            error_log("tour_detail success - Tour: " . $tour['name'] . ", Images: " . count($images) . ", Itinerary: " . count($itinerary) . ", Customers: " . count($customers));

            view('main', [
                'title' => 'Chi Tiết Tour',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/tour_detail',
                'tour' => $tour,
                'images' => $images,
                'itinerary' => $itinerary,
                'customers' => $customers
            ]);
        } catch (Exception $e) {
            error_log("tour_detail error: " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            // DEBUG: Display error directly
            die("<div style='padding:20px; background:#fff3cd; border:1px solid #ffc107; margin:20px; border-radius:4px;'>
                <h3>Lỗi chi tiết tour</h3>
                <p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                <p><a href='index.php?action=guide_assigned_tours'>Quay lại danh sách tour</a></p>
            </div>");
        }
    }

    /**
     * Xem danh sách chi tiết khách hàng của tour (view full-page)
     */
    public function tour_customers_detail()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('guide_assigned_tours');
                return;
            }

            $guideId = $_SESSION['guide_id'];

            // Lấy thông tin tour
            $sql_tour = "SELECT t.* FROM tours t
                        JOIN tour_assignments ta ON t.id = ta.tour_id
                        WHERE t.id = ? AND ta.guide_id = ?";
            $tour = $this->tourModel->fetchOne($sql_tour, [(int)$tourId, $guideId]);

            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại hoặc bạn không được phân công';
                redirect('guide_assigned_tours');
                return;
            }

            // Lấy danh sách khách hàng chi tiết
            $sql_customers = "SELECT
                                u.id,
                                u.full_name,
                                u.phone,
                                u.email,
                                u.address,
                                b.id as booking_id,
                                b.number_of_people,
                                b.special_requests,
                                b.status as booking_status,
                                b.total_price,
                                b.created_at as booking_date,
                                b.checkin_status,
                                b.checkin_time
                              FROM bookings b
                              JOIN users u ON b.user_id = u.id
                              WHERE b.tour_id = ? AND b.status IN ('confirmed', 'pending')
                              ORDER BY u.full_name ASC";
            $customers = $this->userModel->fetchAll($sql_customers, [(int)$tourId]);

            view('main', [
                'title' => 'Danh Sách Khách Hàng Tour',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/tour_customers',
                'tour' => $tour,
                'customers' => $customers ?? []
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Xem danh sách khách hàng của tour
     */
    public function tourCustomers()
    {
        // Đảm bảo chỉ trả về JSON
        header('Content-Type: application/json');

        try {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                throw new Exception('Tour không tồn tại');
            }

            $guideId = $_SESSION['guide_id'];

            // Lấy thông tin tour để xác thực và hiển thị
            $sql_tour = "SELECT t.id, t.name FROM tours t JOIN tour_assignments ta ON t.id = ta.tour_id WHERE t.id = ? AND ta.guide_id = ?";
            $tour = $this->tourModel->fetchOne($sql_tour, [(int)$tourId, $guideId]);

            if (!$tour) {
                throw new Exception('Tour không tồn tại hoặc bạn không được phân công cho tour này.');
            }

            // Lấy danh sách khách hàng với thông tin chi tiết hơn
            $sql_customers = "SELECT
                                u.id,
                                u.full_name,
                                u.phone,
                                u.email,
                                u.address,
                                b.id as booking_id,
                                b.number_of_people,
                                b.special_requests,
                                b.status as booking_status,
                                b.total_price,
                                b.created_at as booking_date,
                                b.checkin_status,
                                b.checkin_time
                              FROM bookings b
                              JOIN users u ON b.user_id = u.id
                              WHERE b.tour_id = ? AND b.status IN ('confirmed', 'pending')
                              ORDER BY u.full_name ASC";
            $customers = $this->userModel->fetchAll($sql_customers, [(int)$tourId]);

            echo json_encode(['success' => true, 'tour' => $tour, 'customers' => $customers ?? []]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit; // Dừng thực thi để đảm bảo chỉ có JSON được trả về
    }

    /**
     * Xác nhận tour
     */
    public function confirmTour()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('guide_assigned_tours');
                return;
            }

            $guideId = $_SESSION['guide_id'];

            // Lấy thông tin tour
            $sql = "SELECT ta.id as assignment_id, ta.assignment_date, ta.status as assignment_status, t.*
                    FROM tour_assignments ta
                    JOIN tours t ON ta.tour_id = t.id
                    WHERE ta.guide_id = ? AND t.id = ?
                    LIMIT 1";
            $tour = $this->guideModel->fetchOne($sql, [$tourId, $guideId]);

            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại hoặc không phải của bạn';
                redirect('guide_assigned_tours');
                return;
            }

            view('main', [
                'title' => 'Xác Nhận Tour',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/confirm_tour',
                'tour' => $tour
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Xử lý xác nhận tour
     */
    public function confirmTourStore()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('guide_assigned_tours');
                return;
            }

            $tourId = $_POST['tour_id'] ?? null;
            $notes = $_POST['notes'] ?? '';

            if (!$tourId) {
                $_SESSION['error'] = 'Dữ liệu không hợp lệ';
                redirect('guide_assigned_tours');
                return;
            }

            // Cập nhật status tour thành confirmed
            $sql = "UPDATE tour_assignments SET status = 'confirmed' WHERE tour_id = ? AND guide_id = ?";
            $this->guideModel->query($sql, [$tourId, $_SESSION['guide_id']]);

            $_SESSION['success'] = 'Xác nhận tour thành công!';
            redirect('guide_assigned_tours');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Xem lịch trình tour
     */
    public function itinerary()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('guide_assigned_tours');
                return;
            }

            $guideId = $_SESSION['guide_id'];

            // Lấy thông tin tour
            $sql = "SELECT t.name as tour_name, t.id as tour_id FROM tour_assignments ta
                    JOIN tours t ON ta.tour_id = t.id
                    WHERE t.id = ? AND ta.guide_id = ?";
            $tour = $this->guideModel->fetchOne($sql, [$tourId, $guideId]);

            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('guide_assigned_tours');
                return;
            }

            // Lấy lịch trình
            $sql_itinerary = "SELECT * FROM tour_itineraries WHERE tour_id = ? ORDER BY day_number ASC";
            $itinerary = $this->tourModel->fetchAll($sql_itinerary, [$tour['tour_id']]);

            view('main', [
                'title' => 'Lịch Trình Tour',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/itinerary',
                'tour' => $tour,
                'itinerary' => $itinerary ?? []
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Cập nhật tình trạng tour
     */
    public function updateStatus()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('guide_assigned_tours');
                return;
            }

            $guideId = $_SESSION['guide_id'];

            // Lấy thông tin tour được gán
            $sql = "SELECT t.id, t.name as tour_name, ta.status as assignment_status
                    FROM tour_assignments ta
                    JOIN tours t ON ta.tour_id = t.id
                    WHERE t.id = ? AND ta.guide_id = ?";
            $tour = $this->guideModel->fetchOne($sql, [$tourId, $guideId]);

            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('guide_assigned_tours');
                return;
            }

            view('main', [
                'title' => 'Cập Nhật Tình Trạng Tour',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/update_status',
                'tour' => $tour
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Xử lý cập nhật tình trạng
     */
    public function updateStatusStore()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('guide_assigned_tours');
                return;
            }

            $tourId = $_POST['tour_id'] ?? null;
            $status = $_POST['status'] ?? '';
            $notes = $_POST['notes'] ?? '';

            if (!$tourId || !in_array($status, ['ongoing', 'completed', 'cancelled'])) {
                $_SESSION['error'] = 'Dữ liệu không hợp lệ';
                redirect('guide_assigned_tours');
                return;
            }

            // Cập nhật status
            $sql = "UPDATE tour_assignments SET status = ?, notes = ? WHERE tour_id = ? AND guide_id = ?";
            $this->guideModel->query($sql, [$status, $notes, $tourId, $_SESSION['guide_id']]);

            $_SESSION['success'] = 'Cập nhật tình trạng thành công!';
            redirect('guide_assigned_tours');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Báo cáo sự cố
     */
    public function incidentReport()
    {
        try {
            $tourId = $_GET['id'] ?? null;

            view('main', [
                'title' => 'Báo Cáo Sự Cố',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/incident_report',
                'tour_id' => $tourId
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Lưu báo cáo sự cố
     */
    public function storeIncidentReport()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('guide_assigned_tours');
                return;
            }

            $userId = $_SESSION['user']['id'];
            $tourId = $_POST['tour_id'] ?? null;
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $severity = $_POST['severity'] ?? 'medium';

            if (!$tourId || empty($title) || empty($description)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                redirect('guide_incident_report');
                return;
            }

            // Lưu vào nhật ký tour
            $content = "Mức độ: " . $severity . "\n" . $description;
            $sql = "INSERT INTO tour_diaries (tour_id, diary_type, title, content, created_by, created_at)
                    VALUES (?, 'incident', ?, ?, ?, NOW())";
            $this->tourDiaryModel->query($sql, [$tourId, $title, $content, $userId]);

            $_SESSION['success'] = 'Báo cáo sự cố đã được gửi!';
            redirect('guide_assigned_tours');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_incident_report');
        }
    }

    /**
     * Hoàn thành tour
     */
    public function completeTour()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('guide_assigned_tours');
                return;
            }

            $guideId = $_SESSION['guide_id'];

            // Lấy thông tin tour được gán
            $sql = "SELECT t.id, t.name as tour_name FROM tour_assignments ta
                    JOIN tours t ON ta.tour_id = t.id
                    WHERE t.id = ? AND ta.guide_id = ?";
            $tour = $this->guideModel->fetchOne($sql, [$tourId, $guideId]);

            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại';
                redirect('guide_assigned_tours');
                return;
            }

            view('main', [
                'title' => 'Hoàn Thành Tour',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/complete_tour',
                'tour' => $tour
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Lưu hoàn thành tour
     */
    public function storeCompleteTour()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('guide_assigned_tours');
                return;
            }

            $tourId = $_POST['tour_id'] ?? null;

            if (!$tourId) {
                $_SESSION['error'] = 'Dữ liệu không hợp lệ';
                redirect('guide_assigned_tours');
                return;
            }

            // Cập nhật status tour thành completed
            $sql = "UPDATE tour_assignments SET status = 'completed' WHERE tour_id = ? AND guide_id = ?";
            $this->guideModel->query($sql, [$tourId, $_SESSION['guide_id']]);

            $_SESSION['success'] = 'Tour đã được hoàn thành!';
            redirect('guide_assigned_tours');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Gửi báo cáo tổng kết
     */
    public function finalReport()
    {
        try {
            $tourId = $_GET['id'] ?? null;

            view('main', [
                'title' => 'Báo Cáo Tổng Kết',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/final_report',
                'tour_id' => $tourId
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Lưu báo cáo tổng kết
     */
    public function storeFinalReport()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('guide_assigned_tours');
                return;
            }

            $userId = $_SESSION['user']['id'];
            $tourId = $_POST['tour_id'] ?? null;
            $summary = $_POST['summary'] ?? '';
            $expenses = $_POST['expenses'] ?? 0;

            if (!$tourId || empty($summary)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                redirect('guide_final_report');
                return;
            }

            // Lưu vào nhật ký tour
            $title = "Báo cáo tổng kết tour";
            $content = "Chi phí phát sinh: " . number_format($expenses) . " VND\n\n" . $summary;
            $sql = "INSERT INTO tour_diaries (tour_id, diary_type, title, content, created_by, created_at)
                    VALUES (?, 'report', ?, ?, ?, NOW())";
            $this->tourDiaryModel->query($sql, [$tourId, $title, $content, $userId]);

            $_SESSION['success'] = 'Báo cáo tổng kết đã được gửi!';
            redirect('guide_assigned_tours');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_final_report');
        }
    }

    /**
     * Trao đổi với Admin (Nhắn tin)
     */
    public function communication()
    {
        try {
            $userId = $_SESSION['user']['id'];

            // Lấy các tin nhắn
            $sql = "SELECT * FROM messages 
                    WHERE (sender_id = ? OR receiver_id = ?) AND (receiver_type = 'admin' OR receiver_type = 'guide' OR receiver_type IS NULL)
                    ORDER BY created_at ASC";
            $messages = $this->userModel->fetchAll($sql, [$userId, $userId]);

            view('main', [
                'title' => 'Trao Đổi Với Admin',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/communication',
                'messages' => $messages ?? []
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_dashboard');
        }
    }

    /**
     * Gửi tin nhắn
     */
    public function sendMessage()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('guide_communication');
                return;
            }

            $userId = $_SESSION['user']['id'];
            $message = $_POST['message'] ?? '';

            if (empty($message)) {
                $_SESSION['error'] = 'Vui lòng nhập nội dung tin nhắn';
                redirect('guide_communication');
                return;
            }

            // Lưu tin nhắn
            $sql = "INSERT INTO messages (sender_id, receiver_id, receiver_type, content, created_at)
                    VALUES (?, 1, 'admin', ?, NOW())";
            $this->userModel->query($sql, [$userId, $message]);

            $_SESSION['success'] = 'Tin nhắn đã được gửi!';
            redirect('guide_communication');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_communication');
        }
    }

    /**
     * Check-in & Điểm danh khách hàng
     */
    public function checkin()
    {
        try {
            $userId = $_SESSION['user']['id'];
            $tourId = $_GET['id'] ?? null;

            if (!$tourId) {
                throw new Exception('ID tour không hợp lệ');
            }

            // Lấy thông tin tour
            $tour = $this->tourModel->find($tourId);
            if (!$tour) {
                throw new Exception('Tour không tồn tại');
            }

            // Lấy danh sách booking cho tour này
            $sql = "SELECT b.*, u.full_name, u.phone, u.email
                    FROM bookings b
                    JOIN users u ON b.user_id = u.id
                    WHERE b.tour_id = ?
                    ORDER BY b.booking_date DESC";
            $bookings = $this->userModel->fetchAll($sql, [$tourId]);

            // Thống kê
            $stats = [
                'total' => count($bookings),
                'checked_in' => 0,
                'pending' => 0,
                'absent' => 0
            ];

            foreach ($bookings as $b) {
                $status = $b['checkin_status'] ?? 'pending';
                if ($status === 'checked_in') {
                    $stats['checked_in']++;
                } elseif ($status === 'absent') {
                    $stats['absent']++;
                } else {
                    $stats['pending']++;
                }
            }

            view('main', [
                'title' => 'Check-In & Điểm Danh',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/checkin',
                'tour' => $tour,
                'bookings' => $bookings,
                'stats' => $stats
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_dashboard');
        }
    }

    /**
     * Cập nhật trạng thái check-in
     */
    public function updateCheckin()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Phương thức không được hỗ trợ');
            }

            $bookingId = $_POST['booking_id'] ?? null;
            $status = $_POST['status'] ?? null; // checked_in, absent, pending

            if (!$bookingId || !in_array($status, ['checked_in', 'absent', 'pending'])) {
                throw new Exception('Dữ liệu không hợp lệ');
            }

            // Cập nhật trạng thái
            $checkinTime = ($status === 'checked_in') ? date('Y-m-d H:i:s') : null;
            $sql = "UPDATE bookings SET checkin_status = ?, checkin_time = ? WHERE id = ?";
            $this->userModel->query($sql, [$status, $checkinTime, $bookingId]);

            $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        // Quay lại trang check-in
        $tourId = $_POST['tour_id'] ?? $_GET['tour_id'] ?? null;
        redirect('guide_checkin?id=' . $tourId);
    }

    /**
     * Viết nhật ký tour (cho HDV)
     */
    public function write_diary()
    {
        try {
            $tourId = $_GET['id'] ?? null;
            if (!$tourId) {
                throw new Exception('ID tour không hợp lệ');
            }

            $guideId = $_SESSION['guide_id'];

            // Kiểm tra tour có được phân công cho HDV này không
            $checkSql = "SELECT ta.id FROM tour_assignments ta 
                        WHERE ta.tour_id = ? AND ta.guide_id = ?";
            $assigned = $this->guideModel->fetchOne($checkSql, [$tourId, $guideId]);
            
            if (!$assigned) {
                throw new Exception('Bạn không có quyền viết nhật ký tour này');
            }

            // Lấy thông tin tour
            $tour = $this->tourModel->find($tourId);
            if (!$tour) {
                throw new Exception('Không tìm thấy tour');
            }

            // Lấy nhật ký cũ (nếu có)
            $diarySql = "SELECT * FROM tour_diaries WHERE tour_id = ? ORDER BY created_at DESC LIMIT 10";
            $diaries = $this->tourDiaryModel->fetchAll($diarySql, [$tourId]);

            view('main', [
                'title' => 'Viết Nhật Ký Tour',
                'page' => 'guide_profile',
                'content_view' => 'guides/profile/write_diary',
                'tour' => $tour,
                'diaries' => $diaries ?? []
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_assigned_tours');
        }
    }

    /**
     * Lưu nhật ký tour (cho HDV)
     */
    public function save_diary()
    {
        try {
            $tourId = (int)($_POST['tour_id'] ?? 0);
            if ($tourId <= 0) {
                throw new Exception('ID tour không hợp lệ');
            }

            $guideId = $_SESSION['guide_id'];

            // Kiểm tra tour có được phân công cho HDV này không
            $checkSql = "SELECT ta.id FROM tour_assignments ta 
                        WHERE ta.tour_id = ? AND ta.guide_id = ?";
            $assigned = $this->guideModel->fetchOne($checkSql, [$tourId, $guideId]);
            
            if (!$assigned) {
                throw new Exception('Bạn không có quyền viết nhật ký tour này');
            }

            $userId = $_SESSION['user']['id'];
            $title = trim($_POST['title'] ?? 'Nhật ký tour');
            $content = trim($_POST['content'] ?? '');
            $diaryType = $_POST['diary_type'] ?? 'daily';

            if (empty($content)) {
                throw new Exception('Vui lòng nhập nội dung nhật ký');
            }

            // Lưu nhật ký
            $sql = "INSERT INTO tour_diaries (tour_id, diary_type, title, content, created_by, created_at) 
                   VALUES (?, ?, ?, ?, ?, NOW())";
            $this->tourDiaryModel->query($sql, [$tourId, $diaryType, $title, $content, $userId]);

            $_SESSION['success'] = 'Nhật ký tour đã được lưu!';
            redirect('guide_write_diary?id=' . $tourId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('guide_write_diary?id=' . ($_POST['tour_id'] ?? ''));
        }
    }
}