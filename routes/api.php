<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

Route::group([
    'middleware' => 'api',
], function () {
    // Admin Routes
    Route::post('/admin-login', [AuthController::class, 'adminLogin']);

    // User Routes
    Route::post('/user-login', [AuthController::class, 'userLogin']);
    Route::post('/user-register', [AuthController::class, 'userRegister']);
});

Route::group([
    'middleware' => ['api', 'auth:admin'],
], function () {
    // Admin Routes
    Route::post('/admin-logout', [AuthController::class, 'adminLogout']);

    Route::get('/admin-dashboard', [AdminController::class, 'adminDashboard']);

    // Admin User Management Routes
    Route::get('/view-users', [AdminController::class, 'listUsers']);
    Route::get('/view-user/{id}', [AdminController::class, 'viewUser']);
    Route::patch('/update-user/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser']);

    // Admin Food Management Routes
    Route::get('/foods', [AdminController::class, 'listFoods']);
    Route::post('/foods', [AdminController::class, 'createFood']);
    Route::patch('/foods/{id}', [AdminController::class, 'updateFood']);
    Route::delete('/foods/{id}', [AdminController::class, 'deleteFood']);
});

Route::group([
    'middleware' => ['api', 'auth:user'],
], function () {
    // User Routes
    Route::post('/user-logout', [AuthController::class, 'userLogout']);

    Route::get('/user-dashboard', [UserController::class, 'userDashboard']);
    Route::patch('/update-profile', [UserController::class, 'updateProfile']);
    Route::get('/view-profile', [UserController::class, 'viewProfile']);

    // User Food Management Routes
    Route::get('/my-foods', [UserController::class, 'getMyFoods']);
    Route::post('/my-foods', [UserController::class, 'addMyFood']);
    Route::patch('/my-foods/{id}', [UserController::class, 'updateMyFood']);
    Route::delete('/my-foods/{id}', [UserController::class, 'deleteMyFood']);

    // Food Recommendation Routes
    Route::get('/view-recommendation/{food_id}', [UserController::class, 'getRecommendedFoods']);
    Route::get('/view-available-foods', [UserController::class, 'viewAvailableFoods']);
});
