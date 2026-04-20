<?php
class ClientController
{
    /**
     * Hiển thị trang chủ client
     */
    public function index()
    {
        view('client/index', [
            'title' => 'Trang Chủ - Travel Manager',
            'page' => 'client/index'
        ]);
    }

    /**
     * Hiển thị trang About
     */
    public function about()
    {
        view('client/about', [
            'title' => 'Về Chúng Tôi - Travel Manager',
            'page' => 'client/about'
        ]);
    }

    /**
     * Hiển thị trang Tour
     */
    public function tour()
    {
        view('client/tour', [
            'title' => 'Tour - Travel Manager',
            'page' => 'client/tour'
        ]);
    }

    /**
     * Hiển thị trang Hotel
     */
    public function hotel()
    {
        view('client/hotel', [
            'title' => 'Khách Sạn - Travel Manager',
            'page' => 'client/hotel'
        ]);
    }

    /**
     * Hiển thị trang Contact
     */
    public function contact()
    {
        view('client/contact', [
            'title' => 'Liên Hệ - Travel Manager',
            'page' => 'client/contact'
        ]);
    }

    /**
     * Xử lý submit form liên hệ
     */
    public function submitContact()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client_contact');
        }

        $errors = [];

        // Validate
        $name = clean($_POST['name'] ?? '');
        $email = clean($_POST['email'] ?? '');
        $phone = clean($_POST['phone'] ?? '');
        $subject = clean($_POST['subject'] ?? '');
        $message = clean($_POST['message'] ?? '');

        if (empty($name) || strlen($name) < 2) {
            $errors['name'] = 'Vui lòng nhập họ tên hợp lệ';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }

        if (empty($phone) || !preg_match('/^[0-9]{10,11}$/', $phone)) {
            $errors['phone'] = 'Số điện thoại phải có 10-11 chữ số';
        }

        if (empty($subject) || strlen($subject) < 3) {
            $errors['subject'] = 'Chủ đề phải có ít nhất 3 ký tự';
        }

        if (empty($message) || strlen($message) < 10) {
            $errors['message'] = 'Nội dung phải có ít nhất 10 ký tự';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('client_contact');
        }

        try {
            // Lưu liên hệ vào database (nếu có table contacts)
            // Hoặc gửi email (nếu cần)
            // Tạm thời chỉ hiển thị success message

            $_SESSION['success'] = 'Cảm ơn bạn! Chúng tôi sẽ liên hệ lại trong 24 giờ.';
        } catch (Exception $e) {
            $_SESSION['errors'] = ['general' => 'Lỗi: ' . $e->getMessage()];
        }

        redirect('client_contact');
    }

    /**
     * Hiển thị trang Hotel Single
     */
    public function hotelSingle()
    {
        view('client/hotel-single', [
            'title' => 'Chi Tiết Khách Sạn - Travel Manager',
            'page' => 'client/hotel-single'
        ]);
    }

    /**
     * Hiển thị trang Tour Single
     */
    public function tourSingle()
    {
        $tourId = (int)($_GET['id'] ?? 0);

        if (!$tourId) {
            $_SESSION['error'] = 'Tour không được chỉ định';
            redirect('client_tour');
        }

        $tourModel = new Tour();
        $tour = $tourModel->findWithDetails($tourId);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại';
            redirect('client_tour');
        }

        // Lấy lịch trình chi tiết
        $itinerary = $tourModel->getItinerary($tourId);

        // Lấy các tour liên quan (cùng danh mục)
        $relatedTours = $tourModel->findAll([
            'category_id' => $tour['category_id'],
            'status' => 'active'
        ]);
        // Loại bỏ tour hiện tại khỏi danh sách liên quan
        $relatedTours = array_filter($relatedTours, function($t) use ($tourId) {
            return $t['id'] != $tourId;
        });
        $relatedTours = array_slice($relatedTours, 0, 3);

        // Lấy khởi hành sắp tới
        $departureSql = "SELECT * FROM tour_departures
                        WHERE tour_id = ? AND departure_date >= CURDATE()
                        ORDER BY departure_date ASC
                        LIMIT 5";
        $departureModel = new TourDeparture();
        $departures = $departureModel->fetchAll($departureSql, [$tourId]);

        view('client/tour-single', [
            'title' => $tour['name'] . ' - Du Lịch Thông Minh',
            'page' => 'client/tour-single',
            'tour' => $tour,
            'itinerary' => $itinerary,
            'relatedTours' => $relatedTours,
            'departures' => $departures
        ]);
    }

    /**
     * Hiển thị dashboard khách hàng
     */
    public function dashboard()
    {
        requireClient();

        $user = getCurrentUser();
        $bookingModel = new Booking();
        $tourModel = new Tour();

        // Lấy booking của khách hàng
        $sql = "SELECT b.*, t.name as tour_name, t.price
                FROM bookings b
                JOIN tours t ON b.tour_id = t.id
                WHERE b.user_id = ?
                ORDER BY b.created_at DESC
                LIMIT 10";
        $bookings = $bookingModel->fetchAll($sql, [$user['id']]);

        // Lấy các booking chờ xác nhận
        $sqlPending = "SELECT b.*, t.name as tour_name, t.price
                       FROM bookings b
                       JOIN tours t ON b.tour_id = t.id
                       WHERE b.user_id = ? AND b.status = 'pending'
                       ORDER BY b.created_at DESC
                       LIMIT 5";
        $pendingBookings = $bookingModel->fetchAll($sqlPending, [$user['id']]);

        // Tính toán thống kê
        $sqlStats = "SELECT
                    COUNT(*) as total_bookings,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_count,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(total_price) as total_spent
                    FROM bookings
                    WHERE user_id = ?";
        $stats = $bookingModel->fetchOne($sqlStats, [$user['id']]) ?? [];

        view('client/dashboard', [
            'title' => 'Trang Cá Nhân - Du Lịch Thông Minh',
            'page' => 'client/dashboard',
            'user' => $user,
            'bookings' => $bookings,
            'pendingBookings' => $pendingBookings,
            'stats' => $stats
        ]);
    }

    /**
     * Hiển thị trang hồ sơ cá nhân
     */
    public function profile()
    {
        requireClient();

        $user = getCurrentUser();
        $userModel = new User();
        $userInfo = $userModel->find($user['id']);

        view('client/profile', [
            'title' => 'Hồ Sơ Cá Nhân - Du Lịch Thông Minh',
            'page' => 'client/profile',
            'user' => $userInfo
        ]);
    }

    /**
     * Cập nhật hồ sơ cá nhân
     */
    public function updateProfile()
    {
        requireClient();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client_profile');
        }

        $user = getCurrentUser();
        $userModel = new User();

        $data = [
            'full_name' => clean($_POST['full_name'] ?? ''),
            'phone' => clean($_POST['phone'] ?? ''),
            'email' => clean($_POST['email'] ?? '')
        ];

        // Kiểm tra validation
        $errors = [];
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Vui lòng nhập tên đầy đủ';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }
        if (!empty($data['phone']) && !preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại phải có 10-11 chữ số';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('client_profile');
        }

        // Cập nhật password nếu có
        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 6) {
                $_SESSION['errors'] = ['password' => 'Mật khẩu phải có ít nhất 6 ký tự'];
                redirect('client_profile');
            }
            $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }

        try {
            $userModel->update($user['id'], $data);

            // Cập nhật session
            $_SESSION['user']['full_name'] = $data['full_name'];
            $_SESSION['user']['email'] = $data['email'];
            $_SESSION['user']['phone'] = $data['phone'] ?? '';

            $_SESSION['success'] = 'Cập nhật hồ sơ thành công!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        redirect('client_profile');
    }

    /**
     * Hiển thị form đặt tour
     */
    public function bookingForm()
    {
        requireClient();

        $tourModel = new Tour();
        $tours = $tourModel->fetchAll("SELECT * FROM tours WHERE status = 'active' ORDER BY name ASC");

        view('client/booking', [
            'title' => 'Đặt Tour - Du Lịch Thông Minh',
            'page' => 'client/booking',
            'tours' => $tours
        ]);
    }

    /**
     * Xử lý submit form đặt tour
     */
    public function submitBooking()
    {
        requireClient();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client_booking');
        }

        $user = getCurrentUser();
        $tourModel = new Tour();
        $departureModel = new TourDeparture();
        $bookingModel = new Booking();

        $errors = [];

        // Validate booking type
        $bookingType = clean($_POST['booking_type'] ?? '');
        if (empty($bookingType) || !in_array($bookingType, ['retail', 'group'])) {
            $errors['booking_type'] = 'Vui lòng chọn loại đặt tour';
        }

        // Validate tour
        $tourId = (int)($_POST['tour_id'] ?? 0);
        $tour = $tourModel->find($tourId);
        if (!$tour) {
            $errors['tour_id'] = 'Tour không tồn tại hoặc đã bị khoá';
        }

        // Validate departure
        $departureId = (int)($_POST['departure_id'] ?? 0);
        $departure = null;
        if ($departureId > 0) {
            $departure = $departureModel->find($departureId);
            if (!$departure) {
                $errors['departure_id'] = 'Ngày khởi hành không hợp lệ';
            }
        }

        // Validate number of people
        $numberOfPeople = (int)($_POST['number_of_people'] ?? 0);
        if ($numberOfPeople < 1) {
            $errors['number_of_people'] = 'Số người phải từ 1 trở lên';
        }
        if ($tour && $numberOfPeople > $tour['available_slots']) {
            $errors['number_of_people'] = 'Không đủ chỗ trống (Còn lại: ' . $tour['available_slots'] . ' chỗ)';
        }

        // Validate customer list if provided
        $customerList = null;
        if (!empty($_POST['customer_list'])) {
            $customerListJson = json_decode($_POST['customer_list'], true);
            if (!is_array($customerListJson)) {
                $errors['customer_list'] = 'Danh sách khách không hợp lệ';
            } else {
                foreach ($customerListJson as $idx => $customer) {
                    if (empty($customer['name'])) {
                        $errors['customer_list'] = "Khách hàng " . ($idx + 1) . ": Vui lòng nhập tên";
                        break;
                    }
                    if (empty($customer['email']) || !filter_var($customer['email'], FILTER_VALIDATE_EMAIL)) {
                        $errors['customer_list'] = "Khách hàng " . ($idx + 1) . ": Email không hợp lệ";
                        break;
                    }
                    if (empty($customer['phone']) || !preg_match('/^[0-9]{10,11}$/', $customer['phone'])) {
                        $errors['customer_list'] = "Khách hàng " . ($idx + 1) . ": Số điện thoại phải có 10-11 chữ số";
                        break;
                    }
                    if (empty($customer['age']) || $customer['age'] < 1 || $customer['age'] > 120) {
                        $errors['customer_list'] = "Khách hàng " . ($idx + 1) . ": Tuổi phải từ 1 đến 120";
                        break;
                    }
                }
                if (empty($errors)) {
                    $customerList = $_POST['customer_list'];
                }
            }
        }

        // If validation fails, redirect back
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST; // Keep form data
            redirect('client_booking');
        }

        // Prepare booking data
        $data = [
            'tour_id' => $tourId,
            'departure_id' => $departureId > 0 ? $departureId : null,
            'user_id' => $user['id'],
            'number_of_people' => $numberOfPeople,
            'total_price' => $tour['price'] * $numberOfPeople,
            'booking_type' => $bookingType,
            'customer_list' => $customerList,
            'special_requests' => clean($_POST['special_requests'] ?? ''),
            'status' => 'pending'
        ];

        try {
            $result = $bookingModel->createBooking($data);

            if ($result['status']) {
                $_SESSION['success'] = 'Đặt tour thành công! Mã đặt chỗ: #' . $result['booking_id'];
                unset($_SESSION['form_data']); // Clear form data on success
                redirect('client_booking_confirmation?id=' . $result['booking_id']);
            } else {
                $_SESSION['error'] = $result['message'] ?? 'Lỗi khi tạo đơn đặt tour';
                $_SESSION['form_data'] = $_POST;
                redirect('client_booking');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            $_SESSION['form_data'] = $_POST;
            redirect('client_booking');
        }
    }

    /**
     * Hiển thị trang xác nhận đặt tour
     */
    public function bookingConfirmation()
    {
        requireClient();

        $bookingId = (int)($_GET['id'] ?? 0);
        $bookingModel = new Booking();
        $tourModel = new Tour();

        $sql = "SELECT b.*, t.name as tour_name, t.price, d.departure_date
                FROM bookings b
                JOIN tours t ON b.tour_id = t.id
                LEFT JOIN tour_departures d ON b.departure_id = d.id
                WHERE b.id = ? AND b.user_id = ?";

        $booking = $bookingModel->fetchOne($sql, [$bookingId, getCurrentUser()['id']]);

        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt tour';
            redirect('client_dashboard');
        }

        // Parse customer list if exists
        $customerList = [];
        if ($booking['customer_list']) {
            $customerList = json_decode($booking['customer_list'], true) ?? [];
        }

        view('client/bookingConfirmation', [
            'title' => 'Xác Nhận Đặt Tour - Du Lịch Thông Minh',
            'page' => 'client/bookingConfirmation',
            'booking' => $booking,
            'customerList' => $customerList
        ]);
    }

    /**
     * Xác nhận đơn đặt tour (với thanh toán)
     */
    public function confirmBooking()
    {
        requireClient();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client_dashboard');
        }

        $user = getCurrentUser();
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $paymentMethod = clean($_POST['payment_method'] ?? 'card');
        $bookingModel = new Booking();

        // Lấy booking và kiểm tra quyền
        $sql = "SELECT * FROM bookings WHERE id = ? AND user_id = ?";
        $booking = $bookingModel->fetchOne($sql, [$bookingId, $user['id']]);

        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt tour';
            redirect('client_dashboard');
        }

        // Chỉ có thể xác nhận những booking ở trạng thái pending
        if ($booking['status'] !== 'pending') {
            $_SESSION['error'] = 'Đơn đặt tour này không thể xác nhận lúc này';
            redirect('client_dashboard');
        }

        try {
            // Xử lý thanh toán (tạm thời: giả lập thanh toán thành công)
            $paymentSuccess = $this->processPayment($bookingId, $booking['total_price'], $paymentMethod);

            if ($paymentSuccess) {
                // Cập nhật status thành confirmed
                $updateSql = "UPDATE bookings SET status = 'confirmed' WHERE id = ?";
                $bookingModel->query($updateSql, [$bookingId]);

                $_SESSION['success'] = 'Thanh toán và xác nhận đơn đặt tour thành công! Mã booking: #' . $bookingId;
            } else {
                $_SESSION['error'] = 'Thanh toán thất bại. Vui lòng thử lại!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        redirect('client_dashboard');
    }

    /**
     * Xử lý thanh toán
     */
    private function processPayment($bookingId, $amount, $paymentMethod)
    {
        // Tạo record thanh toán trong database (nếu có)
        // Tạm thời: giả lập thanh toán thành công

        // Validation based on payment method
        if ($paymentMethod === 'card') {
            // Card validation (client-side validation đã bao quát)
            // Server-side: có thể call thêm payment gateway (Stripe, PayPal, etc.)
            return true; // Giả lập thành công

        } elseif ($paymentMethod === 'bank') {
            // Chuyển khoản: cần xác nhận thủ công hoặc tích hợp API ngân hàng
            // Tạm thời: giả lập chờ xác nhận
            return true; // Giả lập thành công

        } elseif ($paymentMethod === 'wallet') {
            // Ví điện tử: có thể tích hợp Momo API, ZaloPay API
            return true; // Giả lập thành công
        }

        return false;
    }
}
?>
