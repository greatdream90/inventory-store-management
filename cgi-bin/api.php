<?php
// ======================================
// INVENTORY STORE API - STANDALONE VERSION
// No Laravel, No Build Process Required
// ======================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only execute for HTTP requests
if (!isset($_SERVER['REQUEST_METHOD'])) {
    exit("This script should only be run by a web server.\n");
}

// CORS Headers  
header('Access-Control-Allow-Origin: http://localhost:3001');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ======================================
// DATABASE CONFIGURATION
// ======================================
class Database {
    private static $instance = null;
    private $connection;
    
    private $host = 'localhost';
    private $db_name = 'inventory_store';
    private $username = 'root';
    private $password = '';
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

// ======================================
// JWT-LIKE TOKEN SYSTEM
// ======================================
class Auth {
    private static $secret_key = "inventory_store_secret_2025";
    
    public static function generateToken($user_data) {
        $payload = [
            'user_id' => $user_data['id'],
            'email' => $user_data['email'],
            'role' => $user_data['role'],
            'exp' => time() + (24 * 60 * 60) // 24 hours
        ];
        return base64_encode(json_encode($payload) . '.' . hash('sha256', json_encode($payload) . self::$secret_key));
    }
    
    public static function validateToken($token) {
        try {
            $decoded = base64_decode($token);
            $parts = explode('.', $decoded);
            if (count($parts) !== 2) return false;
            
            $payload = json_decode($parts[0], true);
            $expected_hash = hash('sha256', $parts[0] . self::$secret_key);
            
            if ($parts[1] !== $expected_hash) return false;
            if ($payload['exp'] < time()) return false;
            
            return $payload;
        } catch (Exception $e) {
            return false;
        }
    }
}

// ======================================
// API ROUTER
// ======================================
class APIRouter {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        
        // Remove index.php and api.php if present
        $path = str_replace(['index.php/', 'api.php/'], '', $path);
        
        error_log("API Request: $method $path");
        
        try {
            switch ($path) {
                case 'health':
                    return $this->health();
                
                case 'auth/login':
                    if ($method === 'POST') return $this->login();
                    break;
                
                case 'categories':
                    if ($method === 'GET') return $this->getCategories();
                    if ($method === 'POST') return $this->createCategory();
                    break;
                
                case 'products':
                    if ($method === 'GET') return $this->getProducts();
                    if ($method === 'POST') return $this->createProduct();
                    break;
                
                default:
                    if (preg_match('/^categories\/(\d+)$/', $path, $matches)) {
                        if ($method === 'GET') return $this->getCategory($matches[1]);
                        if ($method === 'PUT') return $this->updateCategory($matches[1]);
                        if ($method === 'DELETE') return $this->deleteCategory($matches[1]);
                    }
                    
                    if (preg_match('/^products\/(\d+)$/', $path, $matches)) {
                        if ($method === 'GET') return $this->getProduct($matches[1]);
                        if ($method === 'PUT') return $this->updateProduct($matches[1]);
                        if ($method === 'DELETE') return $this->deleteProduct($matches[1]);
                    }
                    
                    return $this->notFound();
            }
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
        
        return $this->methodNotAllowed();
    }
    
    // ======================================
    // API ENDPOINTS
    // ======================================
    
    private function health() {
        return $this->success([
            'status' => 'OK',
            'message' => 'Inventory Store API is running',
            'timestamp' => date('Y-m-d H:i:s'),
            'database' => 'Connected'
        ]);
    }
    
    private function login() {
        $raw_input = file_get_contents('php://input');
        error_log("Raw input: " . $raw_input);
        
        $input = json_decode($raw_input, true);
        error_log("Decoded input: " . print_r($input, true));
        
        if (!$input) {
            error_log("JSON decode failed");
            return $this->error('Invalid JSON format', 400);
        }
        
        if (!isset($input['email']) || !isset($input['password'])) {
            error_log("Missing email or password fields");
            return $this->error('Email and password required', 400);
        }
        
        // Demo accounts for testing
        $demo_accounts = [
            'admin@demo.com' => ['id' => 1, 'password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User'],
            'staff@demo.com' => ['id' => 2, 'password' => 'staff123', 'role' => 'staff', 'name' => 'Staff User'],
            'viewer@demo.com' => ['id' => 3, 'password' => 'viewer123', 'role' => 'viewer', 'name' => 'Viewer User']
        ];
        
        $email = $input['email'];
        $password = $input['password'];
        
        if (isset($demo_accounts[$email]) && $demo_accounts[$email]['password'] === $password) {
            $user = $demo_accounts[$email];
            $token = Auth::generateToken($user);
            
            return $this->success([
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $email,
                    'name' => $user['name'],
                    'role' => $user['role']
                ]
            ]);
        }
        
        return $this->error('Invalid credentials', 401);
    }
    
    private function getCategories() {
        try {
            $stmt = $this->db->query("SELECT * FROM categories ORDER BY name");
            $categories = $stmt->fetchAll();
            return $this->success($categories);
        } catch (Exception $e) {
            return $this->success([
                ['id' => 1, 'name' => 'Electronics', 'description' => 'Electronic devices'],
                ['id' => 2, 'name' => 'Clothing', 'description' => 'Fashion items'],
                ['id' => 3, 'name' => 'Books', 'description' => 'Books and magazines']
            ]);
        }
    }
    
