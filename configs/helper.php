<?php
// configs/helper.php

if (!function_exists('debug')) {
    function debug($data) {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($folder, $file) {
        $targetFile = $folder . '/' . time() . '-' . $file["name"];
        if (move_uploaded_file($file["tmp_name"], PATH_ASSETS_UPLOADS . $targetFile)) {
            return $targetFile;
        }
        throw new Exception('Upload file không thành công!');
    }
}

if (!function_exists('view')) {
    function view($viewPath, $data = []) {
        extract($data);
        // Để login và register không có layout
        if ($viewPath === 'login' || $viewPath === 'register') {
            $filePath = ROOT_PATH . 'views/' . $viewPath . '.php';
            if (file_exists($filePath)) {
                require_once $filePath;
            } else {
                die("Không tìm thấy file: $filePath");
            }
        } else if (strpos($viewPath, 'client/') === 0) {
            // Client pages (dashboard, profile, atau static HTML pages)
            $phpFilePath = ROOT_PATH . 'views/' . $viewPath . '.php';
            $htmlFilePath = ROOT_PATH . 'views/' . $viewPath . '.html';

            if (file_exists($phpFilePath)) {
                // PHP files - use layout-client for dashboard/profile, direct render for index
                if ($viewPath === 'client/index') {
                    // Homepage - render directly without layout wrapper
                    require_once $phpFilePath;
                } else {
                    // Dashboard/Profile - use layout-client
                    $contentPath = $phpFilePath;
                    ob_start();
                    require_once ROOT_PATH . 'views/layouts/layout-client.php';
                    echo ob_get_clean();
                }
            } else if (file_exists($htmlFilePath)) {
                // Static HTML files - inject navbar
                $html = file_get_contents($htmlFilePath);
                $html = preg_replace('/<nav[^>]*>.*?<\/nav>\s*<!-- END nav -->/is', '', $html);

                ob_start();
                require_once ROOT_PATH . 'views/layouts/navbar-client.php';
                echo $html;
                $output = ob_get_clean();

                // Inject CSS fix
                $fontFixCSS = '<link rel="stylesheet" href="' . BASE_URL . 'assets/css/frontend/fonts-fix.css">';
                $output = str_replace('</head>', $fontFixCSS . "\n</head>", $output);
                echo $output;
            } else {
                die("Không tìm thấy file: $phpFilePath hoặc $htmlFilePath");
            }
        } else {
            // Các view khác sử dụng layout main
            require_once PATH_VIEW_MAIN;
        }
    }
}

// --- QUAN TRỌNG: SỬA HÀM REDIRECT ---
if (!function_exists('redirect')) {
    function redirect($url) {
        $url = ltrim($url, '/');

        // Trường hợp truyền trực tiếp 'index.php...' thì chuyển hướng nguyên trạng theo BASE_URL
        if (substr($url, 0, 9) === 'index.php') {
            header('Location: ' . BASE_URL . $url);
            exit();
        }

        // Trang chủ
        if ($url === '' || $url === '/') {
            header('Location: ' . BASE_URL . 'index.php');
            exit();
        }

        // Tách path và query nếu có
        $path = $url;
        $query = '';
        if (strpos($url, '?') !== false) {
            [$path, $query] = explode('?', $url, 2);
        }

        $target = BASE_URL . 'index.php?action=' . $path;
        if ($query !== '') {
            $target .= '&' . $query;
        }

        header('Location: ' . $target);
        exit();
    }
}

// --- QUAN TRỌNG: SỬA HÀM URL ---
if (!function_exists('url')) {
    function url($path = '') {
        $path = ltrim($path, '/');

        if (empty($path)) {
            return BASE_URL . 'index.php';
        }

        // Hỗ trợ query khi truyền vào dạng 'route?key=value'
        if (strpos($path, '?') !== false) {
            [$p, $q] = explode('?', $path, 2);
            $url = BASE_URL . 'index.php?action=' . $p;
            if ($q !== '') { $url .= '&' . $q; }
            return $url;
        }

        // Trả về đường dẫn dạng ?action=...
        return BASE_URL . 'index.php?action=' . $path;
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return BASE_URL . 'assets/' . ltrim($path, '/');
    }
}

if (!function_exists('active_menu')) {
    function active_menu($current, $expected) {
        return $current === $expected ? 'active' : '';
    }
}

if (!function_exists('escape')) {
    function escape($data) {
        if (is_array($data)) {
            return array_map('escape', $data);
        }
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('clean')) {
    function clean($data) {
        if (is_array($data)) {
            return array_map('clean', $data);
        }
        return htmlspecialchars(trim(stripslashes((string)$data)), ENT_QUOTES, 'UTF-8');
    }
}

// ==================== AUTHENTICATION HELPERS ====================

/**
 * Lấy user hiện tại từ session
 */
if (!function_exists('getCurrentUser')) {
    function getCurrentUser() {
        return $_SESSION['user'] ?? null;
    }
}

/**
 * Kiểm tra đã đăng nhập
 */
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }
}

