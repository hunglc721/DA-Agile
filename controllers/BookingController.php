<?php
class BookingController
{
    private $bookingModel;
    private $tourModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->tourModel = new Tour();
    }

    // 1. READ: Danh sách booking (có bộ lọc)
    // 1. READ: Danh sách booking (có bộ lọc)
    public function index()
    {
        try {
            $status = $_GET['status'] ?? null;
            
            // --- SỬA Ở ĐÂY: Dùng LEFT JOIN để không bị mất đơn hàng ---
            $sql = "SELECT b.*, t.name as tour_name, u.full_name as user_name 
                    FROM bookings b 
                    LEFT JOIN tours t ON b.tour_id = t.id 
                    LEFT JOIN users u ON b.user_id = u.id 
                    WHERE 1=1";
            
            $params = [];
            if ($status) {
                $sql .= " AND b.status = ?";
                $params[] = $status;
            }
            
            // --- SỬA Ở ĐÂY: Sắp xếp theo ID giảm dần để thấy đơn mới nhất ---
            $sql .= " ORDER BY b.id DESC"; 

            $bookings = $this->bookingModel->fetchAll($sql, $params);

            view('main', [
                'title' => 'Quản Lý Đặt Tour',
                'page' => 'bookings',
                'content_view' => 'bookings/index',
                'bookings' => $bookings
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/');
        }
    }

    // 2. SHOW: Chi tiết booking
   // File: controllers/BookingController.php

public function show()
{
    try {
        $id = $_GET['id'] ?? null;
        
        // 1. Lấy thông tin booking + tour + user (Bỏ t.image_url để tránh lỗi)
        $sql = "SELECT b.*, t.name as tour_name, t.tour_code, u.full_name, u.email, u.phone 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                LEFT JOIN users u ON b.user_id = u.id 
                WHERE b.id = ?";
        
        $booking = $this->bookingModel->fetchOne($sql, [$id]);

        if (!$booking) {
            throw new Exception('Đơn hàng không tồn tại');
        }

        view('main', [
            'title' => 'Chi Tiết Đơn Hàng #' . $booking['id'],
            'page' => 'bookings',
            'content_view' => 'bookings/show',
            'booking' => $booking
        ]);
    } catch (Exception $e) {
        $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        // Sửa đường dẫn redirect về đúng action danh sách
        redirect('index.php?action=bookings');
    }
}

    // 3. UPDATE: Cập nhật trạng thái (Duyệt/Hủy/Thanh toán)
   // 3. UPDATE: Cập nhật trạng thái (Duyệt/Hủy/Thanh toán)
    public function updateStatus()
    {
        try {
            $id = $_POST['id'];
            $status = $_POST['status']; 

            // 1. Tìm booking hiện tại
            $currentBooking = $this->bookingModel->find($id);
            if (!$currentBooking) {
                throw new Exception('Đơn hàng không tồn tại');
            }
            
            // 2. Xử lý hoàn slot nếu chọn trạng thái Hủy (cancelled)
            if ($status === 'cancelled' && $currentBooking['status'] !== 'cancelled') {
                $sqlAddSlot = "UPDATE tours SET available_slots = available_slots + ? WHERE id = ?";
                $this->tourModel->query($sqlAddSlot, [$currentBooking['number_of_people'], $currentBooking['tour_id']]);
            }

            // 3. Cập nhật trạng thái mới
            $sql = "UPDATE bookings SET status = ? WHERE id = ?";
            $this->bookingModel->query($sql, [$status, $id]);

            $_SESSION['success'] = 'Cập nhật trạng thái đơn hàng thành công';
            
            // --- SỬA Ở ĐÂY: Chuyển hướng về danh sách (thay vì trang chi tiết sai link) ---
            redirect('index.php?action=bookings'); 
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            // Sửa đường dẫn lỗi về danh sách
            redirect('index.php?action=bookings'); 
        }
    }
    // 4. CREATE: Tạo booking thủ công (Admin tạo hộ khách)
    // Trong BookingController.php -> hàm store()

public function store() {
        try {
            $data = $_POST;
            $bookingType = $data['booking_type'] ?? 'retail';

            // 1. Lấy thông tin Tour để tính toán
            $tour = $this->tourModel->find($data['tour_id'] ?? 0);
            if (!$tour) {
                throw new Exception('Tour không tồn tại!');
            }

            // 2. Xác thực số lượng người
            $guests = (int)($data['number_of_people'] ?? 1);
            if ($bookingType === 'group' && $guests < 10) {
                throw new Exception('Đoàn phải tối thiểu 10 người!');
            }

            if ($guests > $tour['available_slots']) {
                throw new Exception('Không đủ chỗ trống (Còn lại: ' . $tour['available_slots'] . ' chỗ)');
            }

            // 3. Xử lý giá tiền
            if (!empty($data['total_price'])) {
                $data['total_price'] = (int)$data['total_price'];
            } else {
                $basePrice = $tour['price'] * $guests;
                if ($bookingType === 'group' && !empty($data['group_discount'])) {
                    $discount = (float)$data['group_discount'];
                    $data['total_price'] = $basePrice * (1 - $discount / 100);
                } else {
                    $data['total_price'] = $basePrice;
                }
            }

            // 4. Xác thực user_id
            if (empty($data['user_id'])) {
                if (isset($_SESSION['user'])) {
                    $data['user_id'] = $_SESSION['user']['id'];
                } else {
                    throw new Exception('Vui lòng chọn khách hàng!');
                }
            }

            // 5. Lưu thông tin bổ sung
            $data['booking_type'] = $bookingType;
            $data['customer_list'] = $data['customer_list'] ?? null;
            $data['special_requests'] = $data['special_requests'] ?? '';
            
            // Với đoàn, thêm tên đoàn vào special_requests nếu chưa có
            if ($bookingType === 'group' && !empty($data['group_name'])) {
                $data['special_requests'] = "Tên đoàn: " . $data['group_name'] . "\n" . $data['special_requests'];
            }

            // 6. Tạo booking
            $result = $this->bookingModel->createBooking($data);

            if ($result['status']) {
                $_SESSION['success'] = 'Tạo đơn đặt tour thành công! (ID: #' . $result['booking_id'] . ')';
                redirect('index.php?action=bookings_show&id=' . $result['booking_id']); 
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi tạo đơn đặt: ' . $e->getMessage();
            redirect('index.php?action=bookings_create');
        }
    }
    // ... code cũ ...

    // THÊM HÀM NÀY VÀO TRƯỚC HÀM store()
    public function create()
    {
        try {
            // Lấy danh sách khách hàng (User thường)
            $userModel = new User();
            $users = $userModel->fetchAll("SELECT * FROM users WHERE is_admin = 0 OR is_admin IS NULL ORDER BY full_name ASC");
            
            if (empty($users)) {
                $users = [];
            }

            // Lấy danh sách tour đang active
            $tours = $this->tourModel->fetchAll("SELECT * FROM tours WHERE status = 'active' ORDER BY name ASC");

            view('main', [
                'title' => 'Bán Tour & Đặt Chỗ',
                'page' => 'bookings',
                'content_view' => 'bookings/create',
                'users' => $users,
                'tours' => $tours
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=bookings');
        }
    }

    // Trong class BookingController

public function delete() {
    // Lấy ID từ URL (ví dụ: ?action=bookings/delete&id=5)
    $id = $_GET['id'] ?? null;

    if ($id) {
        // Kiểm tra xem booking có tồn tại không trước khi xóa (tùy chọn)
        $booking = $this->bookingModel->find($id);
        
        if ($booking) {
            // Gọi hàm delete trong Model
            $this->bookingModel->delete($id);
            $_SESSION['success'] = "Đã xóa đơn đặt tour thành công!";
        } else {
            $_SESSION['error'] = "Không tìm thấy đơn đặt tour!";
        }
    } else {
        $_SESSION['error'] = "Thiếu thông tin ID để xóa!";
    }

    // Quay lại trang danh sách booking
    redirect('?action=bookings'); // Hoặc đường dẫn action danh sách của bạn
}
    

    // ... hàm store() và các hàm khác giữ nguyên ...
}
?>