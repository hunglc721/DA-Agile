<?php
class ServiceController
{
    private $tourModel;

    private $serviceModel;

    public function __construct()
    {
        $this->tourModel = new Tour();
        $this->serviceModel = new Service();
    }

    public function index()
    {
        // Lấy danh sách tour trước để có mặc định nếu thiếu tour_id
        $tours = $this->tourModel->findAll(['status' => 'active']);
        $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : null;
        if ($tourId === null && !empty($tours)) {
            $tourId = (int)$tours[0]['id'];
        }

        $services = [];
        if ($tourId !== null) {
            try {
                // prefer new Service model
                $services = $this->serviceModel->getByTour($tourId);
            } catch (Exception $e) {
                $services = [];
                $_SESSION['error'] = 'Không thể tải danh sách dịch vụ: ' . $e->getMessage();
            }
        }

        view('main', [
            'title' => 'Quản Lý Dịch Vụ',
            'page' => 'services',
            'content_view' => 'admin/services/index',
            'tours' => $tours,
            'selectedTourId' => $tourId,
            'services' => $services
        ]);
    }

    public function store()
    {
        try {
            // Basic validation
            $tourId = isset($_POST['tour_id']) ? (int)$_POST['tour_id'] : 0;
            $name = trim((string)($_POST['name'] ?? ''));
            $amount = $_POST['amount'] ?? null;
            if ($tourId <= 0) throw new Exception('Tour không hợp lệ');
            if ($name === '') throw new Exception('Tên dịch vụ không được để trống');
            if (!is_numeric($amount) || (float)$amount < 0) throw new Exception('Chi phí không hợp lệ');
            // use Service model to create
            $this->serviceModel->createService([
                'tour_id' => $tourId,
                'name' => $name,
                'amount' => (float)$amount,
                'vendor' => trim((string)($_POST['vendor'] ?? '')),
                'notes' => trim((string)($_POST['notes'] ?? '')),
                'cost_type' => $_POST['cost_type'] ?? 'service'
            ]);
            $_SESSION['success'] = 'Thêm dịch vụ thành công';
            redirect('services?tour_id=' . $_POST['tour_id']);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('services?tour_id=' . ($_POST['tour_id'] ?? ''));
        }
    }

    public function delete()
    {
        try {
            $this->serviceModel->deleteService((int)$_POST['id']);
            $_SESSION['success'] = 'Đã xóa dịch vụ';
            redirect('services?tour_id=' . ($_POST['tour_id'] ?? ''));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('services');
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;
        $row = $this->serviceModel->findOne($id);
        if (!$row) { $_SESSION['error'] = 'Không tìm thấy dịch vụ'; redirect('services?tour_id=' . $tourId); }
        view('main', [
            'title' => 'Sửa Dịch Vụ',
            'page' => 'services',
            'content_view' => 'admin/services/edit',
            'service' => $row,
            'selectedTourId' => $tourId,
            'tours' => $this->tourModel->findAll(['status' => 'active'])
        ]);
    }

    public function update()
    {
        try {
            // Basic validation
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $tourId = isset($_POST['tour_id']) ? (int)$_POST['tour_id'] : 0;
            $name = trim((string)($_POST['name'] ?? ''));
            $amount = $_POST['amount'] ?? null;
            if ($id <= 0) throw new Exception('Dịch vụ không hợp lệ');
            if ($tourId <= 0) throw new Exception('Tour không hợp lệ');
            if ($name === '') throw new Exception('Tên dịch vụ không được để trống');
            if (!is_numeric($amount) || (float)$amount < 0) throw new Exception('Chi phí không hợp lệ');
            // use Service model to update
            $this->serviceModel->updateService((int)$_POST['id'], [
                'name' => $_POST['name'],
                'amount' => (float)$_POST['amount'],
                'vendor' => $_POST['vendor'] ?? null,
                'notes' => $_POST['notes'] ?? null,
                'cost_type' => $_POST['cost_type'] ?? 'service'
            ]);
            $_SESSION['success'] = 'Cập nhật dịch vụ thành công';
            redirect('services?tour_id=' . ($_POST['tour_id'] ?? ''));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('services?tour_id=' . ($_POST['tour_id'] ?? ''));
        }
    }
}
?>
