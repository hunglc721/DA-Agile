<?php
define('BASE_URL', 'http://localhost/Agile/');
require_once 'configs/env.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    echo "Database " . DB_NAME . " created or already exists.\n";
    
    // Select database
    $pdo->exec("USE " . DB_NAME);
    
    // Read and execute SQL file
    $sql = file_get_contents('duan1 (2).sql');
    
    // Split by semicolon
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            try {
                $pdo->exec($statement);
                echo "Executed: " . substr($statement, 0, 50) . "...\n";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "Import completed.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>