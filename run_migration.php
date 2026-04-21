<?php
define('BASE_URL', 'http://localhost/Agile/');
require_once 'configs/env.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
    
    $migrations = [
        "ALTER TABLE `bookings` ADD COLUMN `payment_status` ENUM('unpaid', 'deposit_paid', 'partially_paid', 'paid') DEFAULT 'unpaid' AFTER `status`",
        "ALTER TABLE `bookings` ADD COLUMN `deposit_amount` DECIMAL(10,2) DEFAULT 0 AFTER `payment_status`",
        "ALTER TABLE `bookings` ADD COLUMN `paid_amount` DECIMAL(10,2) DEFAULT 0 AFTER `deposit_amount`",
        "ALTER TABLE `bookings` ADD COLUMN `payment_date` DATETIME DEFAULT NULL AFTER `paid_amount`",
        "ALTER TABLE `bookings` ADD COLUMN `payment_method` VARCHAR(50) DEFAULT NULL AFTER `payment_date`",
        "ALTER TABLE `bookings` ADD COLUMN `transaction_id` VARCHAR(100) DEFAULT NULL AFTER `payment_method`",
        "ALTER TABLE `bookings` ADD COLUMN `receipt_url` VARCHAR(255) DEFAULT NULL AFTER `transaction_id`",
        "ALTER TABLE `bookings` ADD INDEX `idx_payment_status` (`payment_status`)",
        "ALTER TABLE `bookings` ADD INDEX `idx_transaction_id` (`transaction_id`)",
        "UPDATE `bookings` SET `payment_status` = 'paid', `paid_amount` = `total_price` WHERE `status` IN ('confirmed', 'completed')"
    ];
    
    echo "Starting migration...\n";
    $success = 0;
    $skipped = 0;
    $error = 0;
    
    foreach ($migrations as $sql) {
        try {
            $pdo->exec($sql);
            echo "✓ OK: " . substr($sql, 0, 50) . "...\n";
            $success++;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate column') || strpos($e->getMessage(), 'already exists')) {
                echo "⊘ SKIP: " . substr($sql, 0, 50) . "... (already exists)\n";
                $skipped++;
            } else {
                echo "✗ ERROR: " . substr($sql, 0, 50) . "...\n";
                echo "  Message: " . $e->getMessage() . "\n";
                $error++;
            }
        }
    }
    
    echo "\n=== MIGRATION RESULT ===\n";
    echo "Success: $success\n";
    echo "Skipped: $skipped\n";
    echo "Errors: $error\n";
    echo "Total: " . ($success + $skipped + $error) . "\n";
    
} catch (Exception $e) {
    echo "Connection Error: " . $e->getMessage() . "\n";
}