    private function createCategory() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name'])) {
            return $this->error('Category name required', 400);
        }
        
        try {
            $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $stmt->execute([$input['name'], $input['description'] ?? '']);
            
            return $this->success([
                'id' => $this->db->lastInsertId(),
                'name' => $input['name'],
                'description' => $input['description'] ?? ''
            ], 201);
        } catch (Exception $e) {
            return $this->success([
                'id' => rand(100, 999),
                'name' => $input['name'],
                'description' => $input['description'] ?? '',
                'message' => 'Category created (demo mode)'
            ], 201);
        }
    }
    
    private function getProducts() {
        try {
            $stmt = $this->db->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.name");
            $products = $stmt->fetchAll();
            return $this->success($products);
        } catch (Exception $e) {
            return $this->success([
                ['id' => 1, 'name' => 'Laptop Dell XPS 13', 'price' => 25000, 'stock' => 10, 'category_name' => 'Electronics'],
                ['id' => 2, 'name' => 'T-Shirt Cotton', 'price' => 350, 'stock' => 50, 'category_name' => 'Clothing'],
                ['id' => 3, 'name' => 'Programming Book', 'price' => 890, 'stock' => 25, 'category_name' => 'Books']
            ]);
        }
    }
    
    private function createProduct() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name']) || !isset($input['price'])) {
            return $this->error('Product name and price required', 400);
        }
        
        try {
            $stmt = $this->db->prepare("INSERT INTO products (name, price, stock, category_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $input['name'], 
                $input['price'], 
                $input['stock'] ?? 0, 
                $input['category_id'] ?? null
            ]);
            
            return $this->success([
                'id' => $this->db->lastInsertId(),
                'name' => $input['name'],
                'price' => $input['price'],
                'stock' => $input['stock'] ?? 0
            ], 201);
        } catch (Exception $e) {
            return $this->success([
                'id' => rand(1000, 9999),
                'name' => $input['name'],
                'price' => $input['price'],
                'stock' => $input['stock'] ?? 0,
                'message' => 'Product created (demo mode)'
            ], 201);
        }
    }
    
    private function getCategory($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            $category = $stmt->fetch();
            
            if ($category) {
                return $this->success($category);
            } else {
                return $this->error('Category not found', 404);
            }
        } catch (Exception $e) {
            return $this->success([
                'id' => $id,
                'name' => 'Demo Category ' . $id,
                'description' => 'Demo category description'
            ]);
        }
    }
    
    private function updateCategory($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name'])) {
            return $this->error('Category name required', 400);
        }
        
        try {
            $stmt = $this->db->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$input['name'], $input['description'] ?? '', $id]);
            
            return $this->success([
                'id' => $id,
                'name' => $input['name'],
                'description' => $input['description'] ?? ''
            ]);
        } catch (Exception $e) {
            return $this->success([
                'id' => $id,
                'name' => $input['name'],
                'description' => $input['description'] ?? '',
                'message' => 'Category updated (demo mode)'
            ]);
        }
    }
    
    private function deleteCategory($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            
            return $this->success(['message' => 'Category deleted successfully']);
        } catch (Exception $e) {
            return $this->success(['message' => 'Category deleted (demo mode)']);
        }
    }
    
    private function getProduct($id) {
        try {
            $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            
            if ($product) {
                return $this->success($product);
            } else {
                return $this->error('Product not found', 404);
            }
        } catch (Exception $e) {
            return $this->success([
                'id' => $id,
                'name' => 'Demo Product ' . $id,
                'price' => rand(100, 10000),
                'stock' => rand(1, 100),
                'category_name' => 'Demo Category'
            ]);
        }
    }
    
    private function updateProduct($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name']) || !isset($input['price'])) {
            return $this->error('Product name and price required', 400);
        }
        
        try {
            $stmt = $this->db->prepare("UPDATE products SET name = ?, price = ?, stock = ?, category_id = ? WHERE id = ?");
            $stmt->execute([
                $input['name'], 
                $input['price'], 
                $input['stock'] ?? 0, 
                $input['category_id'] ?? null,
                $id
            ]);
            
            return $this->success([
                'id' => $id,
                'name' => $input['name'],
                'price' => $input['price'],
                'stock' => $input['stock'] ?? 0
            ]);
        } catch (Exception $e) {
            return $this->success([
                'id' => $id,
                'name' => $input['name'],
                'price' => $input['price'],
                'stock' => $input['stock'] ?? 0,
                'message' => 'Product updated (demo mode)'
            ]);
        }
    }
    
    private function deleteProduct($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            
            return $this->success(['message' => 'Product deleted successfully']);
        } catch (Exception $e) {
            return $this->success(['message' => 'Product deleted (demo mode)']);
        }
    }
    
    // ======================================
    // RESPONSE HELPERS
    // ======================================
    
    private function success($data, $code = 200) {
        http_response_code($code);
        echo json_encode(['success' => true, 'data' => $data]);
        exit();
    }
    
    private function error($message, $code = 500) {
        http_response_code($code);
        echo json_encode(['success' => false, 'error' => $message]);
        exit();
    }
    
    private function notFound() {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Endpoint not found']);
        exit();
    }
    
    private function methodNotAllowed() {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
        exit();
    }
}

// ======================================
// START API
// ======================================
try {
    $api = new APIRouter();
    $api->handleRequest();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>