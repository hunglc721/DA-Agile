<?php
class TourPolicy extends BaseModel
{
    protected $table = 'tour_policies';

    /**
     * Lấy tất cả policy của tour
     */
    public function findByTour($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? ORDER BY order_number ASC, created_at ASC";
        return $this->fetchAll($sql, [$tourId]);
    }

    /**
     * Lấy policy theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Lấy policy theo loại
     */
    public function findByType($tourId, $type)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? AND policy_type = ? ORDER BY order_number ASC";
        return $this->fetchAll($sql, [$tourId, $type]);
    }

    /**
     * Tạo policy mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, title, content, policy_type, order_number) 
                VALUES (?, ?, ?, ?, ?)";

        $params = [
            $data['tour_id'],
            $data['title'],
            $data['content'],
            $data['policy_type'] ?? 'other',
            $data['order_number'] ?? 0
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật policy
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                title = ?, content = ?, policy_type = ?, order_number = ?
                WHERE id = ?";

        $params = [
            $data['title'],
            $data['content'],
            $data['policy_type'] ?? 'other',
            $data['order_number'] ?? 0,
            $id
        ];

        $this->query($sql, $params);
        return true;
    }

    /**
     * Xóa policy
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
            $errors['title'] = 'Tiêu đề chính sách bắt buộc';
        }

        if (empty($data['content'])) {
            $errors['content'] = 'Nội dung chính sách bắt buộc';
        }

        return $errors;
    }
}
?>
