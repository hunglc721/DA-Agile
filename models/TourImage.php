<?php
class TourImage extends BaseModel
{
    protected $table = 'tour_images';

    /**
     * Lấy ảnh theo tour
     */
    public function findByTour($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? ORDER BY is_main DESC, created_at DESC";
        return $this->fetchAll($sql, [$tourId]);
    }

    /**
     * Lấy ảnh theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Lấy ảnh chính của tour
     */
    public function getMainImage($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? AND is_main = 1 LIMIT 1";
        return $this->fetchOne($sql, [$tourId]);
    }

    /**
     * Tạo ảnh mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (tour_id, image_url, is_main) VALUES (?, ?, ?)";
        $params = [
            $data['tour_id'],
            $data['image_url'],
            $data['is_main'] ?? 0
        ];
        
        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật ảnh
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET image_url = ?, is_main = ? WHERE id = ?";
        $params = [
            $data['image_url'],
            $data['is_main'] ?? 0,
            $id
        ];
        
        $this->query($sql, $params);
        return true;
    }

    /**
     * Xóa ảnh
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->query($sql, [$id]);
        return true;
    }

    /**
     * Đặt ảnh là ảnh chính
     */
    public function setMainImage($tourId, $imageId)
    {
        // Bỏ ảnh chính cũ
        $sql = "UPDATE {$this->table} SET is_main = 0 WHERE tour_id = ? AND is_main = 1";
        $this->query($sql, [$tourId]);

        // Đặt ảnh mới là ảnh chính
        $sql = "UPDATE {$this->table} SET is_main = 1 WHERE id = ? AND tour_id = ?";
        $this->query($sql, [$imageId, $tourId]);
        
        return true;
    }

    /**
     * Validate dữ liệu
     */
    public function validate($data)
    {
        $errors = [];

        if (empty($data['tour_id']) || !is_numeric($data['tour_id'])) {
            $errors['tour_id'] = 'Tour ID không hợp lệ';
        }

        if (empty($data['image_url'])) {
            $errors['image_url'] = 'URL ảnh bắt buộc';
        }

        // Kiểm tra định dạng URL
        if (!empty($data['image_url']) && !filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
            $errors['image_url'] = 'URL ảnh không hợp lệ';
        }

        return $errors;
    }

    /**
     * Xóa tất cả ảnh của tour
     */
    public function deleteByTour($tourId)
    {
        $sql = "DELETE FROM {$this->table} WHERE tour_id = ?";
        $this->query($sql, [$tourId]);
        return true;
    }

    /**
     * Đếm số ảnh của tour
     */
    public function countByTour($tourId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE tour_id = ?";
        $result = $this->fetchOne($sql, [$tourId]);
        return $result['count'] ?? 0;
    }
}
?>
