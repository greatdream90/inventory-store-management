<?php

// Simple Laravel API Server
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS headers - Allow multiple origins
$allowedOrigins = [
    'http://localhost:3000',
    'http://localhost:3001',
    'https://your-app.vercel.app',          // Update this with your Vercel domain
    'https://your-app.netlify.app',         // Or Netlify domain
    'https://your-custom-domain.com'        // Or your custom domain
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
} else {
    // Fallback for development
    header('Access-Control-Allow-Origin: *');
}

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

// Simple routing
$method = $_SERVER['REQUEST_METHOD'];
$path = trim($_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'], '/');
$path = strtok($path, '?'); // Remove query string

// Remove 'api/' or 'api.php/' prefix if present
$path = preg_replace('/^(api\.php\/|api\/)/', '', $path);

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

// Get current user endpoint
if ($path === 'auth/me' && $method === 'GET') {
    // Get Authorization header
    $headers = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? '';
    
    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401);
        echo json_encode(['error' => 'No token provided']);
        exit();
    }
    
    $token = substr($authHeader, 7); // Remove "Bearer " prefix
    
    try {
        $payload = json_decode(base64_decode($token), true);
        
        if (!$payload || $payload['exp'] < time()) {
            http_response_code(401);
            echo json_encode(['error' => 'Token expired']);
            exit();
        }
        
        // Return user info from token
        echo json_encode([
            'email' => $payload['email'],
            'role' => $payload['role'],
            'name' => $payload['role'] === 'admin' ? 'Admin User' : 
                     ($payload['role'] === 'staff' ? 'Staff User' : 'Viewer User')
        ]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token']);
    }
    exit();
}

// Logout endpoint
if ($path === 'auth/logout' && $method === 'POST') {
    // For demo purposes, we'll just return success
    // In a real app, you'd invalidate the token on server side
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
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
if ($path === 'health') {
    echo json_encode(['status' => 'OK', 'timestamp' => date('Y-m-d H:i:s')]);
    exit();
}

// Customers endpoint
if ($path === 'customers' && $method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM customers WHERE is_active = 1 ORDER BY created_at DESC");
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $customers
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch customers: ' . $e->getMessage()]);
    }
    exit();
}

// Sales endpoint
if ($path === 'sales' && $method === 'GET') {
    try {
        $sql = "
            SELECT s.*, c.name as customer_name
            FROM sales s 
            LEFT JOIN customers c ON s.customer_id = c.id 
            ORDER BY s.created_at DESC
        ";
        
        $stmt = $pdo->query($sql);
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $sales
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch sales: ' . $e->getMessage()]);
    }
    exit();
}

// Default response
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found', 'path' => $path, 'method' => $method]);