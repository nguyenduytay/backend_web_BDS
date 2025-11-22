<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel API is running',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

// Health check endpoint để Render không ngủ
Route::get('/health', function () {
    try {
        $dbStatus = 'connected';
        DB::connection()->getPdo();
    } catch (\Exception $e) {
        $dbStatus = 'disconnected';
    }

    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'database' => $dbStatus,
    ]);
});
