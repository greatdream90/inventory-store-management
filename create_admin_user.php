<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=inventory_store', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // สร้างผู้ใช้งาน Admin
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare('
        INSERT INTO users (name, email, password, role, is_active, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ');
    
    $result = $stmt->execute([
        'Administrator', 
        'admin@inventory.com', 
        $password_hash, 
        'admin', 
        1
    ]);
    
    if ($result) {
        echo "✅ ผู้ใช้งาน Admin สร้างสำเร็จ!\n";
        echo "📧 Email: admin@inventory.com\n";
        echo "🔐 Password: admin123\n";
        echo "👤 Role: Admin\n\n";
        
        // ตรวจสอบจำนวนผู้ใช้งานทั้งหมด
        $count = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        echo "📊 จำนวนผู้ใช้งานทั้งหมด: $count คน\n";
    }
    
} catch(Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        echo "⚠️ ผู้ใช้งาน admin@inventory.com มีอยู่แล้ว\n";
        echo "📧 Email: admin@inventory.com\n";
        echo "🔐 Password: admin123\n";
    } else {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}
?>