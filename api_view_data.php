<?php
header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=inventory_store;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $data = [];
    
    // Categories
    $categories = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    $data['categories'] = $categories;
    
    // Products with category names
    $products = $pdo->query("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id
    ")->fetchAll(PDO::FETCH_ASSOC);
    $data['products'] = $products;
    
    // Summary counts
    $tables = ['users', 'customers', 'sales', 'sale_items', 'inventory_transactions', 'notifications', 'settings'];
    $summary = [];
    
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) as count FROM $table")->fetch()['count'];
        $summary[$table] = (int)$count;
    }
    
    $summary['categories'] = count($categories);
    $summary['products'] = count($products);
    
    $data['summary'] = $summary;
    
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>