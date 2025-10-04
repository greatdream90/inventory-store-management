<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Inventory Store API',
        'version' => '1.0.0',
        'status' => 'active'
    ]);
});

// Fallback route for SPA
Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found',
        'available_endpoints' => [
            'GET /' => 'API Info',
            'POST /api/auth/login' => 'Login',
            'GET /api/health' => 'Health Check'
        ]
    ], 404);
});