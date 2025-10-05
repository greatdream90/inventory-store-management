<?php

// Production database setup
// Run this script once after deploying to create tables

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbname = $_ENV['DB_DATABASE'] ?? 'inventory_store';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$port = $_ENV['DB_PORT'] ?? '3306';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    // Create tables
    $sql = file_get_contents(__DIR__ . '/create_tables.sql');
    $pdo->exec($sql);
    
    // Create admin user
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('
        INSERT IGNORE INTO users (name, email, password, role, is_active, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ');
    
    $stmt->execute([
        'Administrator', 
        'admin@inventory.com', 
        $password_hash, 
        'admin', 
        1
    ]);
    
    // Insert sample categories
    $stmt = $pdo->prepare('
        INSERT IGNORE INTO categories (id, name, description, color, is_active, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ');
    
    $stmt->execute([1, 'อุปกรณ์คอมพิวเตอร์', 'อุปกรณ์และชิ้นส่วนคอมพิวเตอร์', '#007bff', 1]);
    $stmt->execute([3, 'อิเล็กทรอนิกส์', 'อุปกรณ์อิเล็กทรอนิกส์ต่างๆ', '#dc3545', 1]);
    
    // Insert sample products
    $stmt = $pdo->prepare('
        INSERT IGNORE INTO products (id, name, sku, category_id, description, price, cost_price, quantity, min_quantity, unit, is_active, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ');
    
    $stmt->execute([1, 'เมาส์ไร้สาย Logitech', 'MOUSE001', 1, 'เมาส์ไร้สายแบรนด์ดัง', 890.00, 650.00, 25, 5, 'ชิ้น', 1]);
    $stmt->execute([2, 'คีย์บอร์ดเกมมิ่ง', 'KB001', 1, 'คีย์บอร์ดเกมมิ่ง RGB', 2590.00, 1890.00, 15, 3, 'ชิ้น', 1]);
    
    echo "✅ Database setup completed successfully!\n";
    echo "📊 Database: $dbname\n";
    echo "🔐 Admin: admin@inventory.com / admin123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>