<?php
class Booking extends BaseModel
{
    protected $table = 'bookings';

    public function findAll() {
        return $this->fetchAll("SELECT * FROM {$this->table}");
    }

    // Thống kê doanh thu theo tháng
    public function getMonthlyRevenue($year) {
        $sql = "SELECT MONTH(created_at) as month, SUM(total_price) as revenue 
                FROM {$this->table} 
                WHERE YEAR(created_at) = ? AND status IN ('confirmed', 'completed')
                GROUP BY MONTH(created_at)";
        return $this->fetchAll($sql, [$year]);
    }

    // Thống kê trạng thái booking
    public function getStatusStatistics() {
        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        return $this->fetchAll($sql);
    }

    // Mở file models/Booking.php và thêm các phương thức sau vào class Booking:

    /**
     * Lấy danh sách booking theo tour để điểm danh
     */
    // models/Booking.php

public function getBookingsByTour($tourId) {
    // Giả sử bảng bookings có cột tour_id
    // JOIN với bảng users để lấy tên và sđt (hoặc lấy trực tiếp nếu lưu trong bookings)
    $sql = "SELECT b.*, u.full_name, u.phone 
            FROM bookings b 
            LEFT JOIN users u ON b.user_id = u.id 
            WHERE b.tour_id = ? AND b.status = 'confirmed'"; // Chỉ lấy đơn đã xác nhận
            
    // Nếu bạn muốn lọc theo ngày khởi hành, cần join với bảng tour_schedules
    
    return $this->fetchAll($sql, [$tourId]);
}

public function updateCheckinStatus($bookingId, $status) {
    $sql = "UPDATE bookings SET checkin_status = ?, checkin_time = NOW() WHERE id = ?";
    
    return $this->query($sql, [$status, $bookingId]);
}
    // Thêm vào trong class Booking

/**
 * Tạo booking mới (Khách lẻ/Đoàn)
 */

/**
     * Tìm booking theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }
public function createBooking($data) {
    // Kiểm tra tour có tồn tại và còn chỗ không
    $tourModel = new Tour();
    $tour = $tourModel->find($data['tour_id']);

    if (!$tour) {
        return ['status' => false, 'message' => 'Tour không tồn tại'];
    }

    if ($tour['available_slots'] < $data['number_of_people']) {
        return ['status' => false, 'message' => 'Không đủ chỗ trống (Còn lại: ' . $tour['available_slots'] . ')'];
    }

    // Tính tổng tiền (nếu chưa truyền vào)
    if (empty($data['total_price'])) {
        $data['total_price'] = $tour['price'] * $data['number_of_people'];
    }

    $sql = "INSERT INTO bookings 
            (tour_id, user_id, booking_date, number_of_people, total_price, status, booking_type, customer_list, special_requests)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $params = [
        $data['tour_id'],
        $data['user_id'],
        date('Y-m-d'), // Ngày đặt là hôm nay
        $data['number_of_people'],
        $data['total_price'],
        'pending', // Mặc định chờ xác nhận
        $data['booking_type'] ?? 'retail',
        $data['customer_list'] ?? null, // JSON danh sách khách
        $data['special_requests'] ?? null
    ];

    $this->query($sql, $params);
    $bookingId = $this->lastInsertId();

    // Trừ số chỗ trống của tour
    $tourModel->updateAvailableSlots($data['tour_id'], $data['number_of_people']);

    return ['status' => true, 'booking_id' => $bookingId];
}

public function update($id, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }

        if (empty($fields)) {
            return false;
        }

        // Thêm ID vào cuối mảng params cho điều kiện WHERE
        $params[] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->query($sql, $params);
    }
public function getCustomersByTourId($tourId) {
        // Bỏ 'b.booking_code' trong câu SELECT
        $sql = "SELECT b.id as booking_id, b.number_of_people, 
                       b.special_requests, b.status as booking_status, b.total_price, 
                       b.created_at, u.full_name, u.phone, u.email 
                FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                WHERE b.tour_id = ? 
                ORDER BY b.created_at DESC";

        // Sử dụng $this->fetchAll có sẵn của BaseModel
        return $this->fetchAll($sql, [$tourId]);
    }
    public function delete($id) {
    // Câu lệnh SQL xóa booking theo ID
    $sql = "DELETE FROM bookings WHERE id = ?";
    
    // Sử dụng hàm query có sẵn trong BaseModel
    return $this->query($sql, [$id]);
}
}
?>