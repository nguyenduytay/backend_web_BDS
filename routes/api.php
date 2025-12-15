<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Favorite\FavoriteController;
use App\Http\Controllers\Feature\FeatureController;
use App\Http\Controllers\Location\LocationController;
use App\Http\Controllers\PropertyFeature\PropertyFeatureController;
use App\Http\Controllers\PropertyImage\PropertyImageController;
use App\Http\Controllers\PropertyType\PropertyTypeController;
use App\Http\Controllers\Property\PropertyController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

// API Versioning Middleware
Route::middleware(['api'])->group(function () {

    // ----------------- AUTH -----------------
    Route::prefix('auth')->group(function () {
        // dev: tắt rate limit tạm thời
        // Route::post('register', [AuthController::class, 'register'])->middleware('rate.limit:5,1'); // 5 requests per minute
        // Route::post('login', [AuthController::class, 'login'])->middleware('rate.limit:10,1');      // 10 requests per minute
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        // note: cần xem xét
        // Route::post('forgot_password', [AuthController::class, 'forgotPassword'])->middleware('rate.limit:3,5'); // 3 requests per 5 minutes
        // note:cần xem xét
        // Route::post('reset_password', [AuthController::class, 'resetPassword'])->middleware('rate.limit:5,10');  // 5 requests per 10 minutes
        Route::post('forgot_password', [AuthController::class, 'forgotPassword']);
        Route::post('reset_password', [AuthController::class, 'resetPassword']);

        // Routes yêu cầu xác thực Sanctum
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            // note: refresh không cần thiết
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

    // ----------------- USER MANAGEMENT -----------------
    Route::prefix('users')->group(function () {
        Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
            // Xem danh sách người dùng
            Route::get('/index', [UserController::class, 'index']);   // Lấy danh sách user
            // Tạo người dùng mới
            Route::post('/create', [UserController::class, 'store']); // Tạo user mới
        });
        Route::middleware(['auth:sanctum', 'role:admin,user'])->group(function () {
            // Xem thông tin user cụ thể (với phân quyền)
            Route::get('/show', [UserController::class, 'show'])->middleware('check.user.permission');
            // Cập nhật thông tin user cụ thể theo phần quyền
            Route::put('/update', [UserController::class, 'update'])->middleware('check.user.permission');
            // Xóa user (chỉ admin)
            Route::post('/delete', [UserController::class, 'destroy'])->middleware('check.user.permission');
        });
    });

    // ----------------- LOCATIONS -----------------
    Route::prefix('locations')->group(function () {
        // Lấy tất cả locations
        Route::get('/all', [LocationController::class, 'all']);

        // Lấy danh sách thành phố duy nhất
        Route::get('/cities', [LocationController::class, 'cities']);

        // Lấy danh sách quận/huyện theo city
        Route::get('/cities/{city}/districts', [LocationController::class, 'districts']);

        // Tìm kiếm location theo city
        Route::get('/search_city', [LocationController::class, 'searchCity']);

        // Lấy location theo ID (chỉ match số)
        Route::get('/search/{id}', [LocationController::class, 'searchId'])->whereNumber('id');

        // Các route chỉ admin
        Route::middleware(['auth:sanctum', 'admin'])->group(function () {
            Route::post('/create', [LocationController::class, 'create']);
            Route::put('/update', [LocationController::class, 'update']);
            // note: cần xem xét lại
            Route::delete('/delete', [LocationController::class, 'delete']);
        });
    });

    // ----------------- PROPERTY TYPES -----------------
    Route::prefix('property_types')->group(function () {
        // Lấy tất cả property type
        Route::get('/all', [PropertyTypeController::class, 'all']);

        // Lấy property type theo type
        Route::get('/search_type', [PropertyTypeController::class, 'searchType']);

        // Các route chỉ admin
        Route::middleware(['auth:sanctum', 'admin'])->group(function () {
            // Tạo property type mới
            Route::post('/create', [PropertyTypeController::class, 'create']);

            // Cập nhật property type
            Route::put('/update/{id}', [PropertyTypeController::class, 'update']);

            // Xóa property type
            Route::delete('/delete/{id}', [PropertyTypeController::class, 'delete']);
        });
    });

    // ----------------- Features -----------------

    Route::prefix('features')->group(function () {
        // Lấy tất cả features
        Route::get('/all', [FeatureController::class, 'all']);

        // Lấy feature theo ID
        Route::get('/search/{id}', [FeatureController::class, 'searchId']);

        // Các route chỉ admin
        Route::middleware(['auth:sanctum', 'admin'])->group(function () {
            Route::post('/create', [FeatureController::class, 'create']);
            Route::put('/update/{id}', [FeatureController::class, 'update']);
            Route::delete('/delete/{id}', [FeatureController::class, 'delete']);
        });
    });

    // ----------------- Contacts -----------------
    Route::prefix('contacts')->group(function () {
        // Routes cho Admin: Quản lý contacts
        Route::middleware(['auth:sanctum', 'admin'])->group(function () {
            Route::get('/all', [ContactController::class, 'all']);
            Route::get('/search', [ContactController::class, 'search']);
            Route::post('/create', [ContactController::class, 'create']);
            Route::put('/update/{id}', [ContactController::class, 'update']);
            Route::delete('/delete/{id}', [ContactController::class, 'delete']);
        });

        // Routes cho User: Liên hệ người bán
        Route::middleware(['auth:sanctum', 'role:admin,user'])->group(function () {
            Route::get('/seller/{propertyId}', [ContactController::class, 'contactSeller'])->whereNumber('propertyId'); // User lấy thông tin liên hệ người bán
        });
    });

    // ----------------- Property API -----------------
    Route::prefix('properties')->group(function () {
        // Đặt các route cụ thể (dài hơn) trước để tránh conflict với /all
        // Lấy danh sách properties theo loại
        Route::get('/by-type/{property_type_id}', [PropertyController::class, 'allByPropertyType'])->whereNumber('property_type_id');
        // Lấy danh sách properties theo địa điểm
        Route::get('/by-location', [PropertyController::class, 'allByLoaction']);
        // Lấy danh sách properties nổi bật
        Route::get('/featured', [PropertyController::class, 'allByOutstand']);

        // Lấy danh sách tất cả properties (đặt sau các route cụ thể)
        Route::get('/all', [PropertyController::class, 'all']);
        // Chi tiết property theo ID
        // ⚠️ LỖ HỔNG: Đã bỏ whereNumber() để có thể test SQL Injection
        Route::get('/detail/{id}', [PropertyController::class, 'searchId']);

        // Routes cần authentication
        Route::middleware(['auth:sanctum', 'role:admin,user'])->group(function () {
            Route::post('/create', [PropertyController::class, 'create']);                 // Tạo mới
            Route::put('/update/{id}', [PropertyController::class, 'update']);             // Cập nhật
            Route::delete('/delete/{id}', [PropertyController::class, 'delete']);          // Soft delete
            Route::post('/restore/{id}', [PropertyController::class, 'restore']);          // Khôi phục
            Route::delete('/force/{id}', [PropertyController::class, 'forceDelete']);      // Xóa vĩnh viễn
            Route::get('/user/{userId}', [PropertyController::class, 'propertiesByUser'])->whereNumber('userId'); // Lấy properties của user
        });

        // Routes chỉ dành cho Admin: Duyệt/Ẩn tin đăng
        Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
            Route::post('/approve/{id}', [PropertyController::class, 'approve'])->whereNumber('id');  // Duyệt tin đăng
            Route::post('/hide/{id}', [PropertyController::class, 'hide'])->whereNumber('id');        // Ẩn tin đăng
        });
    });

    // ----------------- Property Image -----------------
    Route::prefix('property_image/{propertyId}')->group(function () {
        Route::get('/all', [PropertyImageController::class, 'all']); // Lấy images của property
        Route::middleware(['auth:sanctum', 'role:admin,user'])->group(function () {
            Route::post('/create', [PropertyImageController::class, 'create']);             // Thêm image cho property
            Route::get('/show/{imageId}', [PropertyImageController::class, 'show']);        // Lấy image cụ thể
            Route::post('/update/{imageId}', [PropertyImageController::class, 'update']);   // Cập nhật image
            Route::delete('/delete/{imageId}', [PropertyImageController::class, 'delete']); // Xóa image
            // xóa nhiều ảnh
            Route::post('/delete_multiple', [PropertyImageController::class, 'deleteMultiple']);
        });
    });
    Route::prefix('/property_image')->group(function () {
        Route::get('/home_avatars', [PropertyImageController::class, 'homeAvatars']);
    });

    // ----------------- PROPERTY FEATURE-----------------
    Route::prefix('properties/{propertyId}/features')->group(function () {
        Route::get('/all', [PropertyFeatureController::class, 'all']); // Lấy danh sách feature của 1 property
        Route::middleware(['auth:sanctum', 'role:admin,user'])->group(function () {
            Route::post('/create', [PropertyFeatureController::class, 'create']);               // Thêm feature
            Route::put('/sync', [PropertyFeatureController::class, 'sync']);                    // Đồng bộ (        )
            Route::delete('/delete/{featureId}', [PropertyFeatureController::class, 'delete']); // Xóa feature
        });
    });

    // ----------------- FAVORITE/BOOKMARK APIs  -----------------
    Route::prefix('users/{userId}')->group(function () {
        Route::get('/favorites/all', [FavoriteController::class, 'all']);
    });

    Route::prefix('properties/{propertyId}')->group(function () {
        Route::middleware(['auth:sanctum', 'role:admin,user'])->group(function () {
            Route::post('favorite/create/{userId}', [FavoriteController::class, 'create']);
            Route::delete('favorite/delete/{userId}', [FavoriteController::class, 'delete']);
            Route::get('favorite/is_favorite/{userId}', [FavoriteController::class, 'check']);
        });
    });

    // -----------------SEARCH & FILTER APIs  ---------------
    Route::prefix('search')->group(function () {
        Route::get('/properties', [SearchController::class, 'search']);
        Route::get('/filter', [SearchController::class, 'filter']);
        Route::get('/autocomplete', [SearchController::class, 'autocomplete']);
        Route::get('/nearby', [SearchController::class, 'nearby']);
    });

    // -----------------DASHBOARD & ANALYTICS APIs  ---------------
    Route::prefix('admin/dashboard')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('/stats', [DashboardController::class, 'getStats']);
        Route::get('/property_stats', [DashboardController::class, 'getPropertyStats']);
        Route::get('/user_stats', [DashboardController::class, 'getUserStats']);
        Route::get('/recent_properties', [DashboardController::class, 'getRecentProperties']);
        Route::get('/recent_users', [DashboardController::class, 'getRecentUsers']);
    });

    // -------------------REPORT APIs -------------------
    Route::prefix('admin/reports')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('/properties_monthly', [ReportController::class, 'getPropertiesMonthly']);
        Route::get('/users_monthly', [ReportController::class, 'getUsersMonthly']);
        Route::get('/export_properties', [ReportController::class, 'exportProperties']);
        Route::get('/export_users', [ReportController::class, 'exportUsers']);
    });

    Route::get('/debug', function () {
        return response()->json(['status' => 'API is working!']);
    });

    // ----------------- FILE DOWNLOAD (VULNERABLE) -----------------
    // ⚠️ LỖ HỔNG BẢO MẬT: Path Traversal
    Route::prefix('file')->group(function () {
        Route::get('/download', [\App\Http\Controllers\File\FileController::class, 'download']);
        Route::get('/view', [\App\Http\Controllers\File\FileController::class, 'view']);
    });

    // ----------------- SQL INJECTION TEST ENDPOINT (VULNERABLE) -----------------
    // ⚠️ LỖ HỔNG BẢO MẬT: SQL Injection
    // Endpoint này không có route validation để demo SQL Injection
    // Route /properties/detail/{id} có whereNumber() nên không thể test SQL Injection trực tiếp
    Route::get('/test/sql-injection', [\App\Http\Controllers\Property\PropertyController::class, 'testSqlInjection']);
}); // End API Versioning Middleware
