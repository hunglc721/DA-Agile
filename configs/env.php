<?php
define('BASE_APP_URL', '/DA-Agile-main/');

define('PATH_ROOT', __DIR__ . '/../');
define('PATH_VIEW', PATH_ROOT . 'views/');

// --- SỬA DÒNG NÀY ---
// Thêm '/layouts' vào đường dẫn
define('PATH_VIEW_MAIN', PATH_ROOT . 'views/layouts/main.php');
// --------------------

define('BASE_ASSETS_UPLOADS', BASE_URL . '/assets/uploads/');
define('PATH_ASSETS_UPLOADS', PATH_ROOT . 'assets/uploads/');
define('PATH_CONTROLLER', PATH_ROOT . 'controllers/');
define('PATH_MODEL', PATH_ROOT . 'models/');

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'duan1');
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// User roles for future use
define('ROLE_ADMIN', 'admin');
define('ROLE_GUIDE', 'guide');

if (!function_exists('asset')) {
    function asset($path)
    {
        return BASE_URL . '/assets/' . ltrim($path, '/');
    }
}
