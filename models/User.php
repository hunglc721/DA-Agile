<?php
class User extends BaseModel
{
    protected $table = 'users';

    /**
     * Lấy tất cả người dùng
     */
    public function findAll($filters = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['role'])) {
            if ($filters['role'] === 'admin') {
                $sql .= " AND is_admin = ?";
                $params[] = 1;
            } elseif ($filters['role'] === 'guide') {
                $sql .= " AND id IN (SELECT user_id FROM guides)";
            } else {
                $sql .= " AND is_admin = ? AND id NOT IN (SELECT user_id FROM guides)";
                $params[] = 0;
            }
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $sql .= " ORDER BY created_at DESC";
        return $this->fetchAll($sql, $params);
    }

    /**
     * Lấy người dùng theo ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Lấy người dùng theo username
     */
    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = ?";
        return $this->fetchOne($sql, [$username]);
    }

    /**
     * Lấy người dùng theo email
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        return $this->fetchOne($sql, [$email]);
    }

    /**
     * Tạo người dùng mới
     */
    public function create($data)
    {
        // Kiểm tra email đã tồn tại
        if ($this->findByEmail($data['email'])) {
            throw new Exception('Email đã tồn tại');
        }

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Xác định is_admin dựa vào role
        $isAdmin = 0;
        if ($data['role'] === 'admin') {
            $isAdmin = 1;
        }

        // Tạo username từ email
        $username = explode('@', $data['email'])[0];

        $sql = "INSERT INTO {$this->table} 
                (username, email, password, full_name, phone, is_admin, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $params = [
            $username,
            $data['email'],
            $hashedPassword,
            $data['full_name'] ?? null,
            $data['phone'] ?? null,
            $isAdmin
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật người dùng
     */
    public function update($id, $data)
    {
        $updateFields = [];
        $params = [];

        if (isset($data['email'])) {
            // Kiểm tra email không trùng với người khác
            $existing = $this->findByEmail($data['email']);
            if ($existing && $existing['id'] != $id) {
                throw new Exception('Email đã tồn tại');
            }
            $updateFields[] = "email = ?";
            $params[] = $data['email'];
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $updateFields[] = "password = ?";
            $params[] = $data['password'];  // Password đã hash từ controller
        }

        if (isset($data['full_name'])) {
            $updateFields[] = "full_name = ?";
            $params[] = $data['full_name'];
        }

        if (isset($data['phone'])) {
            $updateFields[] = "phone = ?";
            $params[] = $data['phone'];
        }

        if (empty($updateFields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updateFields) . " WHERE id = ?";
        
        return $this->query($sql, $params);
    }

    /**
     * Xóa người dùng
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    /**
     * Xác minh mật khẩu
     */
    public function verifyPassword($userId, $password)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }

    /**
     * Validate dữ liệu người dùng
     */
    public function validate($data, $isUpdate = false)
    {
        $errors = [];

        if (empty($data['username']) || strlen($data['username']) < 3) {
            $errors['username'] = 'Username phải có ít nhất 3 ký tự';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }

        if (!$isUpdate) {
            if (empty($data['password']) || strlen($data['password']) < 6) {
                $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
        } elseif (!empty($data['password']) && strlen($data['password']) < 6) {
            $errors['password'] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
        }

        if (!empty($data['phone']) && !preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại không hợp lệ';
        }

        return $errors;
    }

    /**
     * Kiểm tra người dùng là admin
     */
    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['is_admin'] == 1;
    }

    /**
     * Kiểm tra người dùng là hướng dẫn viên
     */
    public function isGuide($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM guides WHERE user_id = ?";
        $result = $this->fetchOne($sql, [$userId]);
        return $result['count'] > 0;
    }

    /**
     * Lấy tất cả hướng dẫn viên
     */
    public function getAllGuides()
    {
        $sql = "SELECT u.*, g.* FROM {$this->table} u 
                JOIN guides g ON u.id = g.user_id
                ORDER BY u.full_name";
        return $this->fetchAll($sql);
    }
}
?>
