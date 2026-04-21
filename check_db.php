<?php
define('BASE_URL', 'http://localhost/Agile/');
define('ROOT_PATH', __DIR__ . '/');
require_once 'configs/env.php';

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);

try {
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
    echo "DB connection successful\n";
} catch (PDOException $e) {
    echo 'DB connection failed: ' . $e->getMessage() . "\n";
    exit;
}

require_once 'models/BaseModel.php';
require_once 'controllers/BaseController.php';
require_once 'controllers/PaymentController.php';

try {
    $pc = new PaymentController();
    echo 'PaymentController created successfully' . "\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>