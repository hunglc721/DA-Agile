<?php
// routes/index.php

// 1. Load các file cấu hình và helper (nếu chưa load ở index gốc)
// require_once ROOT_PATH . 'configs/env.php';
// require_once ROOT_PATH . 'configs/helper.php';

// 2. Load Models (Load Base trước, các model khác tự động load nếu dùng spl_autoload hoặc require thủ công)
require_once ROOT_PATH . 'models/BaseModel.php';
require_once ROOT_PATH . 'models/User.php';
require_once ROOT_PATH . 'models/Tour.php';
require_once ROOT_PATH . 'models/TourDeparture.php';
require_once ROOT_PATH . 'models/Service.php';
require_once ROOT_PATH . 'models/TourCategory.php'; // Model mới
require_once ROOT_PATH . 'models/TourType.php';    // Model loại tour (NEW)
require_once ROOT_PATH . 'models/Guide.php';        // Model mới
require_once ROOT_PATH . 'models/Booking.php';      // Model mới
require_once ROOT_PATH . 'models/TourDiary.php';
require_once ROOT_PATH . 'models/Report.php';      // Report model

// 3. Load Controllers
require_once ROOT_PATH . 'controllers/BaseController.php';
require_once ROOT_PATH . 'controllers/HomeController.php';
require_once ROOT_PATH . 'controllers/AdminController.php';
require_once ROOT_PATH . 'controllers/TourController.php';
require_once ROOT_PATH . 'controllers/CategoryController.php'; // Controller mới
require_once ROOT_PATH . 'controllers/TourTypeController.php'; // Controller loại tour (NEW)
require_once ROOT_PATH . 'controllers/GuideController.php';    // Controller mới
require_once ROOT_PATH . 'controllers/BookingController.php';  // Controller mới
require_once ROOT_PATH . 'controllers/CheckinController.php';  // Check-in & Attendance
require_once ROOT_PATH . 'controllers/TourDiaryController.php';
require_once ROOT_PATH . 'controllers/TourCustomerController.php';
require_once ROOT_PATH . 'controllers/UserController.php';
require_once ROOT_PATH . 'controllers/CheckinController.php';
require_once ROOT_PATH . 'controllers/ReportController.php';
require_once ROOT_PATH . 'controllers/TourScheduleController.php';
require_once ROOT_PATH . 'controllers/DepartureController.php';
require_once ROOT_PATH . 'controllers/ServiceController.php';
require_once ROOT_PATH . 'controllers/AuthController.php';
require_once ROOT_PATH . 'controllers/GuideProfileController.php';

// 4. Lấy action từ URL (mặc định là trang chủ)
$action = $_GET['action'] ?? '/';

// Helper function: Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

// Helper function: Check if user is admin
function isAdmin() {
    return isset($_SESSION['user']) && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'];
}

// Helper function: Require auth
function requireAuth() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
        redirect('login');
        exit;
    }
}

// Helper function: Require admin
function requireAdmin() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
        redirect('login');
        exit;
    }
    if (!isAdmin()) {
        die('<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này</p>');
    }
}

