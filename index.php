<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// index.php
ob_start();
define('ROOT_PATH', __DIR__ . '/');

// Cấu hình hiển thị lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . 'logs/php_errors.log');

session_start();

// Tự động xác định BASE_URL để xử lý đường dẫn tài nguyên (CSS, JS, Images)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = rtrim($protocol . $host . $scriptName, '/') . '/';
define('BASE_URL', $baseUrl);

// 1. Load Config & Helper
require_once ROOT_PATH . 'configs/env.php';
require_once ROOT_PATH . 'configs/helper.php';

// 2. Load Models
require_once ROOT_PATH . 'models/BaseModel.php';
require_once ROOT_PATH . 'models/Auth.php';
require_once ROOT_PATH . 'models/User.php';
require_once ROOT_PATH . 'models/Tour.php';
require_once ROOT_PATH . 'models/Booking.php';
require_once ROOT_PATH . 'models/TourDiary.php';
require_once ROOT_PATH . 'models/TourCategory.php';
require_once ROOT_PATH . 'models/TourType.php';
require_once ROOT_PATH . 'models/TourImage.php';
require_once ROOT_PATH . 'models/Report.php';

// 3. Load Controllers (BaseController)
if (file_exists(ROOT_PATH . 'controllers/BaseController.php')) {
    require_once ROOT_PATH . 'controllers/BaseController.php';
}

// 4. Gọi routes
require_once ROOT_PATH . 'routes/index.php';


?>
