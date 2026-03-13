<?php
class TourDeparture extends BaseModel
{
    protected $table = 'tour_departures';
    private $resolvedTable;
    private $cols;

    private function actualTable()
    {
        if ($this->resolvedTable) return $this->resolvedTable;
        $hasDepartures = $this->fetchAll("SHOW TABLES LIKE 'tour_departures'");
        if (!empty($hasDepartures)) {
            $this->resolvedTable = 'tour_departures';
        } else {
            $this->resolvedTable = 'tour_schedules';
        }
        return $this->resolvedTable;
    }

    private function getCols()
    {
        if ($this->cols) return $this->cols;
        $table = $this->actualTable();
        $columnsInfo = $this->fetchAll("SHOW COLUMNS FROM {$table}");
        $fields = array_map(function($c){return $c['Field'];}, $columnsInfo);
        $dateCol = null;
        foreach (['departure_date','schedule_date','date','start_date','DepartureDate','ScheduleDate','StartDate','Date'] as $c) { if (in_array($c, $fields)) { $dateCol = $c; break; } }
        $capacityCol = null;
        foreach (['capacity','max_capacity','slots','Capacity','MaxCapacity','Slots'] as $c) { if (in_array($c, $fields)) { $capacityCol = $c; break; } }
        $availableCol = null;
        foreach (['available_slots','slots_available','remaining_slots','AvailableSlots','SlotsAvailable','RemainingSlots'] as $c) { if (in_array($c, $fields)) { $availableCol = $c; break; } }
        $statusCol = (in_array('status', $fields) ? 'status' : (in_array('Status', $fields) ? 'Status' : null));
        $nameCol = null;
        foreach (['name','schedule_name','title','Name','ScheduleName','Title'] as $c) { if (in_array($c, $fields)) { $nameCol = $c; break; } }
        $endDateCol = null;
        foreach (['end_date','EndDate','finish_date','FinishDate'] as $c) { if (in_array($c, $fields)) { $endDateCol = $c; break; } }
        // Robust id detection: try common names, then any field containing 'id', finally fallback to first column
        $idCandidates = ['id','ID','schedule_id','scheduleID','DepartureID','departure_id','ScheduleID'];
        $idCol = null;
        foreach ($idCandidates as $cand) {
            if (in_array($cand, $fields)) { $idCol = $cand; break; }
        }
        if (!$idCol) {
            // try any field that contains 'id' (case-insensitive)
            foreach ($fields as $f) {
                if (stripos($f, 'id') !== false) { $idCol = $f; break; }
            }
        }
        if (!$idCol) {
            // last resort: use first column name
            $idCol = $fields[0] ?? 'id';
        }

        // Robust tour_id detection
        $tourCandidates = ['tour_id','tourId','TourID','tourid'];
        $tourIdCol = null;
        foreach ($tourCandidates as $cand) {
            if (in_array($cand, $fields)) { $tourIdCol = $cand; break; }
        }
        if (!$tourIdCol) {
            // try any column name containing 'tour' and 'id'
            foreach ($fields as $f) {
                if (stripos($f, 'tour') !== false && stripos($f, 'id') !== false) { $tourIdCol = $f; break; }
            }
        }
        if (!$tourIdCol) {
            $tourIdCol = 'tour_id';
        }

        $this->cols = [
            'date' => $dateCol ?: 'departure_date',
            'capacity' => $capacityCol ?: 'capacity',
            'available' => $availableCol, // có thể null nếu bảng không có cột
            'status' => $statusCol ?: 'status',
            'id' => $idCol,
            'tour_id' => $tourIdCol,
            'name' => $nameCol,
            'end_date' => $endDateCol
        ];
        return $this->cols;
    }

