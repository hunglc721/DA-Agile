<?php
class Tour extends BaseModel
{
    protected $table = 'tours';

    /**
     * Lấy tất cả tour
     */
    public function findAll($filters = [])
    {
        $sql = "SELECT t.*, tc.name as category_name 
                FROM {$this->table} t 
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                WHERE 1=1";

        $params = [];

        // Filter by category
        if (!empty($filters['category_id'])) {
            $sql .= " AND t.category_id = ?";
            $params[] = $filters['category_id'];
        }

        // Filter by tour type (domestic, international, custom)
        if (!empty($filters['tour_type'])) {
            $sql .= " AND t.tour_type = ?";
            $params[] = $filters['tour_type'];
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $sql .= " AND t.status = ?";
            $params[] = $filters['status'];
        }

        // Search by name
        if (!empty($filters['search'])) {
            $sql .= " AND t.name LIKE ?";
            $params[] = "%{$filters['search']}%";
        }
        // Search theo tên HOẶC địa điểm (destinations / start_location)
        if (!empty($filters['search'])) {
            $sql .= " AND (t.name LIKE ? OR t.destinations LIKE ? OR t.start_location LIKE ?)";
            $keyword = "%" . $filters['search'] . "%";
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        //  Lọc giá tối thiểu
        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $sql .= " AND t.price >= ?";
            $params[] = (int) $filters['price_min'];
        }

        //  Lọc giá tối đa
        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $sql .= " AND t.price <= ?";
            $params[] = (int) $filters['price_max'];
        }

        //  Sắp xếp
        $sortMap = [
            'price_asc'  => 't.price ASC',
            'price_desc' => 't.price DESC',
            'name_asc'   => 't.name ASC',
            'newest'     => 't.created_at DESC',
        ];
        $sql .= " ORDER BY t.created_at DESC";

