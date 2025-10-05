<?php

// Simple Laravel API Server for XAMPP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS configuration for production
$allowedOrigins = [
    'http://localhost:3001',
    'https://candid-puffpuff-5120a2.netlify.app',
    $_ENV['FRONTEND_URL'] ?? 'https://candid-puffpuff-5120a2.netlify.app'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database connection - support both local and production
$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbname = $_ENV['DB_DATABASE'] ?? 'inventory_store';  
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$port = $_ENV['DB_PORT'] ?? '3306';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // For testing, allow API to work without database for auth endpoints
    $pdo = null;
    error_log("Database connection failed, continuing without DB: " . $e->getMessage());
}

// Get request path and clean it up
$requestUri = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($requestUri, PHP_URL_PATH), '/');

// Log the incoming request for debugging
error_log("Incoming request: " . $_SERVER['REQUEST_METHOD'] . " " . $requestUri);
error_log("Initial path: " . $path);

// Remove common prefixes that might interfere
$path = preg_replace('/^inventory-store-management\/backend\//', '', $path);
$path = preg_replace('/^backend\//', '', $path);
$path = preg_replace('/^api\.php\//', '', $path);
$path = preg_replace('/^api\//', '', $path);

error_log("Final path after processing: " . $path);

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
    
    try {
        $user = null;
        
        // Check user in database if available
        if ($pdo) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$input['email']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        if ($user && password_verify($input['password'], $user['password'])) {
            // Login successful
            $token = base64_encode(json_encode([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'exp' => time() + 3600 // 1 hour expiration
            ]));
            
            echo json_encode([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ]
            ]);
        } else {
            // Demo accounts fallback for testing
            $demoAccounts = [
                'admin@demo.com' => ['password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User'],
                'staff@demo.com' => ['password' => 'staff123', 'role' => 'staff', 'name' => 'Staff User'],
                'viewer@demo.com' => ['password' => 'viewer123', 'role' => 'viewer', 'name' => 'Viewer User']
            ];
            
            if (isset($demoAccounts[$input['email']]) && 
                $demoAccounts[$input['email']]['password'] === $input['password']) {
                
                $demoUser = $demoAccounts[$input['email']];
                $token = base64_encode(json_encode([
                    'email' => $input['email'],
                    'role' => $demoUser['role'],
                    'exp' => time() + 3600
                ]));
                
                echo json_encode([
                    'success' => true,
                    'token' => $token,
                    'user' => [
                        'email' => $input['email'],
                        'name' => $demoUser['name'],
                        'role' => $demoUser['role']
                    ]
                ]);
            } else {
                http_response_code(401);
                echo json_encode(['error' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง']);
            }
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ: ' . $e->getMessage()]);
    }
    exit();
}

// Logout endpoint
if ($path === 'auth/logout' && $method === 'POST') {
    // For stateless JWT, we just return success
    // Client should remove token from localStorage
    echo json_encode([
        'success' => true,
        'message' => 'ออกจากระบบเรียบร้อย'
    ]);
    exit();
}

// Get user info endpoint
if ($path === 'auth/me' && $method === 'GET') {
    // Get Authorization header
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? null;
    
    if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(['error' => 'Token required']);
        exit();
    }
    
    $token = $matches[1];
    
    try {
        // Decode token (simple base64 decode for our implementation)
        $decoded = json_decode(base64_decode($token), true);
        
        if (!$decoded || !isset($decoded['exp']) || $decoded['exp'] < time()) {
            http_response_code(401);
            echo json_encode(['error' => 'Token expired']);
            exit();
        }
        
        // If token has user_id, get from database
        if (isset($decoded['user_id'])) {
            $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ? AND is_active = 1");
            $stmt->execute([$decoded['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                echo json_encode($user);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'User not found']);
            }
        } else {
            // Demo user from token
            echo json_encode([
                'email' => $decoded['email'],
                'name' => $decoded['name'] ?? 'Demo User',
                'role' => $decoded['role']
            ]);
        }
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token']);
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