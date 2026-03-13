<?php
// controllers/CheckinController.php

class CheckinController
{
    private $tourModel;
    private $bookingModel;

    public function __construct() {
        $this->tourModel = new Tour();
        $this->bookingModel = new Booking();
    }

    // controllers/CheckinController.php

    public function index() {
        $tours = $this->tourModel->findAll();
        $tourId = (int)($_GET['tour_id'] ?? 0);
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $filterStatus = $_GET['status'] ?? 'all'; // all, checked_in, pending, absent

        $bookings = [];
        $selectedTour = null;
        $stats = [
            'total' => 0,
            'checked_in' => 0,
            'pending' => 0,
            'absent' => 0,
        ];

        if ($tourId > 0) {
            $selectedTour = $this->tourModel->find($tourId);
            $bookings = $this->bookingModel->getBookingsByTour($tourId);

            // Calculate statistics
            $stats['total'] = count($bookings);
            foreach ($bookings as $b) {
                $checkinStatus = $b['checkin_status'] ?? 'pending';
                if ($checkinStatus === 'checked_in') {
                    $stats['checked_in']++;
                } elseif ($checkinStatus === 'absent') {
                    $stats['absent']++;
                } else {
                    $stats['pending']++;
                }
            }

            // Apply status filter
            if ($filterStatus !== 'all') {
                $bookings = array_filter($bookings, function($b) use ($filterStatus) {
                    return ($b['checkin_status'] ?? 'pending') === $filterStatus;
                });
            }
        }

        view('main', [
            'title' => 'Check-in & Điểm Danh',
            'page' => 'checkin_attendance',
            'content_view' => 'admin/checkin_attendance',
            'tours' => $tours,
            'selectedTourId' => $tourId,
            'selectedTour' => $selectedTour,
            'selectedDate' => $selectedDate,
            'filterStatus' => $filterStatus,
            'bookings' => array_values($bookings),
            'stats' => $stats,
        ]);
    }

    // Giữ nguyên hàm updateStatus của bạn
    // Hàm xử lý Ajax Check-in
    public function updateStatus() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid Request']);
            return;
        }

        $bookingId = $_POST['booking_id'] ?? null;
        $status = $_POST['status'] ?? null; // 'checked_in' hoặc 'pending'/'absent'

        if ($bookingId && $status) {
            // Gọi hàm updateCheckinStatus trong Booking Model
            $result = $this->bookingModel->updateCheckinStatus($bookingId, $status);
            
            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Cập nhật thành công',
                    'time' => date('H:i d/m/Y')
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật database']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
        }
        exit;
    }

    // Batch check-in for multiple customers
    public function batchCheckin() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid Request']);
            return;
        }

        $bookingIds = json_decode($_POST['booking_ids'] ?? '[]', true);
        $status = $_POST['status'] ?? 'checked_in';

        if (empty($bookingIds) || !in_array($status, ['checked_in', 'absent'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        $updated = 0;
        try {
            foreach ($bookingIds as $id) {
                $id = (int)$id;
                if ($id > 0 && $this->bookingModel->updateCheckinStatus($id, $status)) {
                    $updated++;
                }
            }
            echo json_encode(['success' => true, 'updated' => $updated, 'message' => "Cập nhật {$updated} khách hàng"]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
?>