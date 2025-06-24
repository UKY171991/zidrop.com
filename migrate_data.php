<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Starting MySQL to SQLite data migration...\n";

// Read the SQL dump file
$sqlFile = 'database/a49f80b2_zip (1).sql';
$sqlContent = file_get_contents($sqlFile);

// Clean up the SQL content and normalize line endings
$sqlContent = str_replace(["\r\n", "\r"], "\n", $sqlContent);

// Split into statements, but be smarter about it
$statements = [];
$currentStatement = '';
$inString = false;
$stringChar = '';

for ($i = 0; $i < strlen($sqlContent); $i++) {
    $char = $sqlContent[$i];
    
    if (!$inString && ($char === "'" || $char === '"')) {
        $inString = true;
        $stringChar = $char;
    } elseif ($inString && $char === $stringChar) {
        // Check if it's escaped
        if ($i + 1 < strlen($sqlContent) && $sqlContent[$i + 1] === $stringChar) {
            $currentStatement .= $char . $char;
            $i++; // Skip next character
            continue;
        } else {
            $inString = false;
        }
    }
    
    if (!$inString && $char === ';') {
        $statements[] = trim($currentStatement);
        $currentStatement = '';
    } else {
        $currentStatement .= $char;
    }
}

// Add the last statement if it doesn't end with semicolon
if (!empty(trim($currentStatement))) {
    $statements[] = trim($currentStatement);
}

$insertCount = 0;
$errorCount = 0;
$processedTables = [];

/**
 * Parse INSERT statement and extract table name, columns, and values
 */
function parseInsertStatement($statement) {
    // Normalize whitespace and newlines
    $statement = preg_replace('/\s+/', ' ', $statement);
    
    // Match INSERT INTO pattern with table name and columns
    if (!preg_match('/INSERT INTO [`"]?([^`"\s(]+)[`"]?\s*\(([^)]+)\)\s*VALUES\s*(.+)/i', $statement, $matches)) {
        return false;
    }
    
    $tableName = trim($matches[1]);
    $columnsStr = $matches[2];
    $valuesStr = $matches[3];
    
    // Parse columns
    $columns = array_map(function($col) {
        return trim(str_replace(['`', '"'], '', $col));
    }, explode(',', $columnsStr));
    
    // Parse values - handle multiple value sets
    $allValues = [];
    
    // Find all value sets in parentheses
    if (preg_match_all('/\(([^)]*(?:\([^)]*\)[^)]*)*)\)/', $valuesStr, $valueMatches)) {
        foreach ($valueMatches[1] as $valueSet) {
            $values = parseValueSet($valueSet);
            if ($values !== false && count($values) === count($columns)) {
                $allValues[] = array_combine($columns, $values);
            }
        }
    }
    
    return [
        'table' => $tableName,
        'columns' => $columns,
        'values' => $allValues
    ];
}

/**
 * Parse individual value set from INSERT statement
 */
