<?php
class TourInclusion extends BaseModel
{
    protected $table = 'tour_inclusions';

    /**
     * Lấy tất cả inclusion của tour
     */
    public function findByTour($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? ORDER BY type ASC, order_number ASC";
        return $this->fetchAll($sql, [$tourId]);
    }

    /**
     * Lấy inclusion theo loại
     */
    public function findByType($tourId, $type)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? AND type = ? ORDER BY order_number ASC";
        return $this->fetchAll($sql, [$tourId, $type]);
    }

    /**
     * Lấy inclusion theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Tạo inclusion mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, title, type, order_number) 
                VALUES (?, ?, ?, ?)";

        $params = [
            $data['tour_id'],
            $data['title'],
            $data['type'] ?? 'included',
            $data['order_number'] ?? 0
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật inclusion
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                title = ?, type = ?, order_number = ?
                WHERE id = ?";

        $params = [
            $data['title'],
            $data['type'] ?? 'included',
            $data['order_number'] ?? 0,
            $id
        ];

        $this->query($sql, $params);
        return true;
    }

    /**
     * Xóa inclusion
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

        if (empty($data['title'])) {
            $errors['title'] = 'Tiêu đề bao gồm/không bao gồm bắt buộc';
        }

        return $errors;
    }

    /**
     * Xóa tất cả inclusion của tour
     */
    public function deleteByTour($tourId)
    {
        $sql = "DELETE FROM {$this->table} WHERE tour_id = ?";
        $this->query($sql, [$tourId]);
        return true;
    }

    /**
     * Lấy số lượng inclusion theo loại
     */
    public function getCountByType($tourId, $type)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE tour_id = ? AND type = ?";
        $result = $this->fetchOne($sql, [$tourId, $type]);
        return $result['count'] ?? 0;
    }
}
?>
