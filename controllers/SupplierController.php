<?php
class SupplierController
{
    private $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new Supplier();
    }

    /**
     * Hiển thị danh sách nhà cung cấp
     */
    public function index()
    {
        try {
            $filters = [
                'status' => $_GET['status'] ?? null,
                'search' => $_GET['search'] ?? null,
            ];

            $suppliers = $this->supplierModel->findAll($filters);

            view('main', [
                'title' => 'Quản Lý Nhà Cung Cấp',
                'page' => 'suppliers',
                'content_view' => 'suppliers/index',
                'suppliers' => $suppliers,
                'filters' => $filters
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('/');
        }
    }

    /**
     * Hiển thị form tạo nhà cung cấp
     */
    public function create()
    {
        try {
            view('main', [
                'title' => 'Thêm Nhà Cung Cấp Mới',
                'page' => 'suppliers',
                'content_view' => 'suppliers/create'
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=suppliers');
        }
    }

    /**
     * Lưu nhà cung cấp mới
     */
    public function store()
    {
        try {
            $data = $_POST;

            // Validate
            if (empty($data['name'])) {
                throw new Exception('Vui lòng nhập tên nhà cung cấp');
            }

            $errors = $this->supplierModel->validate($data);
            if (!empty($errors)) {
                throw new Exception(implode(', ', $errors));
            }

            // Kiểm tra email
            if (!empty($data['email']) && $this->supplierModel->checkEmailExists($data['email'])) {
                throw new Exception('Email đã tồn tại');
            }

            $this->supplierModel->create($data);
            $_SESSION['success'] = 'Thêm nhà cung cấp thành công';
            redirect('index.php?action=suppliers');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=suppliers_create');
        }
    }

    /**
     * Hiển thị chi tiết nhà cung cấp
     */
    public function show()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('Nhà cung cấp không tồn tại');
            }

            $supplier = $this->supplierModel->findWithTours($id);
            if (!$supplier) {
                throw new Exception('Nhà cung cấp không tồn tại');
            }

            view('main', [
                'title' => 'Chi Tiết Nhà Cung Cấp',
                'page' => 'suppliers',
                'content_view' => 'suppliers/show',
                'supplier' => $supplier
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=suppliers');
        }
    }

    /**
     * Hiển thị form sửa nhà cung cấp
     */
    public function edit()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('Nhà cung cấp không tồn tại');
            }

            $supplier = $this->supplierModel->find($id);
            if (!$supplier) {
                throw new Exception('Nhà cung cấp không tồn tại');
            }

            view('main', [
                'title' => 'Chỉnh Sửa Nhà Cung Cấp',
                'page' => 'suppliers',
                'content_view' => 'suppliers/edit',
                'supplier' => $supplier
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=suppliers');
        }
    }

    /**
     * Cập nhật nhà cung cấp
     */
    public function update()
    {
        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new Exception('Nhà cung cấp không tồn tại');
            }

            $data = $_POST;

            // Validate
            if (empty($data['name'])) {
                throw new Exception('Vui lòng nhập tên nhà cung cấp');
            }

            $errors = $this->supplierModel->validate($data);
            if (!empty($errors)) {
                throw new Exception(implode(', ', $errors));
            }

            // Kiểm tra email
            if (!empty($data['email']) && $this->supplierModel->checkEmailExists($data['email'], $id)) {
                throw new Exception('Email đã tồn tại');
            }

            $this->supplierModel->update($id, $data);
            $_SESSION['success'] = 'Cập nhật nhà cung cấp thành công';
            redirect('index.php?action=suppliers_show&id=' . $id);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=suppliers_edit&id=' . $_POST['id']);
        }
    }

    /**
     * Xóa nhà cung cấp
     */
    public function delete()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('Nhà cung cấp không tồn tại');
            }

            // Kiểm tra xem có tour nào của supplier này không
            $tourCount = $this->supplierModel->getTourCount($id);
            if ($tourCount > 0) {
                throw new Exception("Không thể xóa nhà cung cấp này vì có $tourCount tour đang sử dụng");
            }

            $this->supplierModel->delete($id);
            $_SESSION['success'] = 'Xóa nhà cung cấp thành công';
            redirect('index.php?action=suppliers');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('index.php?action=suppliers');
        }
    }
}
?>