function parseValueSet($valueStr) {
    $values = [];
    $i = 0;
    $len = strlen($valueStr);
    
    while ($i < $len) {
        // Skip whitespace and commas
        while ($i < $len && in_array($valueStr[$i], [' ', ',', "\t", "\n"])) {
            $i++;
        }
        
        if ($i >= $len) break;
        
        if ($valueStr[$i] === "'") {
            // Parse quoted string
            $i++; // Skip opening quote
            $value = '';
            while ($i < $len) {
                if ($valueStr[$i] === "'") {
                    if ($i + 1 < $len && $valueStr[$i + 1] === "'") {
                        // Escaped quote
                        $value .= "'";
                        $i += 2;
                    } else {
                        // End of string
                        $i++;
                        break;
                    }
                } else if ($valueStr[$i] === "\\") {
                    // Handle escape sequences
                    if ($i + 1 < $len) {
                        $next = $valueStr[$i + 1];
                        switch ($next) {
                            case 'n': $value .= "\n"; break;
                            case 'r': $value .= "\r"; break;
                            case 't': $value .= "\t"; break;
                            case '\\': $value .= "\\"; break;
                            case "'": $value .= "'"; break;
                            case '"': $value .= '"'; break;
                            default: $value .= $next; break;
                        }
                        $i += 2;
                    } else {
                        $value .= $valueStr[$i];
                        $i++;
                    }
                } else {
                    $value .= $valueStr[$i];
                    $i++;
                }
            }
            $values[] = $value;
        } else {
            // Parse unquoted value (NULL, number, etc.)
            $value = '';
            $parenDepth = 0;
            
            while ($i < $len) {
                $char = $valueStr[$i];
                
                if ($char === '(') {
                    $parenDepth++;
                } elseif ($char === ')') {
                    $parenDepth--;
                } elseif ($char === ',' && $parenDepth === 0) {
                    break;
                }
                
                $value .= $char;
                $i++;
            }
            
            $value = trim($value);            if (strtoupper($value) === 'NULL') {
                $values[] = null;
            } else if (is_numeric($value)) {
                $values[] = $value;
            } else if (preg_match('/^[\'\"].*[\'\"]$/', $value)) {
                // Handle quoted values that might have been missed
                $values[] = substr($value, 1, -1);
            } else if (preg_match('/^`[^`]+`$/', $value)) {
                // This looks like a column name, treat as NULL
                $values[] = null;
            } else if (trim($value) === '') {
                // Empty value, treat as NULL
                $values[] = null;
            } else {
                $values[] = $value;
            }
        }
    }
    
    return $values;
}

/**
 * Insert data using parameterized queries
 */
function insertData($tableName, $data) {
    if (empty($data)) return 0;    $inserted = 0;
    foreach ($data as $row) {        // Handle special cases for required fields FIRST, before any DB operations
        switch ($tableName) {            case 'parcelnotes':
                if (array_key_exists('note', $row) && (is_null($row['note']) || $row['note'] === '' || $row['note'] === 'NULL')) {
                    $row['note'] = 'No note provided';
                }
                if (array_key_exists('parcelId', $row) && (is_null($row['parcelId']) || $row['parcelId'] === '' || $row['parcelId'] === 'NULL')) {
                    continue 2; // Skip this record entirely as parcelId is critical
                }
                break;case 'parcels':
                if (array_key_exists('invoiceNo', $row) && (is_null($row['invoiceNo']) || $row['invoiceNo'] === '' || $row['invoiceNo'] === 'NULL')) {
                    $row['invoiceNo'] = 'INV_' . time() . '_' . rand(1000, 9999);
                }
                if (array_key_exists('merchantId', $row) && (is_null($row['merchantId']) || $row['merchantId'] === '' || $row['merchantId'] === 'NULL')) {
                    $row['merchantId'] = 1;
                }
                if (array_key_exists('recipientName', $row) && (is_null($row['recipientName']) || $row['recipientName'] === '' || $row['recipientName'] === 'NULL')) {
                    $row['recipientName'] = 'Unknown Recipient';
                }
                if (array_key_exists('recipientPhone', $row) && (is_null($row['recipientPhone']) || $row['recipientPhone'] === '' || $row['recipientPhone'] === 'NULL')) {
                    $row['recipientPhone'] = '0000000000';
                }
                if (array_key_exists('orderType', $row) && (is_null($row['orderType']) || $row['orderType'] === '' || $row['orderType'] === 'NULL')) {
                    $row['orderType'] = 0; // Default order type
                }
                if (array_key_exists('codType', $row) && (is_null($row['codType']) || $row['codType'] === '' || $row['codType'] === 'NULL')) {
                    $row['codType'] = 0;
                }
                if (array_key_exists('status', $row) && (is_null($row['status']) || $row['status'] === '' || $row['status'] === 'NULL')) {
                    $row['status'] = 1;
                }
                if (array_key_exists('payment_option', $row) && (is_null($row['payment_option']) || $row['payment_option'] === '' || $row['payment_option'] === 'NULL')) {
                    $row['payment_option'] = 1;
                }
                break;
                  case 'merchantpayments':
                if (array_key_exists('merchantId', $row) && (is_null($row['merchantId']) || $row['merchantId'] === '' || $row['merchantId'] === 'NULL')) {
                    $row['merchantId'] = 1;
                }
                break;
        }
        
        try {
            // Clean up any malformed data
            foreach ($row as $key => $value) {
                // Skip column names that accidentally got into values
                if ($value === "`$key`" || $value === $key) {
                    $row[$key] = null;
                }
                
                // Convert datetime functions to actual values
                if ($value instanceof \Illuminate\Database\Query\Expression) {
                    if (strpos($value->getValue(), 'datetime') !== false) {
                        $row[$key] = now()->format('Y-m-d H:i:s');
                    }
                }
                
                // Handle empty strings for numeric fields
                if (is_string($value) && trim($value) === '' && in_array($key, ['id', 'merchantId', 'parcelId', 'agentId', 'deliverymanId'])) {
                    $row[$key] = null;
                }
            }
            
            // Try to insert, and handle duplicate key errors
            try {
                DB::table($tableName)->insert($row);
                $inserted++;
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                    // Try to update existing record instead
                    if (isset($row['id'])) {
                        $id = $row['id'];
                        unset($row['id']);
                        DB::table($tableName)->where('id', $id)->update($row);
                        $inserted++;
                    }
                } else {
                    throw $e;
                }
            }
            
        } catch (Exception $e) {
            throw new Exception("Failed to insert row into $tableName: " . $e->getMessage());
        }
    }
    
    return $inserted;
}

