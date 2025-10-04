<?php
phpinfo();
echo "<br><strong>Database Test:</strong><br>";

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=inventory_store", "root", "");
    echo "Database connection: SUCCESS<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch();
    echo "Products in database: " . $result['count'];
} catch (Exception $e) {
    echo "Database connection: FAILED - " . $e->getMessage();
}
?>