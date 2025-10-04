<?php
// Inventory Store Root Index
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Store Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card { box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: none; border-radius: 15px; }
        .btn-custom { background: linear-gradient(45deg, #667eea, #764ba2); border: none; border-radius: 25px; }
        .btn-custom:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .status-badge { padding: 8px 15px; border-radius: 20px; font-size: 12px; }
        .status-success { background: #d4edda; color: #155724; }
        .status-warning { background: #fff3cd; color: #856404; }
        .status-danger { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
        <div class="card" style="max-width: 800px; width: 100%;">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <i class="fas fa-boxes text-primary" style="font-size: 4rem;"></i>
                    <h1 class="mt-3 text-primary fw-bold">Inventory Store Management</h1>
                    <p class="text-muted">ระบบจัดการคลังสินค้า พร้อม POS และรายงาน</p>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded">
                            <i class="fas fa-desktop text-info mb-2" style="font-size: 2rem;"></i>
                            <h5>Frontend (Vue.js)</h5>
                            <span class="status-badge status-success">
                                <i class="fas fa-check"></i> Active
                            </span>
                            <p class="small mt-2 mb-0">Port: 3000</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded">
                            <i class="fas fa-server text-warning mb-2" style="font-size: 2rem;"></i>
                            <h5>Backend (PHP API)</h5>
                            <?php
                            $backendStatus = file_exists(__DIR__ . '/backend/index.php');
                            if ($backendStatus) {
                                echo '<span class="status-badge status-success"><i class="fas fa-check"></i> Active</span>';
                            } else {
                                echo '<span class="status-badge status-danger"><i class="fas fa-times"></i> Inactive</span>';
                            }
                            ?>
                            <p class="small mt-2 mb-0">XAMPP Apache</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded">
                            <i class="fas fa-database text-success mb-2" style="font-size: 2rem;"></i>
                            <h5>Database (MySQL)</h5>
                            <?php
                            try {
                                $pdo = new PDO("mysql:host=127.0.0.1;dbname=inventory_store", "root", "");
                                echo '<span class="status-badge status-success"><i class="fas fa-check"></i> Connected</span>';
                                $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
                                $result = $stmt->fetch();
                                echo '<p class="small mt-2 mb-0">Products: ' . $result['count'] . '</p>';
                            } catch (Exception $e) {
                                echo '<span class="status-badge status-danger"><i class="fas fa-times"></i> Error</span>';
                                echo '<p class="small mt-2 mb-0">Connection failed</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-3 d-md-flex justify-content-center">
                    <a href="http://localhost:3000" class="btn btn-custom btn-lg text-white px-4" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>
                        เปิดแอพพลิเคชัน
                    </a>
                    <a href="backend/test.php" class="btn btn-outline-primary btn-lg px-4">
                        <i class="fas fa-wrench me-2"></i>
                        ทดสอบ Backend
                    </a>
                    <a href="/phpmyadmin" class="btn btn-outline-success btn-lg px-4" target="_blank">
                        <i class="fas fa-database me-2"></i>
                        phpMyAdmin
                    </a>
                </div>

                <div class="mt-4 pt-4 border-top">
                    <h6 class="text-muted">Demo Accounts</h6>
                    <div class="row text-start">
                        <div class="col-md-4">
                            <small><strong>Admin:</strong><br>admin@demo.com<br>admin123</small>
                        </div>
                        <div class="col-md-4">
                            <small><strong>Staff:</strong><br>staff@demo.com<br>staff123</small>
                        </div>
                        <div class="col-md-4">
                            <small><strong>Viewer:</strong><br>viewer@demo.com<br>viewer123</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>