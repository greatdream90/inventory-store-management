<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=inventory_store;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo 'Database connected successfully';
} catch (Exception $e) {
    echo 'Database connection failed: ' . $e->getMessage();
}
?>