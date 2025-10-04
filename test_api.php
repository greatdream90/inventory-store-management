<?php
// Simulate HTTP request for testing
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/health';
$_SERVER['HTTP_HOST'] = 'localhost:8000';

// Include the API script
include 'api.php';
?>