    public function findAll($filters = [])
    {
        $t = $this->actualTable();
        $c = $this->getCols();
        $sql = "SELECT d.*, t.name as tour_name, t.tour_code 
                FROM {$t} d 
                JOIN tours t ON d.".$c['tour_id']." = t.id 
                WHERE 1=1";
        $params = [];
        if (!empty($filters['tour_id'])) {
            $sql .= " AND d.".$c['tour_id']." = ?";
            $params[] = $filters['tour_id'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND d.".$c['status']." = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['from'])) {
            $sql .= " AND d.".$c['date']." >= ?";
            $params[] = $filters['from'];
        }
        if (!empty($filters['to'])) {
            $sql .= " AND d.".$c['date']." <= ?";
            $params[] = $filters['to'];
        }
        $sql .= " ORDER BY d.".$c['date']." ASC";
        $rows = $this->fetchAll($sql, $params);
        foreach ($rows as &$r) {
            if (empty($r)) continue;
            $r['departure_date'] = $r[$c['date']] ?? $r['departure_date'] ?? null;
            $r['capacity'] = $r[$c['capacity']] ?? $r['capacity'] ?? null;
            $availableCol = $c['available'] ?? null;
            $r['available_slots'] = $availableCol ? ($r[$availableCol] ?? ($r['available_slots'] ?? $r['capacity'])) : ($r['available_slots'] ?? $r['capacity']);
            $r['status'] = $r[$c['status']] ?? ($r['status'] ?? 'scheduled');
            $idCol = $c['id'] ?? null;
            if (!empty($idCol) && isset($r[$idCol])) {
                $r['id'] = $r[$idCol];
            }
        }
        return $rows;
    }

    public function find($id)
    {
        $t = $this->actualTable();
        $c = $this->getCols();
        $sql = "SELECT d.*, t.name as tour_name, t.tour_code 
                FROM {$t} d 
                JOIN tours t ON d.".$c['tour_id']." = t.id 
                WHERE d.".$c['id']." = ?";
        $r = $this->fetchOne($sql, [$id]);
        if ($r) {
            $r['departure_date'] = $r[$c['date']] ?? $r['departure_date'] ?? null;
            $r['capacity'] = $r[$c['capacity']] ?? $r['capacity'] ?? null;
            $availableCol = $c['available'] ?? null;
            $r['available_slots'] = $availableCol ? ($r[$availableCol] ?? ($r['available_slots'] ?? $r['capacity'])) : ($r['available_slots'] ?? $r['capacity']);
            $r['status'] = $r[$c['status']] ?? ($r['status'] ?? 'scheduled');
            $idCol = $c['id'] ?? null;
            if (!empty($idCol) && isset($r[$idCol])) {
                $r['id'] = $r[$idCol];
            }
        }
        return $r;
    }

