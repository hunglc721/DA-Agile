<?php
class TourItinerary extends BaseModel
{
    protected $table = 'tour_itineraries';

    /**
     * Lấy tất cả lịch trình của tour
     */
    public function findByTour($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? ORDER BY day_number ASC";
        return $this->fetchAll($sql, [$tourId]);
    }

    /**
     * Lấy lịch trình theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Lấy lịch trình theo ngày
     */
    public function findByDay($tourId, $dayNumber)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? AND day_number = ?";
        return $this->fetchOne($sql, [$tourId, $dayNumber]);
    }

    /**
     * Tạo lịch trình mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, day_number, title, description, location, activities, meals, accommodation, transportation, order_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['tour_id'],
            $data['day_number'],
            $data['title'],
            $data['description'],
            $data['location'] ?? null,
            isset($data['activities']) ? (is_array($data['activities']) ? json_encode($data['activities']) : $data['activities']) : null,
            $data['meals'] ?? null,
            $data['accommodation'] ?? null,
            $data['transportation'] ?? null,
            $data['order_number'] ?? 0
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật lịch trình
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                day_number = ?, title = ?, description = ?, location = ?, 
                activities = ?, meals = ?, accommodation = ?, transportation = ?, order_number = ?
                WHERE id = ?";

        $params = [
            $data['day_number'],
            $data['title'],
            $data['description'],
            $data['location'] ?? null,
            isset($data['activities']) ? (is_array($data['activities']) ? json_encode($data['activities']) : $data['activities']) : null,
            $data['meals'] ?? null,
            $data['accommodation'] ?? null,
            $data['transportation'] ?? null,
            $data['order_number'] ?? 0,
            $id
        ];

        $this->query($sql, $params);
        return true;
    }

    /**
     * Xóa lịch trình
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->query($sql, [$id]);
        return true;
    }

    /**
     * Validate dữ liệu
     */
    public function validate($data)
    {
        $errors = [];

        if (empty($data['day_number']) || !is_numeric($data['day_number']) || $data['day_number'] < 1) {
            $errors['day_number'] = 'Số ngày phải lớn hơn 0';
        }

        if (empty($data['title'])) {
            $errors['title'] = 'Tiêu đề ngày bắt buộc';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Mô tả ngày bắt buộc';
        }

        return $errors;
    }

    /**
     * Lấy số ngày tối đa
     */
    public function getMaxDayNumber($tourId)
    {
        $sql = "SELECT MAX(day_number) as max_day FROM {$this->table} WHERE tour_id = ?";
        $result = $this->fetchOne($sql, [$tourId]);
        return $result['max_day'] ?? 0;
    }

    /**
     * Xóa tất cả lịch trình của tour
     */
    public function deleteByTour($tourId)
    {
        $sql = "DELETE FROM {$this->table} WHERE tour_id = ?";
        $this->query($sql, [$tourId]);
        return true;
    }
}
?>
