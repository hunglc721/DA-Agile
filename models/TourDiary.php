<?php
class TourDiary extends BaseModel
{
    protected $table = 'tour_diaries';

    public function findAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->fetchAll($sql);
    }

    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    // Hàm quan trọng để lấy nhật ký theo Tour
    public function findByTour($tour_id) {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = ? ORDER BY created_at DESC";
        return $this->fetchAll($sql, [$tour_id]);
    }

    public function create($data) {
        $errors = $this->validate($data);
        if (!empty($errors)) throw new Exception(implode(', ', $errors));

        $images = !empty($data['images']) ? (is_array($data['images']) ? json_encode($data['images']) : $data['images']) : null;

        $sql = "INSERT INTO {$this->table} 
                (tour_id, diary_type, title, content, handling, location, importance_level, images, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['tour_id'],
            $data['diary_type'],
            $data['title'] ?? 'Nhật ký',
            $data['content'],
            $data['handling'] ?? null,
            $data['location'] ?? null,
            $data['importance_level'] ?? 'normal',
            $images,
            $data['created_by'] ?? 1
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    public function update($id, $data) {
        $updateFields = [];
        $params = [];
        $allowFields = ['diary_type', 'title', 'content', 'handling', 'location', 'importance_level', 'images'];

        foreach ($allowFields as $field) {
            if (isset($data[$field])) {
                $updateFields[] = "$field = ?";
                $params[] = ($field === 'images' && is_array($data[$field])) ? json_encode($data[$field]) : $data[$field];
            }
        }

        if (empty($updateFields)) return false;
        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updateFields) . " WHERE id = ?";
        return $this->query($sql, $params);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    public function validate($data) {
        $errors = [];
        if (empty($data['tour_id'])) $errors['tour_id'] = 'Tour không hợp lệ';
        if (empty($data['content']) || strlen($data['content']) < 5) $errors['content'] = 'Nội dung quá ngắn';
        return $errors;
    }
}
?>