        return $this->fetchAll($sql, $params);
    }

    /**
     * Lấy tour theo ID
     */
    public function find($id)
    {
        $sql = "SELECT t.*, tc.name as category_name 
                FROM {$this->table} t 
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                WHERE t.id = ?";

        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Lấy tour với ảnh và hướng dẫn viên
     */
    public function findWithDetails($id)
    {
        $tour = $this->find($id);

        if (!$tour) {
            return null;
        }

        // Lấy ảnh tour
        $sql = "SELECT * FROM tour_images WHERE tour_id = ? ORDER BY is_main DESC";
        $tour['images'] = $this->fetchAll($sql, [$id]);

        // Lấy hướng dẫn viên gán cho tour
        $sql = "SELECT g.*, u.full_name, u.phone 
                FROM tour_assignments ta
                JOIN guides g ON ta.guide_id = g.id
                JOIN users u ON g.user_id = u.id
                WHERE ta.tour_id = ? AND ta.status = 'assigned'";
        $tour['guides'] = $this->fetchAll($sql, [$id]);

        // Lấy số booking
        $sql = "SELECT COUNT(*) as booking_count FROM bookings WHERE tour_id = ? AND status != 'cancelled'";
        $booking_count = $this->fetchOne($sql, [$id]);
        $tour['booking_count'] = $booking_count['booking_count'] ?? 0;

        return $tour;
    }

    /**
     * Tạo tour mới
     */
    /**
     * Tạo tour mới (Đã cập nhật đầy đủ các trường)
     */
    public function create($data)
    {
        // SQL Insert thêm các cột mới: tour_code, tour_type, supplier, cost_price, policy
        $sql = "INSERT INTO {$this->table} 
                (tour_code, category_id, name, tour_type, description, price, cost_price, duration, start_location, destinations, supplier, policy, max_capacity, available_slots, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['tour_code'],           // Mã tour (VD: HL001)
            $data['category_id'] ?? null,
            $data['name'],
            $data['tour_type'] ?? 'domestic', // Loại tour: domestic/international
            $data['description'] ?? null,
            $data['price'],
            $data['cost_price'] ?? 0,     // Giá vốn để tính lợi nhuận
            $data['duration'] ?? null,
            $data['start_location'] ?? null,
            $data['destinations'] ?? null,
            $data['supplier'] ?? null,    // Nhà cung cấp
            $data['policy'] ?? null,      // Chính sách
            $data['max_capacity'] ?? null,
            $data['available_slots'] ?? null,
            $data['status'] ?? 'active'
        ];

        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    /**
     * Cập nhật tour
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                category_id = ?, 
                name = ?, 
                description = ?, 
                price = ?, 
                duration = ?, 
                start_location = ?, 
                destinations = ?, 
                max_capacity = ?, 
                available_slots = ?, 
                status = ?
                WHERE id = ?";

        $params = [
            $data['category_id'] ?? null,
            $data['name'],
            $data['description'] ?? null,
            $data['price'],
            $data['duration'] ?? null,
            $data['start_location'] ?? null,
            $data['destinations'] ?? null,
            $data['max_capacity'] ?? null,
            $data['available_slots'] ?? null,
            $data['status'] ?? 'active',
            $id
        ];

        return $this->query($sql, $params);
    }


    /**
     * Xóa tour
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    /**
     * Lấy tour theo danh mục
     */
    public function findByCategory($categoryId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = ? ORDER BY name";
        return $this->fetchAll($sql, [$categoryId]);
    }

    /**
     * Validate dữ liệu tour
     */
    public function validate($data)
    {
        $errors = [];

        if (empty($data['name']) || strlen($data['name']) < 5) {
            $errors['name'] = 'Tên tour phải có ít nhất 5 ký tự';
        }

        if (empty($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Giá tour phải là số dương';
        }

        if (empty($data['category_id']) || !is_numeric($data['category_id'])) {
            $errors['category_id'] = 'Danh mục tour không hợp lệ';
        }

        if (empty($data['duration']) || !is_numeric($data['duration']) || $data['duration'] < 1) {
            $errors['duration'] = 'Thời lượng tour phải ≥ 1';
        }

        if (empty($data['max_capacity']) || !is_numeric($data['max_capacity']) || $data['max_capacity'] < 1) {
            $errors['max_capacity'] = 'Sức chứa phải ≥ 1';
        }

        // ✅ Check category exists
        if (!empty($data['category_id'])) {
            $category = (new TourCategory())->find($data['category_id']);
            if (!$category) {
                $errors['category_id'] = 'Danh mục không tồn tại';
            }
        }

        // ✅ Check available_slots <= max_capacity
        if (!empty($data['available_slots']) && !empty($data['max_capacity'])) {
            if ($data['available_slots'] > $data['max_capacity']) {
                $errors['available_slots'] = 'Chỗ trống không được vượt quá sức chứa';
            }
        }

        return $errors;
    }

    /**
     * Kiểm tra tour có slot còn trống
     */
    public function hasAvailableSlots($id, $requiredSlots = 1)
    {
        $tour = $this->find($id);
        return $tour && $tour['available_slots'] >= $requiredSlots;
    }

    /**
     * Cập nhật số slot còn lại
     */
    public function updateAvailableSlots($id, $slots)
    {
        $sql = "UPDATE {$this->table} SET available_slots = available_slots - ? WHERE id = ?";
        return $this->query($sql, [$slots, $id]);
    }
    // Thêm vào trong class Tour

    /**
     * Lấy lịch trình chi tiết của tour
     */
    public function getItinerary($id)
    {
        $sql = "SELECT * FROM tour_itineraries WHERE tour_id = ? ORDER BY day_number ASC";
        return $this->fetchAll($sql, [$id]);
    }

    /**
     * Lấy danh sách chi phí của tour
     */
    public function getCosts($id)
    {
        $hasCosts = $this->fetchAll("SHOW TABLES LIKE 'tour_costs'");
        $table = !empty($hasCosts) ? 'tour_costs' : 'booking_services';
        $sql = "SELECT * FROM {$table} WHERE tour_id = ?";
        $rows = $this->fetchAll($sql, [$id]);
        foreach ($rows as &$r) {
            // Chuẩn hóa tên dịch vụ
            $r['name'] = $r['name']
                ?? ($r['service_name'] ?? ($r['cost_name'] ?? ($r['ServiceName'] ?? ($r['CostName'] ?? ($r['Name'] ?? ($r['title'] ?? ($r['service_title'] ?? ($r['item_name'] ?? ''))))))));
            // Chuẩn hóa nhà cung cấp
            $r['vendor'] = $r['vendor']
                ?? ($r['provider'] ?? ($r['supplier'] ?? ($r['Vendor'] ?? ($r['Provider'] ?? ($r['Supplier'] ?? ($r['vendor_name'] ?? ($r['partner'] ?? ($r['company'] ?? ''))))))));
            // Chuẩn hóa ghi chú
            $r['notes'] = $r['notes']
                ?? ($r['description'] ?? ($r['remark'] ?? ($r['Notes'] ?? ($r['Description'] ?? ($r['Remark'] ?? ($r['detail'] ?? ''))))));
            // Rút trích DV và NCC từ ghi chú nếu cột tương ứng không tồn tại
            if (empty($r['name']) && !empty($r['notes'])) {
                if (preg_match('/DV\s*:\s*([^|]+)/u', $r['notes'], $m)) {
                    $r['name'] = trim($m[1]);
                }
            }
            if (empty($r['vendor']) && !empty($r['notes'])) {
                if (preg_match('/NCC\s*:\s*([^|]+)/u', $r['notes'], $m)) {
                    $r['vendor'] = trim($m[1]);
                }
            }

            if (!isset($r['amount'])) {
                $r['amount'] = $r['cost'] ?? ($r['price'] ?? 0);
            }
            // Chuẩn hóa id để thao tác
            $idCandidates = ['id', 'bs_id', 'cost_id', 'service_id', 'ID', 'BS_ID', 'CostID', 'ServiceID'];
            foreach ($idCandidates as $cid) {
                if (isset($r[$cid])) {
                    $r['id'] = $r[$cid];
                    break;
                }
            }
        }
        return $rows;
    }

    /**
     * Tính toán Doanh thu - Chi phí - Lợi nhuận cho 1 tour
     */
    public function getFinancialStats($id)
    {
        // 1. Tổng doanh thu từ các booking đã xác nhận
        $sqlRevenue = "SELECT SUM(total_price) as revenue 
                   FROM bookings 
                   WHERE tour_id = ? AND status IN ('confirmed', 'completed')";
        $revenue = $this->fetchOne($sqlRevenue, [$id])['revenue'] ?? 0;

        // 2. Tổng chi phí vận hành tour
        $sqlCost = "SELECT SUM(amount) as total_cost FROM tour_costs WHERE tour_id = ?";
        $cost = $this->fetchOne($sqlCost, [$id])['total_cost'] ?? 0;

        // 3. Lợi nhuận
        $profit = $revenue - $cost;

        return [
            'tour_id' => $id,
            'revenue' => (float)$revenue,
            'cost'    => (float)$cost,
            'profit'  => (float)$profit
        ];
    }

    /**
     * Tìm tour theo loại (Trong nước/Quốc tế)
     */


    /**
     * Tính toán lợi nhuận (Doanh thu - Chi phí)
     */

    /**
     * Tìm tour theo loại (Trong nước/Quốc tế)
     */
    public function findByType($type)
    {
        $sql = "SELECT t.*, tc.name as category_name 
                FROM {$this->table} t 
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                WHERE t.tour_type = ? AND t.status = 'active'
                ORDER BY t.created_at DESC";
        return $this->fetchAll($sql, [$type]);
    }

    /**
     * Lấy tour theo loại với thông tin chi tiết
     */
    public function findByTypeWithDetails($type)
    {
        $sql = "SELECT t.*, tc.name as category_name 
                FROM {$this->table} t 
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                WHERE t.tour_type = ? AND t.status = 'active'
                ORDER BY t.created_at DESC";

        $tours = $this->fetchAll($sql, [$type]);

        // Thêm thông tin bổ sung cho mỗi tour
        foreach ($tours as &$tour) {
            // Lấy số booking
            $sqlBooking = "SELECT COUNT(*) as booking_count FROM bookings WHERE tour_id = ? AND status != 'cancelled'";
            $bookingResult = $this->fetchOne($sqlBooking, [$tour['id']]);
            $tour['booking_count'] = $bookingResult['booking_count'] ?? 0;

            // Lấy ảnh chính
            $sqlImage = "SELECT image_url FROM tour_images WHERE tour_id = ? AND is_main = 1 LIMIT 1";
            $imageResult = $this->fetchOne($sqlImage, [$tour['id']]);
            $tour['main_image'] = $imageResult['image_url'] ?? null;
        }

        return $tours;
    }

    /**
     * Lấy tất cả tour theo loại và danh mục
     */
    public function findByTypeAndCategory($type, $categoryId)
    {
        $sql = "SELECT t.*, tc.name as category_name 
                FROM {$this->table} t 
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                WHERE t.tour_type = ? AND t.category_id = ? AND t.status = 'active'
                ORDER BY t.created_at DESC";
        return $this->fetchAll($sql, [$type, $categoryId]);
    }

    /**
     * Đếm số tour theo loại
     */
    public function countByType($type)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE tour_type = ? AND status = 'active'";
        $result = $this->fetchOne($sql, [$type]);
        return $result['total'] ?? 0;
    }
}
