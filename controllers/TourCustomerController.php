<?php
// controllers/TourCustomerController.php

class TourCustomerController 
{
    private $bookingModel;
    private $tourModel;
    private $userModel;

    public function __construct() {
        $this->bookingModel = new Booking();
        $this->tourModel = new Tour();
        $this->userModel = new User();
    }

    public function index() {
        try {
            // 1. Lấy danh sách Tour để hiển thị Select box (với info chi tiết)
            $tours = $this->tourModel->findAll(); 
            
            // 2. Lấy tour_id từ URL hoặc Session
            $tourId = $_GET['tour_id'] ?? ($_SESSION['selected_tour_id'] ?? null);
            
            $customers = [];
            $selectedTour = null;
            $departures = [];
            $guides = [];
            $services = [];
            $statistics = [];

            if ($tourId) {
                // Lưu tour_id vào session để giữ trạng thái khi load lại trang
                $_SESSION['selected_tour_id'] = $tourId;
                
                // Lấy thông tin chi tiết Tour
                $selectedTour = $this->tourModel->find($tourId);
                
                // Gọi hàm từ Model thay vì viết SQL ở Controller
                if ($selectedTour) {
                    // Danh sách khách hàng
                    $sql = "SELECT b.id as booking_id, b.number_of_people, 
                                   b.special_requests, b.status as booking_status, b.total_price, 
                                   b.created_at, b.checkin_status, u.full_name, u.phone, u.email, u.id as user_id 
                            FROM bookings b JOIN users u ON b.user_id = u.id 
                            WHERE b.tour_id = ? ORDER BY b.created_at DESC";
                    $customers = $this->bookingModel->fetchAll($sql, [$tourId]);

                    // Lấy danh sách khởi hành cho tour này
                    $departuresSql = "SELECT td.* 
                                    FROM tour_departures td 
                                    WHERE td.tour_id = ? 
                                    ORDER BY td.departure_date DESC";
                    $departures = $this->bookingModel->fetchAll($departuresSql, [$tourId]);

                    // Lấy danh sách hướng dẫn viên được gán
                    // Some installations use `tour_assignments` without a `departure_id` column.
                    // Join using tour_id and assignment_date matching departure_date as a safe fallback.
                    $guidesSql = "SELECT DISTINCT g.*, td.departure_date, ta.assignment_date as assigned_date
                                FROM guides g
                                JOIN tour_assignments ta ON g.id = ta.guide_id
                                JOIN tour_departures td ON ta.tour_id = td.tour_id AND (ta.assignment_date = td.departure_date OR ta.assignment_date IS NULL)
                                WHERE td.tour_id = ?
                                ORDER BY g.id ASC";
                    try {
                        $guides = $this->bookingModel->fetchAll($guidesSql, [$tourId]);
                    } catch (Exception $e) {
                        // Fallback 1: order by user full_name if present
                        try {
                            $fallbackSql = "SELECT DISTINCT g.*, u.full_name
                                            FROM guides g
                                            JOIN tour_assignments ta ON g.id = ta.guide_id
                                            JOIN users u ON g.user_id = u.id
                                            WHERE ta.tour_id = ?
                                            ORDER BY u.full_name ASC";
                            $guides = $this->bookingModel->fetchAll($fallbackSql, [$tourId]);
                        } catch (Exception $e2) {
                            // Final fallback: simple list by guide id
                            $guides = $this->bookingModel->fetchAll("SELECT DISTINCT g.* FROM guides g JOIN tour_assignments ta ON g.id = ta.guide_id WHERE ta.tour_id = ? ORDER BY g.id ASC", [$tourId]);
                        }
                    }

                    // Lấy danh sách dịch vụ/chi phí (thử cả hai tên bảng)
                    $services = [];
                    try {
                        $servicesSql = "SELECT * FROM tour_costs 
                                        WHERE tour_id = ? 
                                        ORDER BY created_at DESC
                                        LIMIT 10";
                        $services = $this->bookingModel->fetchAll($servicesSql, [$tourId]);
                    } catch (Exception $e) {
                        // tour_costs không tồn tại, bỏ qua
                    }
                    
                    // Nếu tour_costs trống, thử booking_services
                    if (empty($services)) {
                        try {
                            $servicesSql = "SELECT * FROM booking_services 
                                            WHERE tour_id = ? 
                                            ORDER BY created_at DESC
                                            LIMIT 10";
                            $services = $this->bookingModel->fetchAll($servicesSql, [$tourId]);
                        } catch (Exception $e) {
                            // booking_services không tồn tại hoặc không có dữ liệu, bỏ qua
                            $services = [];
                        }
                    }

                    // Thống kê
                    $statistics = [
                        'total_customers' => count($customers),
                        'total_people' => array_reduce($customers, function($carry, $item) {
                            return $carry + (int)$item['number_of_people'];
                        }, 0),
                        'confirmed' => count(array_filter($customers, function($c) { return $c['booking_status'] === 'confirmed'; })),
                        'pending' => count(array_filter($customers, function($c) { return $c['booking_status'] === 'pending'; })),
                        'checked_in' => count(array_filter($customers, function($c) { return $c['checkin_status'] === 'checked_in'; })),
                        'total_revenue' => array_reduce($customers, function($carry, $item) {
                            return $carry + (float)$item['total_price'];
                        }, 0)
                    ];
                }
            }

            // 3. Trả về View
            view('main', [
                'title' => 'Danh Sách Khách Hàng',
                'page' => 'tour_customers',
                'content_view' => 'admin/tour_customers',
                'tours' => $tours,
                'customers' => $customers,
                'selectedTour' => $selectedTour,
                'tourId' => $tourId,
                'departures' => $departures,
                'guides' => $guides,
                'services' => $services,
                'statistics' => $statistics
            ]);

        } catch (Exception $e) {
            // Hiển thị lỗi ra màn hình để debug nếu vẫn bị trắng trang
            echo "Có lỗi xảy ra: " . $e->getMessage();
            die();
        }
    }

