<?php
try {
    // Read migration file
    $sql_content = file_get_contents('migration_add_payment_fields.sql');
    
    echo "=== SQL STATEMENTS PARSING DEBUG ===\n\n";
    
    // Split by semicolon and process each statement
    $parts = explode(';', $sql_content);
    
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
        echo "Statement $stmt_num:\n";
        echo "  " . substr($statement, 0, 100) . (strlen($statement) > 100 ? '...' : '') . "\n\n";
    }
    
} catch (Exception $e) {
    echo "[FATAL ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
?>
