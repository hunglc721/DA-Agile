<?php
class Supplier extends BaseModel
{
    protected $table = 'suppliers';

    /**
     * Lấy tất cả nhà cung cấp
     */
    public function findAll($filters = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        // Search by name
        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $sql .= " ORDER BY name ASC";

        return $this->fetchAll($sql, $params);
    }

    /**
     * Lấy nhà cung cấp theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Lấy nhà cung cấp với thông tin tour
     */
    public function findWithTours($id)
    {
        $supplier = $this->find($id);
        if (!$supplier) {
            return null;
        }

        // Lấy danh sách tour từ nhà cung cấp này
        $sql = "SELECT id, tour_code, name, price, status FROM tours WHERE supplier_id = ? ORDER BY name ASC";
        $supplier['tours'] = $this->fetchAll($sql, [$id]);

        return $supplier;
    }

    /**
     * Tạo nhà cung cấp mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (name, contact_person, email, phone, address, description, website, commission_rate, payment_terms, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['name'],
            $data['contact_person'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['description'] ?? null,
            $data['website'] ?? null,
            $data['commission_rate'] ?? 0,
            $data['payment_terms'] ?? null,
            $data['status'] ?? 'active'
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật nhà cung cấp
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                name = ?, contact_person = ?, email = ?, phone = ?, 
                address = ?, description = ?, website = ?, 
                commission_rate = ?, payment_terms = ?, status = ?
                WHERE id = ?";

        $params = [
            $data['name'],
            $data['contact_person'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['description'] ?? null,
            $data['website'] ?? null,
            $data['commission_rate'] ?? 0,
            $data['payment_terms'] ?? null,
            $data['status'] ?? 'active',
            $id
        ];

        $this->query($sql, $params);
        return true;
    }

    /**
     * Xóa nhà cung cấp
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
            $errors['name'] = 'Tên nhà cung cấp bắt buộc';
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }

        if (!empty($data['phone']) && !preg_match('/^[0-9\s\+\-]+$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại không hợp lệ';
        }

        if (!empty($data['commission_rate']) && (!is_numeric($data['commission_rate']) || $data['commission_rate'] < 0 || $data['commission_rate'] > 100)) {
            $errors['commission_rate'] = 'Tỷ lệ hoa hồng phải từ 0-100%';
        }

        return $errors;
    }

    /**
     * Lấy số tour của nhà cung cấp
     */
    public function getTourCount($supplierId)
    {
        $sql = "SELECT COUNT(*) as count FROM tours WHERE supplier_id = ?";
        $result = $this->fetchOne($sql, [$supplierId]);
        return $result['count'] ?? 0;
    }

    /**
     * Kiểm tra email tồn tại
     */
    public function checkEmailExists($email, $exceptId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $result = $this->fetchOne($sql, $params);
        return $result['count'] > 0;
    }
}
?>