    // Các hàm khác giữ nguyên
    public function updateSpecialRequests() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingId = $_POST['booking_id'];
            $requests = $_POST['special_requests'];
            $this->bookingModel->update($bookingId, ['special_requests' => $requests]);
            echo json_encode(['success' => true]);
        }
    }
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tourId = $_POST['tour_id'];
                $email = trim($_POST['email']);
                $phone = trim($_POST['phone']);
                $fullName = trim($_POST['full_name']);
                $numPeople = intval($_POST['number_of_people']);
                $requests = $_POST['special_requests'];
                $customPrice = $_POST['custom_price'];

                $user = $this->userModel->findByEmail($email);
                
                if ($user) {
                    $userId = $user['id'];
                } else {
                    // 2. Nếu chưa có, tạo User mới
                    // Tạo username từ email (lấy phần trước @ + số ngẫu nhiên để tránh trùng)
                    $username = strstr($email, '@', true) . rand(100, 999);
                    
                    $userId = $this->userModel->create([
                        'username' => $username,
                        'email' => $email,
                        'password' => '123456', // Mật khẩu mặc định
                        'full_name' => $fullName,
                        'phone' => $phone,
                        'is_admin' => 0
                    ]);
                }

                // 3. Chuẩn bị dữ liệu Booking
                $bookingData = [
               'tour_id' => $tourId,
               'user_id' => $userId,
               'number_of_people' => $numPeople,
               'booking_type' => 'retail', // <--- Sửa từ 'admin_create' thành 'retail'
               'special_requests' => $requests
                ];

                // Nếu admin nhập giá riêng thì tính tổng tiền theo giá đó, ngược lại model sẽ tự tính theo giá tour
                if (!empty($customPrice)) {
                    $bookingData['total_price'] = $customPrice * $numPeople;
                }

                // 4. Gọi Model tạo Booking
                $result = $this->bookingModel->createBooking($bookingData);

                if ($result['status']) {
                    // Admin thêm thì set luôn trạng thái là Đã Xác Nhận (confirmed)
                    $this->bookingModel->update($result['booking_id'], ['status' => 'confirmed']);
                    
                    $_SESSION['success'] = "Đã thêm khách hàng thành công!";
                } else {
                    $_SESSION['error'] = "Lỗi: " . $result['message'];
                }

            } catch (Exception $e) {
                $_SESSION['error'] = "Đã xảy ra lỗi: " . $e->getMessage();
            }

            // Redirect trở lại trang danh sách khách hàng của tour đó
             redirect("?action=tour_customers&tour_id=" . $tourId);        }
    }
}
?>
