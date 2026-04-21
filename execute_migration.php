<?php
try {
    // Read migration file
    $sql_content = file_get_contents('migration_add_payment_fields.sql');
    
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Select database
    $pdo->exec('USE duan1');
    echo "[OK] Connected to database duan1\n\n";
    
    // Split by semicolon and process each statement
    $parts = explode(';', $sql_content);
    
    $success_count = 0;
    $skip_count = 0;
    $error_count = 0;
    $stmt_num = 0;
    
    foreach ($parts as $part) {
        $statement = trim($part);
        // Skip empty lines and comments
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        // Remove comment lines from the statement
        $lines = explode("\n", $statement);
        $clean_lines = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && strpos($line, '--') !== 0) {
                $clean_lines[] = $line;
            }
        }
        $statement = implode(' ', $clean_lines);
        
        if (empty($statement)) continue;
        
        $stmt_num++;
        
        try {
            $pdo->exec($statement);
            echo "[OK] Statement " . $stmt_num . " executed successfully\n";
            $success_count++;
        } catch (PDOException $e) {
            // Check if it's a 'column already exists' error
            $msg = $e->getMessage();
            if (strpos($msg, 'already exists') !== false || 
                strpos($msg, 'Duplicate column name') !== false ||
                strpos($msg, '1060') !== false) {
                echo "[SKIP] Statement " . $stmt_num . " skipped (column already exists)\n";
                $skip_count++;
            } else {
                echo "[ERROR] Statement " . $stmt_num . " failed: " . $msg . "\n";
                $error_count++;
            }
        }
    }
    
    echo "\n=== MIGRATION RESULT ===\n";
    echo "Total statements: " . ($success_count + $skip_count + $error_count) . "\n";
    echo "Successful: $success_count\n";
    echo "Skipped: $skip_count\n";
    echo "Failed: $error_count\n";
    
    if ($error_count === 0) {
        echo "\n[SUCCESS] Migration completed successfully!\n";
    } else {
        echo "\n[WARNING] Migration completed with errors.\n";
    }
    
} catch (Exception $e) {
    echo "[FATAL ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
?>