    public function create($data)
    {
        if (empty($data['tour_id']) || empty($data['departure_date']) || empty($data['capacity'])) {
            throw new Exception('Thiếu dữ liệu bắt buộc');
        }
        if ((int)$data['capacity'] < 1) {
            throw new Exception('Sức chứa phải lớn hơn 0');
        }
        if ($this->existsForDate($data['tour_id'], $data['departure_date'])) {
            throw new Exception('Đã có lịch khởi hành cho tour này trong ngày đã chọn');
        }
        $t = $this->actualTable();
        $c = $this->getCols();
        $cols = [$c['tour_id'], $c['date'], $c['capacity']];
        $params = [(int)$data['tour_id'], $data['departure_date'], (int)$data['capacity']];
        if ($c['name']) {
            $tour = (new Tour())->find((int)$data['tour_id']);
            $autoName = ($tour ? ($tour['name'] ?? '') : '') . ' - ' . $data['departure_date'];
            $cols[] = $c['name'];
            $params[] = $autoName;
        }
        $columnsInfo = $this->fetchAll("SHOW COLUMNS FROM {$t}");
        $dateAliases = ['StartDate','DepartureDate','ScheduleDate'];
        foreach ($columnsInfo as $ci) {
            $f = $ci['Field'];
            if (in_array($f, $dateAliases) && $f !== $c['date']) {
                $cols[] = $f;
                $params[] = $data['departure_date'];
            }
        }
        if ($c['end_date']) {
            $tour = isset($tour) ? $tour : (new Tour())->find((int)$data['tour_id']);
            $duration = (int)($tour['duration'] ?? 0);
            $endDate = $data['departure_date'];
            if ($duration > 0) {
                $endDate = date('Y-m-d', strtotime($data['departure_date'] . ' + ' . ($duration - 1) . ' day'));
            }
            $cols[] = $c['end_date'];
            $params[] = $endDate;
        }
        $availableCol = $c['available'] ?? null;
        if ($availableCol) { $cols[] = $availableCol; $params[] = isset($data['available_slots']) ? (int)$data['available_slots'] : (int)$data['capacity']; }
        $statusCol = $c['status'] ?? null;
        if ($statusCol) { $cols[] = $statusCol; $params[] = $data['status'] ?? 'scheduled'; }
        $placeholders = implode(', ', array_fill(0, count($cols), '?'));
        $sql = "INSERT INTO {$t} (" . implode(', ', $cols) . ") VALUES (" . $placeholders . ")";
        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    public function update($id, $data)
    {
        if (empty($data['departure_date']) || empty($data['capacity'])) {
            throw new Exception('Thiếu dữ liệu bắt buộc');
        }
        $t = $this->actualTable();
        $c = $this->getCols();
        $sets = [ $c['date'] . ' = ?', $c['capacity'] . ' = ?' ];
        $params = [ $data['departure_date'], (int)$data['capacity'] ];
        if ($c['end_date']) {
            $tour = (new Tour())->find((int)$this->fetchOne("SELECT ".$c['tour_id']." FROM {$t} WHERE ".$c['id']." = ?", [$id])[$c['tour_id']]);
            $duration = (int)($tour['duration'] ?? 0);
            $endDate = $data['departure_date'];
            if ($duration > 0) {
                $endDate = date('Y-m-d', strtotime($data['departure_date'] . ' + ' . ($duration - 1) . ' day'));
            }
            $sets[] = $c['end_date'] . ' = ?';
            $params[] = $endDate;
        }
        $columnsInfo = $this->fetchAll("SHOW COLUMNS FROM {$t}");
        foreach ($columnsInfo as $ci) {
            $f = $ci['Field'];
            if (in_array($f, ['StartDate','DepartureDate','ScheduleDate']) && $f !== $c['date']) {
                $sets[] = $f . ' = ?';
                $params[] = $data['departure_date'];
            }
        }
        $availableCol = $c['available'] ?? null;
        if ($availableCol) { $sets[] = $availableCol . ' = ?'; $params[] = (int)$data['available_slots']; }
        $statusCol = $c['status'] ?? null;
        if ($statusCol) { $sets[] = $statusCol . ' = ?'; $params[] = $data['status']; }
        $sql = "UPDATE {$t} SET " . implode(', ', $sets) . " WHERE " . $c['id'] . " = ?";
        $params[] = $id;
        return $this->query($sql, $params);
    }

    public function delete($id)
    {
        $t = $this->actualTable();
        $c = $this->getCols();
        $sql = "DELETE FROM {$t} WHERE " . $c['id'] . " = ?";
        return $this->query($sql, [$id]);
    }

    public function getAssignments($id)
    {
        $dep = $this->find($id);
        $sql = "SELECT ta.*, g.id as guide_id, u.full_name 
                FROM tour_assignments ta 
                JOIN guides g ON ta.guide_id = g.id 
                JOIN users u ON g.user_id = u.id 
                WHERE ta.tour_id = ? AND ta.assignment_date = ?";
        return $this->fetchAll($sql, [$dep['tour_id'], $dep['departure_date']]);
    }

    public function existsForDate($tourId, $date)
    {
        $t = $this->actualTable();
        $c = $this->getCols();
        $sql = "SELECT COUNT(*) as c FROM {$t} WHERE " . $c['tour_id'] . " = ? AND " . $c['date'] . " = ?";
        $row = $this->fetchOne($sql, [(int)$tourId, $date]);
        return (int)($row['c'] ?? 0) > 0;
    }
}
?>
