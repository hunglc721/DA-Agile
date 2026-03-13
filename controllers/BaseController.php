<?php
// controllers/BaseController.php

class BaseController {
    
    /**
     * Hàm render view
     * @param string $view Tên file view trong thư mục views/ (vd: 'login', 'admin/dashboard')
     * @param array $data Dữ liệu truyền vào view
     * @param string|bool $layout Layout cần dùng (vd: 'main'), để false nếu không dùng
     */
    protected function render($view, $data = [], $layout = 'main') {
        // 1. Giải nén dữ liệu
        extract($data);

        // 2. Tạo đường dẫn file view
        $view_file = ROOT_PATH . 'views/' . $view . '.php';

        if (!file_exists($view_file)) {
            die("Lỗi: Không tìm thấy file view '$view' tại $view_file");
        }

        // 3. Xử lý layout
        if ($layout === false) {
            // Trường hợp: Login (Không cần khung admin)
            require_once $view_file;
        } else {
            // Trường hợp: Admin (Cần khung layout)
            $layout_path = ROOT_PATH . 'views/layouts/' . $layout . '.php';
            
            if (file_exists($layout_path)) {
                require_once $layout_path;
            } else {
                die("Lỗi: Không tìm thấy layout '$layout'");
            }
        }
    }

    /**
     * Hàm chuyển hướng an toàn
     */
    protected function redirect($url) {
        $url = ltrim($url, '/');
        if (substr($url, 0, 9) === 'index.php') {
            header('Location: ' . BASE_URL . '/' . $url);
            exit();
        }

        if ($url === '' || $url === '/') {
            header('Location: ' . BASE_URL . '/index.php');
            exit();
        }

        $path = $url;
        $query = '';
        if (strpos($url, '?') !== false) {
            [$path, $query] = explode('?', $url, 2);
        }
        $target = BASE_URL . '/index.php?action=' . $path;
        if ($query !== '') {
            $target .= '&' . $query;
        }
        header('Location: ' . $target);
        exit();
    }

    /**
     * Kiểm tra đăng nhập
     */
    protected function checkLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) {
            $this->redirect('/');
        }
    }
}
?>
