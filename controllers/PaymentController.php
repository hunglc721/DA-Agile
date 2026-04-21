<?php

class PaymentController extends BaseController
{
    private $bookingModel;

    private function getBookingModel()
    {
        if (!$this->bookingModel) {
            $this->bookingModel = new Booking();
        }
        return $this->bookingModel;
    }

    /**
     * Danh sách tất cả booking với payment status
     */
    public function index()
    {
        requireAdmin();
        
        $status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? null;
        
        if ($status) {
            $bookings = $this->getBookingModel()->getByPaymentStatus($status);
        } else {
            $bookings = $this->getBookingModel()->findAll();
        }

        // Filter by search if provided
        if ($search) {
            $bookings = array_filter($bookings, function ($booking) use ($search) {
                return stripos($booking['id'], $search) !== false ||
                       stripos($booking['tour_name'], $search) !== false ||
                       stripos($booking['user_name'], $search) !== false;
            });
        }

        // Sort by latest
        usort($bookings, function ($a, $b) {
            return strtotime($b['booking_date']) - strtotime($a['booking_date']);
        });

        $this->renderView('admin/payments/index', [
            'bookings' => $bookings,
            'status' => $status,
            'search' => $search
        ]);
    }

    /**
     * Chi tiết thanh toán của 1 booking
     */
    public function show()
    {
        requireAdmin();
        
        $bookingId = $_GET['id'] ?? null;
        if (!$bookingId) {
            header('Location: ?action=admin_payments');
            exit;
        }

        $booking = $this->getBookingModel()->find($bookingId);
        if (!$booking) {
            $this->renderError('Không tìm thấy đơn booking');
            return;
        }

        $this->renderView('admin/payments/show', [
            'booking' => $booking
        ]);
    }

    /**
     * Xác nhận đã nhận tiền - Update payment status
     */
    public function confirmPayment()
    {
        requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.0 405 Method Not Allowed');
            header('Content-Type: application/json');
            exit;
        }

        header('Content-Type: application/json; charset=utf-8');

        $bookingId = $_POST['booking_id'] ?? null;
        $newStatus = $_POST['payment_status'] ?? null;

        if (!$bookingId || !$newStatus) {
            echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        // Validate payment status
        $validStatuses = ['unpaid', 'deposit_paid', 'partially_paid', 'paid', 'confirmed_by_admin'];
        if (!in_array($newStatus, $validStatuses)) {
            echo json_encode(['status' => 'error', 'message' => 'Trạng thái không hợp lệ']);
            exit;
        }

        try {
            $db = $this->getBookingModel()->getConnection();
            
            $sql = "UPDATE bookings SET payment_status = :status, payment_date = NOW() WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':status' => $newStatus, ':id' => $bookingId]);

            // If confirming payment, also update booking status
            if ($newStatus === 'paid') {
                $sql2 = "UPDATE bookings SET status = 'confirmed' WHERE id = :id";
                $stmt2 = $db->prepare($sql2);
                $stmt2->execute([':id' => $bookingId]);
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái thanh toán thành công',
                'newStatus' => $newStatus
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Lấy danh sách payment theo từng trạng thái
     */
    public function getPaymentStats()
    {
        requireAdmin();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $stats = [];
            $statuses = ['unpaid', 'deposit_paid', 'partially_paid', 'paid'];

            foreach ($statuses as $status) {
                $bookings = $this->getBookingModel()->getByPaymentStatus($status);
                $totalAmount = 0;
                
                foreach ($bookings as $booking) {
                    $totalAmount += (float)($booking['paid_amount'] ?? 0);
                }

                $stats[$status] = [
                    'count' => count($bookings),
                    'totalAmount' => $totalAmount
                ];
            }

            echo json_encode(['status' => 'success', 'data' => $stats]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Export report thanh toán
     */
    public function exportReport()
    {
        requireAdmin();
        
        $status = $_GET['status'] ?? null;
        $bookings = $status ? $this->getBookingModel()->getByPaymentStatus($status) : $this->getBookingModel()->findAll();

        // Sort
        usort($bookings, function ($a, $b) {
            return strtotime($b['booking_date']) - strtotime($a['booking_date']);
        });

        // CSV header
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=payment_report_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

        // Headers
        fputcsv($output, [
            'Mã Booking',
            'Tour',
            'Khách Hàng',
            'Số Người',
            'Tổng Tiền',
            'Đã Thanh Toán',
            'Trạng Thái Thanh Toán',
            'Phương Thức',
            'Ngày Booking',
            'Ngày Thanh Toán'
        ]);

        // Data
        foreach ($bookings as $booking) {
            fputcsv($output, [
                $booking['id'],
                $booking['tour_name'] ?? 'N/A',
                $booking['user_name'] ?? 'N/A',
                $booking['number_of_people'] ?? 0,
                $booking['total_price'] ?? 0,
                $booking['paid_amount'] ?? 0,
                $booking['payment_status'] ?? 'unpaid',
                $booking['payment_method'] ?? 'N/A',
                $booking['booking_date'] ?? 'N/A',
                $booking['payment_date'] ?? 'N/A'
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Render view with data
     */
    protected function renderView($view, $data = [])
    {
        extract($data);
        $content_view = $view;
        include PATH_VIEW . 'layouts/main.php';
    }

    /**
     * Render error
     */
    protected function renderError($message)
    {
        echo '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
    }
}
