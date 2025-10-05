<?php

// Production environment configuration
return [
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'dbname' => $_ENV['DB_DATABASE'] ?? 'inventory_store',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'port' => $_ENV['DB_PORT'] ?? '3306',
    'charset' => 'utf8mb4',
    
    // App settings
    'app_env' => $_ENV['APP_ENV'] ?? 'production',
    'app_debug' => $_ENV['APP_DEBUG'] ?? false,
    'app_url' => $_ENV['APP_URL'] ?? 'https://your-backend-url.railway.app',
    
    // CORS settings
    'frontend_url' => $_ENV['FRONTEND_URL'] ?? 'https://candid-puffpuff-5120a2.netlify.app',
    
    // JWT settings
    'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'your-production-jwt-secret',
    'jwt_ttl' => $_ENV['JWT_TTL'] ?? 60,
];