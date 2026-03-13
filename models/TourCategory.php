<?php
class TourCategory extends BaseModel
{
    protected $table = 'tour_categories';

    /**
     * Lấy tất cả danh mục tour
     */
    public function findAll($filters = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND name LIKE ?";
            $params[] = "%{$filters['search']}%";
        }

        $sql .= " ORDER BY name";
        return $this->fetchAll($sql, $params);
    }

    /**
     * Lấy danh mục theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Tạo danh mục mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (name, description) VALUES (?, ?)";
        $params = [
            $data['name'],
            $data['description'] ?? null
        ];
        
        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật danh mục
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET name = ?, description = ? WHERE id = ?";
        $params = [
            $data['name'],
            $data['description'] ?? null,
            $id
        ];
        
        $this->query($sql, $params);
        return true;
    }

    /**
     * Xóa danh mục
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

        if (empty($data['name']) || strlen($data['name']) < 3) {
            $errors['name'] = 'Tên danh mục phải có ít nhất 3 ký tự';
        }

        if (!empty($data['description']) && strlen($data['description']) > 500) {
            $errors['description'] = 'Mô tả không vượt quá 500 ký tự';
        }

        return $errors;
    }

    /**
     * Lấy danh mục với số lượng tour
     */
    public function findWithTourCount()
    {
        $sql = "SELECT tc.*, COUNT(t.id) as tour_count 
                FROM {$this->table} tc
                LEFT JOIN tours t ON tc.id = t.category_id
                GROUP BY tc.id
                ORDER BY tc.name";
        return $this->fetchAll($sql);
    }
}
?>
