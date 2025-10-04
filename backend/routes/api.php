<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
        Route::get('me', [App\Http\Controllers\AuthController::class, 'me']);
        Route::post('refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
    });
});

// Protected Routes
Route::middleware(['auth:api'])->group(function () {
    
    // Categories
    Route::apiResource('categories', App\Http\Controllers\CategoryController::class);
    
    // Products
    Route::apiResource('products', App\Http\Controllers\ProductController::class);
    Route::get('products/{id}/stock-history', [App\Http\Controllers\ProductController::class, 'stockHistory']);
    
    // Customers
    Route::apiResource('customers', App\Http\Controllers\CustomerController::class);
    Route::get('customers/{id}/purchase-history', [App\Http\Controllers\CustomerController::class, 'purchaseHistory']);
    
    // Sales
    Route::apiResource('sales', App\Http\Controllers\SaleController::class);
    Route::post('sales/{id}/complete', [App\Http\Controllers\SaleController::class, 'complete']);
    Route::post('sales/{id}/cancel', [App\Http\Controllers\SaleController::class, 'cancel']);
    
    // Inventory
    Route::prefix('inventory')->group(function () {
        Route::get('transactions', [App\Http\Controllers\InventoryController::class, 'transactions']);
        Route::post('adjust', [App\Http\Controllers\InventoryController::class, 'adjust']);
        Route::get('low-stock', [App\Http\Controllers\InventoryController::class, 'lowStock']);
    });
    
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\ReportController::class, 'dashboard']);
        Route::get('sales', [App\Http\Controllers\ReportController::class, 'sales']);
        Route::get('products', [App\Http\Controllers\ReportController::class, 'products']);
        Route::get('customers', [App\Http\Controllers\ReportController::class, 'customers']);
    });
    
    // Notifications
    Route::apiResource('notifications', App\Http\Controllers\NotificationController::class);
    Route::post('notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    
    // Settings (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('settings', App\Http\Controllers\SettingController::class);
        Route::apiResource('users', App\Http\Controllers\UserController::class);
    });
});

// Public Routes
Route::get('health', function () {
    return response()->json(['status' => 'OK', 'timestamp' => now()]);
});