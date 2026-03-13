<?php
class TourPricing extends BaseModel
{
    protected $table = 'tour_pricings';

    /**
     * Lấy tất cả bảng giá của tour
     */
    public function findByTour($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? ORDER BY order_number ASC, base_price ASC";
        return $this->fetchAll($sql, [$tourId]);
    }

    /**
     * Lấy bảng giá hoạt động của tour
     */
    public function findActivePricing($tourId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE tour_id = ? AND is_active = 1 
                AND (effective_from IS NULL OR effective_from <= CURDATE())
                AND (effective_to IS NULL OR effective_to >= CURDATE())
                ORDER BY min_group_size ASC";
        return $this->fetchAll($sql, [$tourId]);
    }

    /**
     * Lấy giá phù hợp với kích thước nhóm
     */
    public function getPriceByGroupSize($tourId, $groupSize)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE tour_id = ? AND is_active = 1 
                AND min_group_size <= ? 
                AND (max_group_size IS NULL OR max_group_size >= ?)
                AND (effective_from IS NULL OR effective_from <= CURDATE())
                AND (effective_to IS NULL OR effective_to >= CURDATE())
                ORDER BY min_group_size DESC LIMIT 1";
        return $this->fetchOne($sql, [$tourId, $groupSize, $groupSize]);
    }

    /**
     * Lấy giá theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Tạo bảng giá mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (tour_id, name, description, base_price, min_group_size, max_group_size, discount_percent, effective_from, effective_to, is_active, order_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['tour_id'],
            $data['name'],
            $data['description'] ?? null,
            $data['base_price'],
            $data['min_group_size'] ?? 1,
            $data['max_group_size'] ?? null,
            $data['discount_percent'] ?? 0,
            $data['effective_from'] ?? null,
            $data['effective_to'] ?? null,
            $data['is_active'] ?? 1,
            $data['order_number'] ?? 0
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật bảng giá
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                name = ?, description = ?, base_price = ?, min_group_size = ?, max_group_size = ?, 
                discount_percent = ?, effective_from = ?, effective_to = ?, is_active = ?, order_number = ?
                WHERE id = ?";

        $params = [
            $data['name'],
            $data['description'] ?? null,
            $data['base_price'],
            $data['min_group_size'] ?? 1,
            $data['max_group_size'] ?? null,
            $data['discount_percent'] ?? 0,
            $data['effective_from'] ?? null,
            $data['effective_to'] ?? null,
            $data['is_active'] ?? 1,
            $data['order_number'] ?? 0,
            $id
        ];

        $this->query($sql, $params);
        return true;
    }

    /**
     * Xóa bảng giá
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

        if (empty($data['name'])) {
            $errors['name'] = 'Tên bảng giá bắt buộc';
        }

        if (empty($data['base_price']) || !is_numeric($data['base_price']) || $data['base_price'] <= 0) {
            $errors['base_price'] = 'Giá cơ bản phải lớn hơn 0';
        }

        if (!empty($data['min_group_size']) && !is_numeric($data['min_group_size'])) {
            $errors['min_group_size'] = 'Số người tối thiểu phải là số';
        }

        if (!empty($data['discount_percent']) && (!is_numeric($data['discount_percent']) || $data['discount_percent'] < 0 || $data['discount_percent'] > 100)) {
            $errors['discount_percent'] = 'Phần trăm giảm giá phải từ 0-100%';
        }

        return $errors;
    }

    /**
     * Tính giá sau giảm
     */
    public function calculateFinalPrice($basePrice, $discountPercent)
    {
        return $basePrice * (1 - ($discountPercent / 100));
    }

    /**
     * Xóa tất cả bảng giá của tour
     */
    public function deleteByTour($tourId)
    {
        $sql = "DELETE FROM {$this->table} WHERE tour_id = ?";
        $this->query($sql, [$tourId]);
        return true;
    }
}
?>
