<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=inventory_store', 'root', '');
    
    // ตรวจสอบตาราง users
    $tables_to_check = ['users', 'categories', 'products', 'customers', 'sales'];
    
    foreach ($tables_to_check as $table) {
        try {
            $count = $pdo->query("SELECT COUNT(*) as count FROM $table")->fetch();
            echo "📊 $table: {$count['count']} รายการ\n";
        } catch (Exception $e) {
            echo "⚠️ ตาราง $table: ไม่สามารถเข้าถึงได้ ({$e->getMessage()})\n";
        }
    }
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>