foreach ($statements as $statement) {
    $statement = trim($statement);
    
    // Skip empty statements and comments
    if (empty($statement) || 
        strpos($statement, '--') === 0 || 
        strpos($statement, '/*') === 0 || 
        strpos($statement, 'SET ') === 0 ||
        strpos($statement, 'START TRANSACTION') === 0 ||
        strpos($statement, 'COMMIT') === 0 ||
        strpos($statement, '/*!') === 0 ||
        strpos($statement, 'CREATE TABLE') === 0 ||
        strpos($statement, 'ALTER TABLE') === 0 ||
        strpos($statement, 'DROP TABLE') === 0 ||
        strpos($statement, 'CREATE INDEX') === 0) {
        continue;
    }
    
    // Only process INSERT statements
    if (strpos($statement, 'INSERT INTO') === 0) {
        try {
            // Parse the INSERT statement
            $parsed = parseInsertStatement($statement);
            
            if ($parsed === false) {
                echo "⚠ Could not parse INSERT statement: " . substr($statement, 0, 100) . "...\n";
                continue;
            }
            
            $tableName = $parsed['table'];
            
            // Check if table exists in SQLite
            if (!Schema::hasTable($tableName)) {
                echo "⚠ Skipping $tableName (table doesn't exist)\n";
                continue;
            }
            
            // Insert data using parameterized queries
            $rowsInserted = insertData($tableName, $parsed['values']);
            $insertCount += $rowsInserted;
            
            if (!isset($processedTables[$tableName])) {
                $processedTables[$tableName] = 0;
            }
            $processedTables[$tableName] += $rowsInserted;
            
            echo "✓ Inserted $rowsInserted row(s) into $tableName\n";        } catch (Exception $e) {
            $errorCount++;
            echo "✗ Error in statement: " . substr($statement, 0, 100) . "...\n";
            echo "  Error: " . $e->getMessage() . "\n";
        }
    }
}

echo "\nMigration completed!\n";
echo "Successful inserts: $insertCount\n";
echo "Errors: $errorCount\n";
echo "\nData inserted by table:\n";
foreach ($processedTables as $table => $count) {
    echo "  $table: $count rows\n";
}
