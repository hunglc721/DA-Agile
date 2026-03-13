<?php
class DepartureController
{
    private $departureModel;
    private $tourModel;
    private $guideModel;

    public function __construct()
    {
        $this->departureModel = new TourDeparture();
        $this->tourModel = new Tour();
        $this->guideModel = new Guide();
    }

    public function index()
    {
        $deps = $this->departureModel->findAll([
            'from' => $_GET['from'] ?? null,
            'to' => $_GET['to'] ?? null,
            'tour_id' => $_GET['tour_id'] ?? null,
            'status' => $_GET['status'] ?? null,
        ]);
        view('main', [
            'title' => 'Quản Lý Khởi Hành',
            'page' => 'departures',
            'content_view' => 'admin/departures/index',
            'departures' => $deps,
            'tours' => $this->tourModel->findAll(['status' => 'active'])
        ]);
    }

    public function create()
    {
        $tours = $this->tourModel->findAll(['status' => 'active']);
        view('main', [
            'title' => 'Tạo Lịch Khởi Hành',
            'page' => 'departures',
            'content_view' => 'admin/departures/create',
            'tours' => $tours
        ]);
    }

    public function store()
    {
        try {
            $data = [
                'tour_id' => $_POST['tour_id'],
                'departure_date' => $_POST['departure_date'],
                'capacity' => (int)$_POST['capacity'],
                'available_slots' => (int)($_POST['available_slots'] ?? $_POST['capacity']),
                'status' => $_POST['status'] ?? 'scheduled'
            ];
            $id = $this->departureModel->create($data);
            $_SESSION['success'] = 'Tạo lịch khởi hành thành công';
            redirect('departures');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('departures_create');
        }
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID lịch khởi hành không hợp lệ';
            redirect('departures');
            return;
        }
        $dep = $this->departureModel->find($id);
        if (!$dep) {
            $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
            redirect('departures');
            return;
        }
        $tours = $this->tourModel->findAll(['status' => 'active']);
        view('main', [
            'title' => 'Sửa Lịch Khởi Hành',
            'page' => 'departures',
            'content_view' => 'admin/departures/edit',
            'departure' => $dep,
            'tours' => $tours
        ]);
    }

    public function update()
    {
        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id <= 0) throw new Exception('ID lịch khởi hành không hợp lệ');
            $this->departureModel->update($id, [
                'departure_date' => $_POST['departure_date'],
                'capacity' => (int)$_POST['capacity'],
                'available_slots' => (int)$_POST['available_slots'],
                'status' => $_POST['status']
            ]);
            $_SESSION['success'] = 'Cập nhật thành công';
            redirect('departures');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('departures');
        }
    }

    public function delete()
    {
        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id <= 0) throw new Exception('ID lịch khởi hành không hợp lệ');
            $this->departureModel->delete($id);
            $_SESSION['success'] = 'Đã xóa lịch khởi hành';
            redirect('departures');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('departures');
        }
    }

    public function assign()
    {
        $id = $_GET['id'] ?? null;
        $dep = $this->departureModel->find($id);
        if (!$dep) {
            $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
            redirect('departures');
            return;
        }
        $assignments = $this->departureModel->getAssignments($id);
        $guides = $this->guideModel->findAll(['status' => 'active']);
        $services = [];
        try { if (!empty($dep['tour_id'])) { $services = $this->tourModel->getCosts((int)$dep['tour_id']); } } catch (Throwable $e) { $services = []; }
        view('main', [
            'title' => 'Phân Bổ Nhân Sự',
            'page' => 'departures',
            'content_view' => 'admin/departures/assign',
            'departure' => $dep,
            'assignments' => $assignments,
            'guides' => $guides,
            'services' => $services
        ]);
    }

    public function storeAssignment()
    {
        try {
            $depId = (int)($_POST['departure_id'] ?? 0);
            if ($depId <= 0) throw new Exception('Lịch khởi hành không hợp lệ');
            
            $dep = $this->departureModel->find($depId);
            if (!$dep) throw new Exception('Không tìm thấy lịch khởi hành');
            
            $guideId = (int)($_POST['guide_id'] ?? 0);
            if ($guideId <= 0) throw new Exception('Hướng dẫn viên không hợp lệ');
            
            $guide = $this->guideModel->find($guideId);
            if (!$guide) throw new Exception('Không tìm thấy hướng dẫn viên');
            
            $depDate = $dep['departure_date'] ?? null;
            if (!$depDate) throw new Exception('Lịch khởi hành không có ngày');
            
            if (!$this->guideModel->isAvailableOnDate($guideId, $depDate)) {
                throw new Exception('Hướng dẫn viên đã được phân công ngày này');
            }
            
            $exists = $this->departureModel->fetchOne(
                "SELECT COUNT(*) as c FROM tour_assignments WHERE tour_id = ? AND guide_id = ? AND assignment_date = ?",
                [(int)($dep['tour_id'] ?? 0), $guideId, $depDate]
            );
            if ((int)($exists['c'] ?? 0) > 0) {
                throw new Exception('Hướng dẫn viên đã được gán cho lịch này');
            }
            
            $sql = "INSERT INTO tour_assignments (tour_id, guide_id, assignment_date, status) VALUES (?, ?, ?, ?)";
            $this->departureModel->query($sql, [(int)($dep['tour_id'] ?? 0), $guideId, $depDate, 'assigned']);
            $_SESSION['success'] = 'Phân bổ nhân sự thành công';
            redirect('departures_assign?id=' . $depId);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('departures_assign?id=' . ($_POST['departure_id'] ?? ''));
        }
    }

    public function unassign()
    {
        try {
            // Prefer deleting by assignment id if provided (safer)
            $assignId = isset($_POST['assign_id']) ? (int)$_POST['assign_id'] : 0;
            if ($assignId > 0) {
                $this->departureModel->query("DELETE FROM tour_assignments WHERE id = ?", [$assignId]);
                $_SESSION['success'] = 'Đã hủy phân bổ';
                // try to redirect back to assignments list if provided
                redirect('assignments');
                return;
            }

            // Fallback: accept departure_id + guide_id (existing behavior)
            $depId = (int)($_POST['departure_id'] ?? 0);
            $guideId = (int)($_POST['guide_id'] ?? 0);
            $dep = $this->departureModel->find($depId);
            if (!$dep) throw new Exception('Không tìm thấy lịch khởi hành');
            $this->departureModel->query(
                "DELETE FROM tour_assignments WHERE tour_id = ? AND guide_id = ? AND assignment_date = ?",
                [$dep['tour_id'], $guideId, $dep['departure_date']]
            );
            $_SESSION['success'] = 'Đã hủy phân bổ';
            redirect('departures_assign?id=' . $depId);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('departures');
        }
    }

    /**
     * Hiển thị danh sách tất cả phân bổ (tour_assignments)
     */
    public function assignments()
    {
        $sql = "SELECT ta.id as assign_id, ta.tour_id, ta.guide_id, ta.assignment_date, ta.notes as assign_notes, ta.status as assign_status,
                       t.name as tour_name, t.tour_code,
                       u.full_name as guide_name, g.status as guide_status
                FROM tour_assignments ta
                JOIN tours t ON ta.tour_id = t.id
                JOIN guides g ON ta.guide_id = g.id
                JOIN users u ON g.user_id = u.id
                ORDER BY ta.assignment_date DESC";
        $bm = $this->departureModel; // TourDeparture extends BaseModel, has query/fetchAll
        $assigns = $bm->fetchAll($sql, []);
        view('main', [
            'title' => 'Danh Sách Phân Bổ',
            'page' => 'departures',
            'content_view' => 'admin/departures/assignments',
            'assignments' => $assigns
        ]);
    }

    /**
     * Hiển thị form tạo phân bổ mới
     */
    public function assignments_create()
    {
        try {
            // Lấy danh sách lịch khởi hành
            $departures = $this->departureModel->findAll(['status' => 'scheduled']);
            
            // Lấy danh sách hướng dẫn viên
            $guides = $this->guideModel->findAll(['status' => 'active']);
            
            view('main', [
                'title' => 'Tạo Phân Bổ Mới',
                'page' => 'departures',
                'content_view' => 'admin/departures/assignments_create',
                'departures' => $departures,
                'guides' => $guides
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('assignments');
        }
    }

    /**
     * Lưu phân bổ mới
     */
    public function assignments_store()
    {
        try {
            $depId = (int)($_POST['departure_id'] ?? 0);
            if ($depId <= 0) throw new Exception('Lịch khởi hành không hợp lệ');
            
            $dep = $this->departureModel->find($depId);
            if (!$dep) throw new Exception('Không tìm thấy lịch khởi hành');
            
            $guideId = (int)($_POST['guide_id'] ?? 0);
            if ($guideId <= 0) throw new Exception('Hướng dẫn viên không hợp lệ');
            
            $guide = $this->guideModel->find($guideId);
            if (!$guide) throw new Exception('Không tìm thấy hướng dẫn viên');
            
            $depDate = $dep['departure_date'] ?? null;
            if (!$depDate) throw new Exception('Lịch khởi hành không có ngày');
            
            if (!$this->guideModel->isAvailableOnDate($guideId, $depDate)) {
                throw new Exception('Hướng dẫn viên đã được phân công ngày này');
            }
            
            $exists = $this->departureModel->fetchOne(
                "SELECT COUNT(*) as c FROM tour_assignments WHERE tour_id = ? AND guide_id = ? AND assignment_date = ?",
                [(int)($dep['tour_id'] ?? 0), $guideId, $depDate]
            );
            if ((int)($exists['c'] ?? 0) > 0) {
                throw new Exception('Hướng dẫn viên đã được gán cho lịch này');
            }
            
            $sql = "INSERT INTO tour_assignments (tour_id, guide_id, assignment_date, status) VALUES (?, ?, ?, ?)";
            $this->departureModel->query($sql, [(int)($dep['tour_id'] ?? 0), $guideId, $depDate, 'assigned']);
            
            $_SESSION['success'] = 'Phân bổ nhân sự thành công';
            redirect('assignments');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('assignments_create');
        }
    }
}
