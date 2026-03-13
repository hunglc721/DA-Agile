<?php
class Report extends BaseModel
{
    protected $table = 'tours';

    /**
     * Lấy báo cáo tài chính tour
     * Tính doanh thu, chi phí, lợi nhuận cho từng tour
     */
    public function getTourFinancialReport($filters = [])
    {
        // Query đơn giản - không dùng LEFT JOIN aggregation phức tạp
        $sql = "SELECT 
                    t.id,
                    t.tour_code,
                    t.name as tour_name,
                    tc.name as category_name,
                    t.tour_type,
                    t.price as unit_price,
                    t.cost_price as unit_cost,
                    0 as total_bookings,
                    0 as confirmed_bookings,
                    0 as revenue,
                    0 as total_cost,
                    0 as profit
                FROM {$this->table} t
                LEFT JOIN tour_categories tc ON t.category_id = tc.id
                WHERE t.status = 'active'";

        $params = [];

        // Filter by category
        if (!empty($filters['category_id'])) {
            $sql .= " AND t.category_id = ?";
            $params[] = $filters['category_id'];
        }

        // Filter by tour type
        if (!empty($filters['tour_type'])) {
            $sql .= " AND t.tour_type = ?";
            $params[] = $filters['tour_type'];
        }

        $sql .= " ORDER BY t.id DESC";

        $result = $this->fetchAll($sql, $params);
        
        // Tính toán doanh thu, chi phí, lợi nhuận từ bookings
        if ($result) {
            foreach ($result as &$tour) {
                $bookingSql = "SELECT COUNT(*) as total, 
                                     COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed,
                                     SUM(CASE WHEN status = 'confirmed' THEN total_price ELSE 0 END) as revenue
                              FROM bookings
                              WHERE tour_id = ?";
                
                // Thêm filter ngày cho bookings
                if (!empty($filters['from_date'])) {
                    $bookingSql .= " AND DATE(created_at) >= ?";
                }
                if (!empty($filters['to_date'])) {
                    $bookingSql .= " AND DATE(created_at) <= ?";
                }
                
                $bookingParams = [$tour['id']];
                if (!empty($filters['from_date'])) {
                    $bookingParams[] = $filters['from_date'];
                }
                if (!empty($filters['to_date'])) {
                    $bookingParams[] = $filters['to_date'];
                }
                
                $booking = $this->fetchOne($bookingSql, $bookingParams);
                
                $tour['total_bookings'] = $booking['total'] ?? 0;
                $tour['confirmed_bookings'] = $booking['confirmed'] ?? 0;
                $tour['revenue'] = $booking['revenue'] ?? 0;
                $tour['total_cost'] = ($tour['total_bookings'] ?? 0) * ($tour['unit_cost'] ?? 0);
                $tour['profit'] = ($tour['revenue'] ?? 0) - ($tour['total_cost'] ?? 0);
            }
        }
        
        return $result;
    }

    /**
     * Lấy tổng hợp báo cáo
     */
    public function getReportSummary($filters = [])
    {
        // Lấy danh sách tất cả tour với filter
        $tours = $this->getTourFinancialReport($filters);
        
        // Tính tổng
        $summary = [
            'total_tours' => count($tours),
            'total_bookings' => 0,
            'confirmed_bookings' => 0,
            'total_revenue' => 0,
            'total_cost' => 0,
            'total_profit' => 0
        ];
        
        foreach ($tours as $tour) {
            $summary['total_bookings'] += $tour['total_bookings'] ?? 0;
            $summary['confirmed_bookings'] += $tour['confirmed_bookings'] ?? 0;
            $summary['total_revenue'] += $tour['revenue'] ?? 0;
            $summary['total_cost'] += $tour['total_cost'] ?? 0;
            $summary['total_profit'] += $tour['profit'] ?? 0;
        }
        
        return $summary;
    }
}
?>
