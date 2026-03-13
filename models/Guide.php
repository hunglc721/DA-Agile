<?php
class Guide extends BaseModel
{
    protected $table = 'guides';

    /**
     * Lấy tất cả hướng dẫn viên
     */
    public function findAll($filters = [])
    {
        $sql = "SELECT g.*, u.full_name, u.email, u.phone 
                FROM {$this->table} g
                JOIN users u ON g.user_id = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND g.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
        }

        $sql .= " ORDER BY u.full_name";
        return $this->fetchAll($sql, $params);
    }

    /**
     * Lấy hướng dẫn viên theo ID
     */
    public function find($id)
    {
        $sql = "SELECT g.*, u.full_name, u.email, u.phone 
                FROM {$this->table} g
                JOIN users u ON g.user_id = u.id
                WHERE g.id = ?";
        
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Lấy hướng dẫn viên theo user_id
     */
    public function findByUserId($userId)
    {
        $sql = "SELECT g.*, u.full_name, u.email, u.phone 
                FROM {$this->table} g
                JOIN users u ON g.user_id = u.id
                WHERE g.user_id = ?";
        
        return $this->fetchOne($sql, [$userId]);
    }

    /**
     * Tạo hướng dẫn viên mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (user_id, license_number, experience_years, languages, specialties, status)
                VALUES (?, ?, ?, ?, ?, ?)";

        $params = [
            $data['user_id'],
            $data['license_number'] ?? null,
            $data['experience_years'] ?? 0,
            $data['languages'] ?? 'Tiếng Việt',
            $data['specialties'] ?? null,
            $data['status'] ?? 'active'
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật hướng dẫn viên
     */
    public function update($id, $data)
    {
        $updateFields = [];
        $params = [];

        if (isset($data['license_number'])) {
            $updateFields[] = "license_number = ?";
            $params[] = $data['license_number'];
        }

        if (isset($data['experience_years'])) {
            $updateFields[] = "experience_years = ?";
            $params[] = $data['experience_years'];
        }

        if (isset($data['languages'])) {
            $updateFields[] = "languages = ?";
            $params[] = $data['languages'];
        }

        if (isset($data['specialties'])) {
            $updateFields[] = "specialties = ?";
            $params[] = $data['specialties'];
        }

        if (isset($data['status'])) {
            $updateFields[] = "status = ?";
            $params[] = $data['status'];
        }

        if (empty($updateFields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updateFields) . " WHERE id = ?";
        
        return $this->query($sql, $params);
    }

    /**
     * Xóa hướng dẫn viên
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    /**
     * Lấy các tour được gán cho hướng dẫn viên
     */
    public function getAssignedTours($guideId)
    {
        $sql = "SELECT t.*, ta.assignment_date, ta.status as assignment_status
                FROM tours t
                JOIN tour_assignments ta ON t.id = ta.tour_id
                WHERE ta.guide_id = ?
                ORDER BY ta.assignment_date DESC";
        
        return $this->fetchAll($sql, [$guideId]);
    }

    /**
     * Lấy số tour đã hướng dẫn
     */
    public function getTourCount($guideId)
    {
        $sql = "SELECT COUNT(*) as count FROM tour_assignments 
                WHERE guide_id = ? AND status = 'completed'";
        
        $result = $this->fetchOne($sql, [$guideId]);
        return $result['count'] ?? 0;
    }

    /**
     * Kiểm tra hướng dẫn viên có sẵn trong ngày
     */
    public function isAvailableOnDate($guideId, $date)
    {
        $sql = "SELECT COUNT(*) as count FROM tour_assignments 
                WHERE guide_id = ? AND assignment_date = ? AND status != 'cancelled'";
        
        $result = $this->fetchOne($sql, [$guideId, $date]);
        return $result['count'] == 0;
    }

    /**
     * Lấy hướng dẫn viên theo chuyên môn
     */
    public function findBySpecialty($specialty)
    {
        $sql = "SELECT g.*, u.full_name, u.email 
                FROM {$this->table} g
                JOIN users u ON g.user_id = u.id
                WHERE g.specialties LIKE ? AND g.status = 'active'
                ORDER BY g.experience_years DESC";
        
        return $this->fetchAll($sql, ["%{$specialty}%"]);
    }

    /**
     * Lấy hướng dẫn viên theo ngôn ngữ
     */
    public function findByLanguage($language)
    {
        $sql = "SELECT g.*, u.full_name, u.email 
                FROM {$this->table} g
                JOIN users u ON g.user_id = u.id
                WHERE g.languages LIKE ? AND g.status = 'active'
                ORDER BY g.experience_years DESC";
        
        return $this->fetchAll($sql, ["%{$language}%"]);
    }

    /**
     * Validate dữ liệu hướng dẫn viên
     */
    public function validate($data)
    {
        $errors = [];

        if (empty($data['user_id'])) {
            $errors['user_id'] = 'Người dùng không hợp lệ';
        }

        if (!empty($data['experience_years']) && !is_numeric($data['experience_years'])) {
            $errors['experience_years'] = 'Năm kinh nghiệm phải là số';
        }

        if (!empty($data['license_number']) && strlen($data['license_number']) < 3) {
            $errors['license_number'] = 'Số giấy phép phải có ít nhất 3 ký tự';
        }

        return $errors;
    }

    /**
     * Kiểm tra hướng dẫn viên đang hoạt động
     */
    public function isActive($guideId)
    {
        $guide = $this->find($guideId);
        return $guide && $guide['status'] === 'active';
    }
}
?>
