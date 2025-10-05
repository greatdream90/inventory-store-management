<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'inventory_store');
    $mysqli->set_charset('utf8mb4');
    
    echo "📊 ข้อมูลในฐานข้อมูル inventory_store\n";
    echo "=" . str_repeat("=", 60) . "\n\n";
    
    // Categories
    echo "🗂️ ตาราง: categories\n";
    echo "-" . str_repeat("-", 40) . "\n";
    $result = $mysqli->query("SELECT * FROM categories");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "📝 ID: {$row['id']}\n";
            echo "   ชื่อ: {$row['name']}\n";
            echo "   คำอธิบาย: {$row['description']}\n";
            echo "   สี: {$row['color']}\n";
            echo "   สถานะ: " . ($row['is_active'] ? 'ใช้งาน' : 'ไม่ใช้งาน') . "\n";
            echo "   สร้างเมื่อ: {$row['created_at']}\n\n";
        }
    } else {
        echo "🈳 ไม่มีข้อมูล\n\n";
    }
    
    // Products
    echo "🗂️ ตาราง: products\n";
    echo "-" . str_repeat("-", 40) . "\n";
    $result = $mysqli->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "📦 ID: {$row['id']}\n";
            echo "   ชื่อสินค้า: {$row['name']}\n";
            echo "   รหัสสินค้า: {$row['sku']}\n";
            echo "   หมวดหมู่: {$row['category_name']}\n";
            echo "   คำอธิบาย: {$row['description']}\n";
            echo "   ราคาขาย: " . number_format($row['price'], 2) . " บาท\n";
            echo "   ราคาทุน: " . number_format($row['cost_price'], 2) . " บาท\n";
            echo "   จำนวนคงเหลือ: {$row['quantity']} {$row['unit']}\n";
            echo "   จำนวนขั้นต่ำ: {$row['min_quantity']} {$row['unit']}\n";
            echo "   สถานะ: " . ($row['is_active'] ? 'ใช้งาน' : 'ไม่ใช้งาน') . "\n";
            echo "   สร้างเมื่อ: {$row['created_at']}\n\n";
        }
    } else {
        echo "🈳 ไม่มีข้อมูล\n\n";
    }
    
    // Summary
    echo "📋 สรุปข้อมูลทั้งหมด\n";
    echo "=" . str_repeat("=", 40) . "\n";
    
    $tables_info = [
        'users' => 'ผู้ใช้งาน',
        'categories' => 'หมวดหมู่สินค้า',
        'products' => 'สินค้า',
        'customers' => 'ลูกค้า',
        'sales' => 'การขาย',
        'sale_items' => 'รายการขาย',
        'inventory_transactions' => 'ธุรกรรมสินค้า',
        'notifications' => 'การแจ้งเตือน',
        'settings' => 'การตั้งค่า'
    ];
    
    foreach ($tables_info as $table => $description) {
        $count = $mysqli->query("SELECT COUNT(*) as count FROM $table")->fetch_assoc()['count'];
        $status = $count > 0 ? "✅ $count รายการ" : "🈳 ว่าง";
        echo "📊 $description ($table): $status\n";
    }
    
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>