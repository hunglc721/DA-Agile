<?php
// controllers/TourScheduleController.php

class TourScheduleController
{
    private $tourModel;
    private $departureModel;
    private $bookingModel;

    public function __construct() {
        $this->tourModel = new Tour();
        $this->departureModel = new TourDeparture();
        $this->bookingModel = new Booking();
    }

    // Hiển thị giao diện lịch
    public function index() {
        // Có thể truyền thêm dữ liệu khởi tạo nếu cần
        view('main', [
            'title' => 'Lịch Trình Tour & Làm Việc',
            'page' => 'tour_schedule',
            'content_view' => 'admin/tour_schedule'
        ]);
    }

    // API trả về dữ liệu JSON cho FullCalendar
    public function getEvents()
    {
        // 1. Xóa sạch bộ nhớ đệm đầu ra để tránh file index.php chèn header/footer vào JSON
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        try {
            $events = [];
            
            // Lấy các filter từ query string
            $statusFilter = $_GET['status'] ?? '';
            $categoryFilter = $_GET['category'] ?? '';
            $searchFilter = $_GET['search'] ?? '';

            // Lấy lịch khởi hành sử dụng TourDeparture model (tự xử lý schema detection)
            $deps = $this->departureModel->findAll();
            if (!empty($deps)) {
                foreach ($deps as $d) {
                    // Áp dụng filter trạng thái
                    if (!empty($statusFilter) && isset($d['status']) && $d['status'] != $statusFilter) {
                        continue;
                    }
                    
                    // Áp dụng filter loại tour (category)
                    if (!empty($categoryFilter)) {
                        $tourId = $d['tour_id'] ?? null;
                        if ($tourId) {
                            $tour = $this->tourModel->find($tourId);
                            if ($tour) {
                                // Kiểm tra loại tour dựa trên tour_type hoặc category
                                $tourType = $tour['tour_type'] ?? '';
                                if ($categoryFilter == 'domestic' && $tourType != 'domestic') continue;
                                if ($categoryFilter == 'international' && $tourType != 'international') continue;
                            }
                        }
                    }
                    
                    // Áp dụng filter tìm kiếm
                    if (!empty($searchFilter)) {
                        $tourName = $d['tour_name'] ?? '';
                        if (stripos($tourName, $searchFilter) === false) {
                            continue;
                        }
                    }
                    
                    $color = '#858796'; // Default color
                    if (isset($d['status'])) {
                        if ($d['status'] == 'scheduled') $color = '#f6c23e'; // Vàng
                        if ($d['status'] == 'ongoing') $color = '#4e73df';   // Xanh dương
                        if ($d['status'] == 'finished') $color = '#1cc88a';  // Xanh lá
                        if ($d['status'] == 'cancelled') $color = '#e74a3b'; // Đỏ
                    }

                    // Lấy departure_date từ dữ liệu được chuẩn hóa
                    $departureDate = $d['departure_date'] ?? null;
                    if (!$departureDate) continue; // Bỏ qua nếu không có ngày khởi hành
                    
                    try {
                        // Tính ngày kết thúc
                        $startDate = new DateTime($departureDate);
                        $endDate = clone $startDate;
                        // Nếu không có duration_days, sử dụng 1 ngày mặc định
                        $durationDays = !empty($d['duration_days']) ? $d['duration_days'] : 1;
                        $endDate->modify('+' . $durationDays . ' days');

                        $events[] = [
                            'id' => 'dep-' . $d['id'],
                            'title' => 'KH: ' . ($d['tour_name'] ?? 'N/A'),
                            'start' => $startDate->format('Y-m-d'),
                            'end' => $endDate->format('Y-m-d'),
                            'backgroundColor' => $color,
                            'borderColor' => $color,
                            'url' => url('departures_edit?id=' . $d['id'])
                        ];
                    } catch (Exception $dateErr) {
                        error_log('Date error for departure ' . $d['id'] . ': ' . $dateErr->getMessage());
                        continue; // Bỏ qua entry này nếu có lỗi date
                    }
                }
            }

            // Lấy lịch phân công hướng dẫn viên (nếu bảng tour_assignments tồn tại)
            try {
                $assignmentSql = "SELECT ta.id, ta.assignment_date, u.full_name as guide_name, t.name as tour_name
                                  FROM tour_assignments ta
                                  JOIN guides g ON ta.guide_id = g.id
                                  JOIN users u ON g.user_id = u.id
                                  JOIN tours t ON ta.tour_id = t.id
                                  WHERE ta.assignment_date IS NOT NULL
                                  LIMIT 1000";
                $assignments = $this->tourModel->fetchAll($assignmentSql, []);
                if (!empty($assignments)) {
                    foreach ($assignments as $assign) {
                        if (!isset($assign['assignment_date']) || !$assign['assignment_date']) continue;
                        
                        $events[] = [
                            'id' => 'assign-' . $assign['id'],
                            'title' => 'HDV ' . ($assign['guide_name'] ?? 'N/A') . ' - ' . ($assign['tour_name'] ?? 'N/A'),
                            'start' => $assign['assignment_date'],
                            'backgroundColor' => '#6f42c1', // Màu tím cho phân công
                            'borderColor' => '#6f42c1',
                            'url' => url('assignments')
                        ];
                    }
                }
            } catch (Exception $assignErr) {
                // tour_assignments có thể không tồn tại, bỏ qua nhưng vẫn log
                error_log('Assignment table error: ' . $assignErr->getMessage());
            }

            header('Content-Type: application/json');
            echo json_encode($events);
        } catch (Exception $e) {
            // Log lỗi để debug
            error_log('API schedule_events error: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            // Trả về response với status 500 nhưng JSON hợp lệ
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Server error occurred']);
        }

        // 5. QUAN TRỌNG: Ngắt chương trình ngay lập tức
        exit(); 
    }
}
?>