// 5. Điều hướng (Routing)
match ($action) {
    // --- ĐĂNG NHẬP / ĐĂNG XUẤT ---
    'login'         => (new AuthController())->login(),
    'login_submit'  => (new AuthController())->handleLogin(),
    'logout'        => (new AuthController())->logout(),
    
    // --- TRANG CHỦ ---
    '/'             => (new HomeController())->index(),
    
    // --- ADMIN DASHBOARD ---
    'dashboard'     => (new AdminController())->dashboard(),
    'communication' => (new AdminController())->communication(),
    'admin_send_message' => (new AdminController())->admin_send_message(),
    
    // --- QUẢN LÝ TOUR (Đã có) ---
    'tours'         => (new TourController())->index(),
    'tours_domestic'      => (new TourController())->filterByCategory(1), // Tour Trong Nước
    'tours_international' => (new TourController())->filterByCategory(2), // Tour Quốc Tế
    'tours_custom'        => (new TourController())->filterByCategory(3), // Tour Theo Yêu Cầu
    'tours_create'  => (new TourController())->create(),
    'tours_store'   => (new TourController())->store(),
    'tours_show'    => (new TourController())->show(),
    'tours_edit'    => (new TourController())->edit(),
    'tours_update'  => (new TourController())->update(),
    'tours_delete'  => (new TourController())->delete(),

    'tour_customers'                        => (new TourCustomerController())->index(),
// Thêm vào phần QUẢN LÝ TOUR
'tours_trash'        => (new TourController())->trash(),
'tours_restore'      => (new TourController())->restore(),
'tours_force_delete' => (new TourController())->forceDelete(),
    // --- QUẢN LÝ DANH MỤC (Mới thêm) ---
    'categories'        => (new CategoryController())->index(),
    'categories_create' => (new CategoryController())->create(),
    'categories_store'  => (new CategoryController())->store(),
    'categories_edit'   => (new CategoryController())->edit(),
    'categories_update' => (new CategoryController())->update(),
    'categories_delete' => (new CategoryController())->delete(),

    // --- QUẢN LÝ HƯỚNG DẪN VIÊN (Mới thêm) ---
    'guides'        => (new GuideController())->index(),
    'guides_create' => (new GuideController())->create(),
    'guides_store'  => (new GuideController())->store(),
    'guides_edit'   => (new GuideController())->edit(),
    'guides_update' => (new GuideController())->update(),
    'guides_delete' => (new GuideController())->delete(),

    // --- TRANG CÁ NHÂN HƯỚNG DẪN VIÊN (HDV DASHBOARD) ---
    'guide_dashboard'              => (new GuideProfileController())->dashboard(),
    'guide_customer_feedback'      => (new GuideProfileController())->customerFeedback(),
    'guide_notifications'          => (new GuideProfileController())->notifications(),
    'guide_assigned_tours'         => (new GuideProfileController())->assignedTours(),
    'guide_tour_detail'            => (new GuideProfileController())->tour_detail(),
    'guide_tour_customers'         => (new GuideProfileController())->tourCustomers(),
    'guide_tour_customers_detail'  => (new GuideProfileController())->tour_customers_detail(),
    'guide_confirm_tour'           => (new GuideProfileController())->confirmTour(),
    'guide_confirm_tour_store'     => (new GuideProfileController())->confirmTourStore(),
    'guide_itinerary'              => (new GuideProfileController())->itinerary(),
    'guide_update_status'          => (new GuideProfileController())->updateStatus(),
    'guide_update_status_store'    => (new GuideProfileController())->updateStatusStore(),
    'guide_incident_report'        => (new GuideProfileController())->incidentReport(),
    'guide_incident_report_store'  => (new GuideProfileController())->storeIncidentReport(),
    'guide_complete_tour'          => (new GuideProfileController())->completeTour(),
    'guide_complete_tour_store'    => (new GuideProfileController())->storeCompleteTour(),
    'guide_final_report'           => (new GuideProfileController())->finalReport(),
    'guide_final_report_store'     => (new GuideProfileController())->storeFinalReport(),
    'guide_communication'          => (new GuideProfileController())->communication(),
    'guide_send_message'           => (new GuideProfileController())->sendMessage(),
    'guide_checkin'                => (new GuideProfileController())->checkin(),
    'guide_update_checkin'         => (new GuideProfileController())->updateCheckin(),
    'guide_write_diary'            => (new GuideProfileController())->write_diary(),
    'guide_save_diary'             => (new GuideProfileController())->save_diary(),

    // --- QUẢN LÝ DANH MỤC TOUR (NEW) ---
    'tour_categories'        => (new CategoryController())->index(),
    'tour_categories_create' => (new CategoryController())->create(),
    'tour_categories_store'  => (new CategoryController())->store(),
    'tour_categories_edit'   => (new CategoryController())->edit(),
    'tour_categories_update' => (new CategoryController())->update(),
    'tour_categories_delete' => (new CategoryController())->delete(),

    'tour_types'        => (new TourTypeController())->index(),
    'tour_types_create' => (new TourTypeController())->create(),
    'tour_types_store'  => (new TourTypeController())->store(),
    'tour_types_edit'   => (new TourTypeController())->edit(),
    'tour_types_update' => (new TourTypeController())->update(),
    'tour_types_delete' => (new TourTypeController())->delete(),
    'tour_types_toggle' => (new TourTypeController())->toggleActive(),

  
// ...

    // --- QUẢN LÝ BOOKING / ĐẶT TOUR (Mới thêm) ---
    'bookings'              => (new BookingController())->index(),
    'bookings_show'         => (new BookingController())->show(),
    'bookings_create'       => (new BookingController())->create(), // Tạo thủ công cho khách
    'bookings_store'        => (new BookingController())->store(),
    'bookings_update_status'=> (new BookingController())->updateStatus(), // Duyệt/Hủy đơn
    'bookings_delete'       => (new BookingController())->delete(),
    // --- QUẢN LÝ NHẬT KÝ TOUR ---
    'tour_diary'        => (new TourDiaryController())->index(),
    'tour_diary_store'  => (new TourDiaryController())->store(),
    'tour_diary_update' => (new TourDiaryController())->update(),
    'tour_diary_delete' => (new TourDiaryController())->delete(),
    'incident_reports'  => (new TourDiaryController())->incidents(),

    // --- KHỞI HÀNH & PHÂN BỔ NHÂN SỰ ---
    'departures'                  => (new DepartureController())->index(),
    'departures_create'           => (new DepartureController())->create(),
    'departures_store'            => (new DepartureController())->store(),
    'departures_edit'             => (new DepartureController())->edit(),
    'departures_update'           => (new DepartureController())->update(),
    'departures_delete'           => (new DepartureController())->delete(),
    'departures_assign'           => (new DepartureController())->assign(),
    'departures_store_assignment' => (new DepartureController())->storeAssignment(),
    'departures_unassign'         => (new DepartureController())->unassign(),
    'assignments'                 => (new DepartureController())->assignments(),
    'assignments_create'          => (new DepartureController())->assignments_create(),
    'assignments_store'           => (new DepartureController())->assignments_store(),

    // --- DỊCH VỤ TOUR ---
    'services'         => (new ServiceController())->index(),
    'services_store'   => (new ServiceController())->store(),
    'services_delete'  => (new ServiceController())->delete(),
    'services_edit'    => (new ServiceController())->edit(),
    'services_update'  => (new ServiceController())->update(),

    // --- QUẢN LÝ KHÁCH HÀNG TOUR ---
    'tour_customers'                      => (new TourCustomerController())->index(),
    'tour_customers_add'                  => (new TourCustomerController())->add(),
    'tour_customers_update_special_requests' => (new TourCustomerController())->updateSpecialRequests(),

    
    // --- QUẢN LÝ KHÁCH HÀNG & USER ---
    'users'         => (new UserController())->index(),
    'users_create'  => (new UserController())->create(),
    'users_store'   => (new UserController())->store(),
    'users_show'    => (new UserController())->show(),
    'users_edit'    => (new UserController())->edit(),
    'users_update'  => (new UserController())->update(),
    'users_delete'  => (new UserController())->delete(),
    'tour_customers'=> (new TourCustomerController())->index(),
    
    // Thêm route cho Ajax update (để checkbox hoạt động)
    'checkin_update'     => (new CheckinController())->updateStatus(),
    'checkin'            => (new CheckinController())->index(), // Thêm action này
    'checkin_attendance' => (new CheckinController())->index(),


    

    // --- CÁC CHỨC NĂNG KHÁC ---    
    'checkin_batch'      => (new CheckinController())->batchCheckin(),
    'tour_schedule'      => (new TourScheduleController())->index(),
    
    // --- BÁAO CÁO ---
    'tour_financial_report'       => (new ReportController())->tourFinancialReport(),
    'tour_financial_report_export' => (new ReportController())->exportCSV(),
    
    'api_schedule_events' => (new TourScheduleController())->getEvents(),
    default         => die('<h1>Lỗi 404: Trang không tồn tại!</h1><a href="index.php">Quay lại trang chủ</a>'),
};

?>
