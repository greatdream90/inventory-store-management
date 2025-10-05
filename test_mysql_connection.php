<?php
try {
    // ใช้ข้อมูลการเชื่อมต่อจาก MCP config
    $host = '127.0.0.1';
    $port = '3306';
    $dbname = 'inventory_store';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ เชื่อมต่อ MySQL สำเร็จ!\n";
    echo "📊 ฐานข้อมูล: " . $pdo->query('SELECT DATABASE()')->fetchColumn() . "\n";
    echo "🔢 MySQL Version: " . $pdo->query('SELECT VERSION()')->fetchColumn() . "\n";
    
    // ตรวจสอบตารางในฐานข้อมูล
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 จำนวนตาราง: " . count($tables) . " ตาราง\n";
    
    if (!empty($tables)) {
        echo "📝 รายชื่อตาราง:\n";
        foreach ($tables as $table) {
            echo "   - $table\n";
        }
    }
    
} catch(PDOException $e) {
    echo "❌ การเชื่อมต่อล้มเหลว: " . $e->getMessage() . "\n";
}
?>