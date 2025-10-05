<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=inventory_store', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "📊 ข้อมูลในฐานข้อมูล inventory_store\n";
    echo "=" . str_repeat("=", 50) . "\n\n";
    
    // ตรวจสอบข้อมูลในแต่ละตาราง
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "🗂️ ตาราง: $table\n";
        echo "-" . str_repeat("-", 40) . "\n";
        
        try {
            // นับจำนวนรายการ
            $count = $pdo->query("SELECT COUNT(*) as count FROM $table")->fetch();
            echo "📝 จำนวนรายการ: {$count['count']}\n";
            
            if ($count['count'] > 0) {
                // แสดงข้อมูลตัวอย่าง 3 รายการแรก
                $sample = $pdo->query("SELECT * FROM $table LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($sample)) {
                    echo "📋 ตัวอย่างข้อมูล:\n";
                    foreach ($sample as $index => $row) {
                        echo "   รายการที่ " . ($index + 1) . ":\n";
                        foreach ($row as $column => $value) {
                            $displayValue = is_null($value) ? 'NULL' : (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value);
                            echo "     $column: $displayValue\n";
                        }
                        echo "\n";
                    }
                }
            } else {
                echo "🈳 ไม่มีข้อมูล\n";
            }
            
        } catch (Exception $e) {
            echo "⚠️ ไม่สามารถเข้าถึงได้: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>