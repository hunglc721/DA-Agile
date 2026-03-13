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
        // Để login không có layout
        if ($viewPath === 'login') {
            $loginPath = ROOT_PATH . 'views/login.php';
            if (file_exists($loginPath)) {
                require_once $loginPath;
            } else {
                die("Không tìm thấy file: $loginPath");
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
?>