/**
 * Kiểm tra là Admin
 */
if (!function_exists('isAdmin')) {
    function isAdmin() {
        if (!isLoggedIn()) return false;
        return isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'] == 1;
    }
}

/**
 * Kiểm tra là Guide/HDV
 */
if (!function_exists('isGuide')) {
    function isGuide() {
        if (!isLoggedIn()) return false;
        $userId = $_SESSION['user']['id'];
        $guideModel = new Guide();
        $sql = "SELECT id FROM guides WHERE user_id = ? LIMIT 1";
        $guide = $guideModel->fetchOne($sql, [$userId]);
        return !empty($guide);
    }
}

/**
 * Kiểm tra là Client/User thường
 */
if (!function_exists('isClient')) {
    function isClient() {
        if (!isLoggedIn()) return false;
        // User thường là người không phải admin và không phải guide
        return !isAdmin() && !isGuide();
    }
}

/**
 * Yêu cầu đăng nhập
 */
if (!function_exists('requireAuth')) {
    function requireAuth() {
        if (!isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
            redirect('login');
            exit;
        }
    }
}

/**
 * Yêu cầu Admin
 */
if (!function_exists('requireAdmin')) {
    function requireAdmin() {
        if (!isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
            redirect('login');
            exit;
        }
        if (!isAdmin()) {
            die('<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này. Chỉ Admin có thể truy cập.</p>');
        }
    }
}

/**
 * Yêu cầu Guide/HDV
 */
if (!function_exists('requireGuide')) {
    function requireGuide() {
        if (!isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
            redirect('login');
            exit;
        }
        if (!isGuide()) {
            die('<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này. Chỉ Hướng Dẫn Viên có thể truy cập.</p>');
        }
    }
}

/**
 * Yêu cầu Client/User thường
 */
if (!function_exists('requireClient')) {
    function requireClient() {
        if (!isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
            redirect('login');
            exit;
        }
        if (!isClient()) {
            die('<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này.</p>');
        }
    }
}

/**
 * Kiểm tra xem user có phải là chủ sở hữu tài nguyên không
 */
if (!function_exists('isOwner')) {
    function isOwner($resourceUserId) {
        if (!isLoggedIn()) return false;
        return $_SESSION['user']['id'] == $resourceUserId;
    }
}

/**
 * Lấy tên loại user
 */
if (!function_exists('getUserType')) {
    function getUserType() {
        if (!isLoggedIn()) return 'guest';
        if (isAdmin()) return 'admin';
        if (isGuide()) return 'guide';
        return 'client';
    }
}

/**
 * Lấy user info đầy đủ
 */
if (!function_exists('getUserInfo')) {
    function getUserInfo() {
        $user = getCurrentUser();
        if (!$user) return null;

        return [
            'id' => $user['id'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'type' => getUserType()
        ];
    }
}
?>

