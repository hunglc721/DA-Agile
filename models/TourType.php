<?php
class TourType extends BaseModel
{
    protected $table = 'tour_types';

    /**
     * Lấy tất cả loại tour
     */
    public function findAll($filters = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND name LIKE ?";
            $params[] = "%{$filters['search']}%";
        }

        $sql .= " ORDER BY id ASC";
        return $this->fetchAll($sql, $params);
    }

    /**
     * Lấy loại tour theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND is_active = 1";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Lấy loại tour theo code
     */
    public function findByCode($code)
    {
        $sql = "SELECT * FROM {$this->table} WHERE code = ? AND is_active = 1";
        return $this->fetchOne($sql, [$code]);
    }

    /**
     * Lấy tất cả loại tour (kể cả không hoạt động)
     */
    public function findAllIncludeInactive()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id ASC";
        return $this->fetchAll($sql);
    }

    /**
     * Tạo loại tour mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (code, name, description, icon, color, is_active, display_order)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['code'],
            $data['name'],
            $data['description'] ?? null,
            $data['icon'] ?? null,
            $data['color'] ?? null,
            $data['is_active'] ?? 1,
            $data['display_order'] ?? 0
        ];
        
        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật loại tour
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                code = ?, 
                name = ?, 
                description = ?, 
                icon = ?, 
                color = ?, 
                is_active = ?, 
                display_order = ? 
                WHERE id = ?";
        
        $params = [
            $data['code'],
            $data['name'],
            $data['description'] ?? null,
            $data['icon'] ?? null,
            $data['color'] ?? null,
            $data['is_active'] ?? 1,
            $data['display_order'] ?? 0,
            $id
        ];
        
        $this->query($sql, $params);
        return true;
    }

    /**
     * Xóa loại tour
     */
    public function delete($id)
    {
        // Kiểm tra nếu có tour sử dụng loại này
        $sql = "SELECT COUNT(*) as count FROM tours WHERE tour_type = (SELECT code FROM {$this->table} WHERE id = ?)";
        $result = $this->fetchOne($sql, [$id]);
        
        if ($result['count'] > 0) {
            throw new Exception("Không thể xóa loại tour này vì có tour đang sử dụng");
        }

        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->query($sql, [$id]);
        return true;
    }

    /**
     * Validate dữ liệu
     */
    public function validate($data, $isUpdate = false, $id = null)
    {
        $errors = [];

        // Code validation
        if (empty($data['code'])) {
            $errors['code'] = 'Mã loại tour không được để trống';
        } elseif (strlen($data['code']) < 2 || strlen($data['code']) > 50) {
            $errors['code'] = 'Mã loại tour phải từ 2 đến 50 ký tự';
        } else {
            // Kiểm tra code có trùng lặp
            $sql = "SELECT id FROM {$this->table} WHERE code = ?";
            $params = [$data['code']];
            
            if ($isUpdate) {
                $sql .= " AND id != ?";
                $params[] = $id;
            }
            
            $existing = $this->fetchOne($sql, $params);
            if ($existing) {
                $errors['code'] = 'Mã loại tour này đã tồn tại';
            }
        }

        // Name validation
        if (empty($data['name']) || strlen($data['name']) < 3) {
            $errors['name'] = 'Tên loại tour phải có ít nhất 3 ký tự';
        } elseif (strlen($data['name']) > 100) {
            $errors['name'] = 'Tên loại tour không vượt quá 100 ký tự';
        }

        // Description validation
        if (!empty($data['description']) && strlen($data['description']) > 500) {
            $errors['description'] = 'Mô tả không vượt quá 500 ký tự';
        }

        return $errors;
    }

    /**
     * Bật/Tắt loại tour
     */
    public function toggleActive($id)
    {
        $sql = "UPDATE {$this->table} SET is_active = !is_active WHERE id = ?";
        $this->query($sql, [$id]);
        return true;
    }

    /**
     * Lấy loại tour theo tên
     */
    public function findByName($name)
    {
        $sql = "SELECT * FROM {$this->table} WHERE name = ? AND is_active = 1";
        return $this->fetchOne($sql, [$name]);
    }
}
