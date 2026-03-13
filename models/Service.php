<?php
class Service extends BaseModel
{
    // This model tries to abstract the variable table names used in different databases
    protected $table;
    protected $cols;

    public function __construct()
    {
        parent::__construct();
        // determine table
        $hasCosts = $this->fetchAll("SHOW TABLES LIKE 'tour_costs'");
        $this->table = !empty($hasCosts) ? 'tour_costs' : 'booking_services';
        $columnsInfo = $this->fetchAll("SHOW COLUMNS FROM {$this->table}");
        $this->cols = array_map(fn($c) => $c['Field'], $columnsInfo);
    }

    /**
     * Extract name and vendor from notes if they match pattern 'DV: ...' and 'NCC: ...'
     */
    private function extractNameVendorFromNotes($notes)
    {
        $extracted = ['name' => null, 'vendor' => null];
        if (empty($notes)) return $extracted;
        if (preg_match('/DV\s*:\s*([^|]+)/u', $notes, $m)) {
            $extracted['name'] = trim($m[1]);
        }
        if (preg_match('/NCC\s*:\s*([^|]+)/u', $notes, $m2)) {
            $extracted['vendor'] = trim($m2[1]);
        }
        return $extracted;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getCols()
    {
        return $this->cols;
    }

    public function getByTour($tourId)
    {
        $idCol = in_array('tour_id', $this->cols) ? 'tour_id' : (in_array('tourId', $this->cols) ? 'tourId' : 'tour_id');
        $sql = "SELECT * FROM {$this->table} WHERE {$idCol} = ? ORDER BY id DESC";
        $rows = $this->fetchAll($sql, [(int)$tourId]);
        // normalize fields
        foreach ($rows as &$r) {
            $r['id'] = $r['id'] ?? ($r['service_id'] ?? ($r['cost_id'] ?? ($r['bs_id'] ?? null)));
            $r['name'] = $r['name'] ?? ($r['service_name'] ?? ($r['cost_name'] ?? ($r['ServiceName'] ?? ($r['CostName'] ?? ($r['Name'] ?? '')))));
            $r['vendor'] = $r['vendor'] ?? ($r['provider'] ?? ($r['supplier'] ?? ($r['Vendor'] ?? ($r['Provider'] ?? ($r['Supplier'] ?? '')))));
            $r['notes'] = $r['notes'] ?? ($r['description'] ?? ($r['remark'] ?? ''));
            $r['amount'] = $r['amount'] ?? ($r['cost'] ?? ($r['price'] ?? 0));
            // Nếu name hoặc vendor rỗng, thử tách từ notes theo mẫu 'DV: ...' và 'NCC: ...'
            if ((!isset($r['name']) || $r['name'] === '') && !empty($r['notes'])) {
                if (preg_match('/DV\s*:\s*([^|]+)/u', $r['notes'], $m)) { $r['name'] = trim($m[1]); }
            }
            if ((!isset($r['vendor']) || $r['vendor'] === '') && !empty($r['notes'])) {
                if (preg_match('/NCC\s*:\s*([^|]+)/u', $r['notes'], $m2)) { $r['vendor'] = trim($m2[1]); }
            }
        }
        return $rows;
    }

    public function findOne($id)
    {
        // try multiple id column names
        $idCol = in_array('id', $this->cols) ? 'id' : (in_array('bs_id', $this->cols) ? 'bs_id' : (in_array('service_id', $this->cols) ? 'service_id' : (in_array('cost_id', $this->cols) ? 'cost_id' : 'id')));
        $row = $this->fetchOne("SELECT * FROM {$this->table} WHERE {$idCol} = ?", [$id]);
        if (!$row) return null;
        $row['id'] = $row[$idCol] ?? $row['id'];
        $row['name'] = $row['name'] ?? ($row['service_name'] ?? ($row['cost_name'] ?? ($row['ServiceName'] ?? ($row['CostName'] ?? ($row['Name'] ?? '')))));
        $row['vendor'] = $row['vendor'] ?? ($row['provider'] ?? ($row['supplier'] ?? ($row['Vendor'] ?? ($row['Provider'] ?? ($row['Supplier'] ?? '')))));
        $row['notes'] = $row['notes'] ?? ($row['description'] ?? ($row['remark'] ?? ''));
        $row['amount'] = $row['amount'] ?? ($row['cost'] ?? ($row['price'] ?? 0));
        // Nếu thiếu name/vendor, thử tách từ notes
        if ((empty($row['name']) || empty($row['vendor'])) && !empty($row['notes'])) {
            if (empty($row['name']) && preg_match('/DV\s*:\s*([^|]+)/u', $row['notes'], $m)) { $row['name'] = trim($m[1]); }
            if (empty($row['vendor']) && preg_match('/NCC\s*:\s*([^|]+)/u', $row['notes'], $m2)) { $row['vendor'] = trim($m2[1]); }
        }
        return $row;
    }

    public function createService($data)
    {
        // Normalize: extract name/vendor from notes if not provided
        $extracted = $this->extractNameVendorFromNotes($data['notes'] ?? '');
        $name = !empty($data['name']) ? $data['name'] : $extracted['name'];
        $vendor = !empty($data['vendor']) ? $data['vendor'] : $extracted['vendor'];
        
        $cols = $this->cols;
        $insertCols = [];
        $params = [];
        // tour_id
        if (in_array('tour_id', $cols)) { $insertCols[] = 'tour_id'; $params[] = (int)$data['tour_id']; }
        // amount variants
        if (in_array('amount', $cols)) { $insertCols[] = 'amount'; $params[] = (float)$data['amount']; }
        elseif (in_array('cost', $cols)) { $insertCols[] = 'cost'; $params[] = (float)$data['amount']; }
        elseif (in_array('price', $cols)) { $insertCols[] = 'price'; $params[] = (float)$data['amount']; }
        // name
        if (in_array('name', $cols)) { $insertCols[] = 'name'; $params[] = $name; }
        elseif (in_array('service_name', $cols)) { $insertCols[] = 'service_name'; $params[] = $name; }
        elseif (in_array('cost_name', $cols)) { $insertCols[] = 'cost_name'; $params[] = $name; }
        // vendor
        if (in_array('vendor', $cols)) { $insertCols[] = 'vendor'; $params[] = $vendor; }
        elseif (in_array('provider', $cols)) { $insertCols[] = 'provider'; $params[] = $vendor; }
        elseif (in_array('supplier', $cols)) { $insertCols[] = 'supplier'; $params[] = $vendor; }
        // notes
        $finalNotes = trim($data['notes'] ?? '');
        if (!in_array('name', $cols) && ($data['name'] ?? '') !== '') {
            $finalNotes = trim(($finalNotes !== '' ? ($finalNotes . ' | ') : '') . 'DV: ' . $data['name']);
        }
        if (!in_array('vendor', $cols) && ($data['vendor'] ?? '') !== '') {
            $finalNotes = trim(($finalNotes !== '' ? ($finalNotes . ' | ') : '') . 'NCC: ' . $data['vendor']);
        }
        if ($finalNotes !== '') {
            if (in_array('notes', $cols)) { $insertCols[] = 'notes'; $params[] = $finalNotes; }
            elseif (in_array('description', $cols)) { $insertCols[] = 'description'; $params[] = $finalNotes; }
            elseif (in_array('remark', $cols)) { $insertCols[] = 'remark'; $params[] = $finalNotes; }
        }
        // type
        if (in_array('cost_type', $cols)) { $insertCols[] = 'cost_type'; $params[] = $data['cost_type'] ?? 'service'; }
        elseif (in_array('type', $cols)) { $insertCols[] = 'type'; $params[] = $data['cost_type'] ?? 'service'; }

        if (empty($insertCols)) throw new Exception('Không có cột phù hợp để chèn dịch vụ');
        $placeholders = implode(', ', array_fill(0, count($insertCols), '?'));
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $insertCols) . ") VALUES (" . $placeholders . ")";
        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    public function updateService($id, $data)
    {
        // Normalize: extract name/vendor from notes if not provided
        $extracted = $this->extractNameVendorFromNotes($data['notes'] ?? '');
        $name = !empty($data['name']) ? $data['name'] : $extracted['name'];
        $vendor = !empty($data['vendor']) ? $data['vendor'] : $extracted['vendor'];
        
        $cols = $this->cols;
        $sets = [];
        $params = [];
        if (in_array('amount', $cols)) { $sets[] = 'amount = ?'; $params[] = (float)$data['amount']; }
        elseif (in_array('cost', $cols)) { $sets[] = 'cost = ?'; $params[] = (float)$data['amount']; }
        elseif (in_array('price', $cols)) { $sets[] = 'price = ?'; $params[] = (float)$data['amount']; }
        if (in_array('name', $cols)) { $sets[] = 'name = ?'; $params[] = $name; }
        elseif (in_array('service_name', $cols)) { $sets[] = 'service_name = ?'; $params[] = $name; }
        elseif (in_array('cost_name', $cols)) { $sets[] = 'cost_name = ?'; $params[] = $name; }
        if (in_array('vendor', $cols)) { $sets[] = 'vendor = ?'; $params[] = $vendor; }
        elseif (in_array('provider', $cols)) { $sets[] = 'provider = ?'; $params[] = $vendor; }
        elseif (in_array('supplier', $cols)) { $sets[] = 'supplier = ?'; $params[] = $vendor; }
        $finalNotes = trim($data['notes'] ?? '');
        if (!in_array('name', $cols) && ($data['name'] ?? '') !== '') {
            $finalNotes = trim(($finalNotes !== '' ? ($finalNotes . ' | ') : '') . 'DV: ' . $data['name']);
        }
        if (!in_array('vendor', $cols) && ($data['vendor'] ?? '') !== '') {
            $finalNotes = trim(($finalNotes !== '' ? ($finalNotes . ' | ') : '') . 'NCC: ' . $data['vendor']);
        }
        if ($finalNotes !== '') {
            if (in_array('notes', $cols)) { $sets[] = 'notes = ?'; $params[] = $finalNotes; }
            elseif (in_array('description', $cols)) { $sets[] = 'description = ?'; $params[] = $finalNotes; }
            elseif (in_array('remark', $cols)) { $sets[] = 'remark = ?'; $params[] = $finalNotes; }
        }
        if (in_array('cost_type', $cols)) { $sets[] = 'cost_type = ?'; $params[] = $data['cost_type'] ?? 'service'; }
        elseif (in_array('type', $cols)) { $sets[] = 'type = ?'; $params[] = $data['cost_type'] ?? 'service'; }
        if (empty($sets)) throw new Exception('Không có trường nào để cập nhật');
        $idCol = in_array('id', $cols) ? 'id' : (in_array('bs_id', $cols) ? 'bs_id' : (in_array('service_id', $cols) ? 'service_id' : (in_array('cost_id', $cols) ? 'cost_id' : 'id')));
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$idCol} = ?";
        $params[] = $id;
        return $this->query($sql, $params);
    }

    public function deleteService($id)
    {
        $cols = $this->cols;
        $idCol = in_array('id', $cols) ? 'id' : (in_array('bs_id', $cols) ? 'bs_id' : (in_array('service_id', $cols) ? 'service_id' : (in_array('cost_id', $cols) ? 'cost_id' : 'id')));
        return $this->query("DELETE FROM {$this->table} WHERE {$idCol} = ?", [$id]);
    }
}
?>