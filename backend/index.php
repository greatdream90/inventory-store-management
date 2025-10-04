<?php

// Simple Laravel API Server for XAMPP
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: http://localhost:3001');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database connection
$host = '127.0.0.1';
$dbname = 'inventory_store';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Get request path
$path = trim($_SERVER['REQUEST_URI'], '/');
$path = strtok($path, '?'); // Remove query string

// Remove 'inventoty_store_v1/backend/' prefix if present
$path = preg_replace('/^inventoty_store_v1\/backend\//', '', $path);

// Remove 'api/' prefix if present
$path = preg_replace('/^api\//', '', $path);

$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

// Authentication endpoints
if ($path === 'auth/login' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['email']) || !isset($input['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password required']);
        exit();
    }
    
    // Demo accounts for testing
    $demoAccounts = [
        'admin@demo.com' => ['password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User'],
        'staff@demo.com' => ['password' => 'staff123', 'role' => 'staff', 'name' => 'Staff User'],
        'viewer@demo.com' => ['password' => 'viewer123', 'role' => 'viewer', 'name' => 'Viewer User']
    ];
    
    if (isset($demoAccounts[$input['email']]) && 
        $demoAccounts[$input['email']]['password'] === $input['password']) {
        
        $user = $demoAccounts[$input['email']];
        $token = base64_encode(json_encode([
            'email' => $input['email'],
            'role' => $user['role'],
            'exp' => time() + 3600
        ]));
        
        echo json_encode([
            'success' => true,
            'token' => $token,
            'user' => [
                'email' => $input['email'],
                'name' => $user['name'],
                'role' => $user['role']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
    exit();
}

// Categories endpoint
if ($path === 'categories' && $method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY created_at DESC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $categories
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch categories: ' . $e->getMessage()]);
    }
    exit();
}

// Products endpoint
if ($path === 'products' && $method === 'GET') {
    try {
        $sql = "
            SELECT p.*, c.name as category_name, c.color as category_color
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.is_active = 1 
            ORDER BY p.created_at DESC
        ";
        
        $stmt = $pdo->query($sql);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => [
                'data' => $products,
                'total' => count($products),
                'per_page' => 15,
                'current_page' => 1
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch products: ' . $e->getMessage()]);
    }
    exit();
}

// Health check
if ($path === 'health' || $path === '') {
    echo json_encode(['status' => 'OK', 'timestamp' => date('Y-m-d H:i:s')]);
    exit();
}

// Default response
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found', 'path' => $path, 'method' => $method]);