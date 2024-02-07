<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\SubCategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
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

// Admin routes
Route::group(['prefix' => 'admin'], function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware('admin')->group(function () {
            // Logged in routes
            Route::get('/user', [AuthController::class, 'user']);
            Route::delete('/logout', [AuthController::class, 'logout']);

            // brand
            // Route::post("/brand/create", [BrandController::class, 'create']);
            // Route::get("/brand/list", [BrandController::class, 'list']);

            // category
            Route::post("/category/create", [CategoryController::class, 'create']);
            Route::get("/category/list", [CategoryController::class, 'list']);
            Route::put("/category/update", [CategoryController::class, 'update']);
            Route::get("/category/getAllCategories", [CategoryController::class, 'getAllCategories']);
            // Route::get("/brand/list", [BrandController::class, 'list']);

            // sub category
            Route::post("/sub-category/create", [SubCategoryController::class, 'create']);
            Route::get("/sub-category/list", [SubCategoryController::class, 'list']);
            Route::get("/sub-category/getAllSubCategories", [SubCategoryController::class, 'getAllSubCategories']);

            Route::get("/getUsers", [DashboardController::class, 'getUsers']);
            Route::get("/getUserbyId", [DashboardController::class, 'getUserById']);


            Route::put("/updateProfileIds", [DashboardController::class, 'updateProfileIds']);
        });
    });
});

// User Routes
Route::group(['prefix' => 'user'], function () {
    Route::post("/login", [AuthController::class, 'login']);
    Route::post("/register", [AuthController::class, 'register']);

    Route::get("/getUsers", [DashboardController::class, 'getUsers']);

    Route::middleware('auth:sanctum')->group(function () {
        // Logged in routes 
        Route::get('/user', [AuthController::class, 'user']);
        Route::delete('/logout', [AuthController::class, 'logout']);


        Route::post('/updateProfile', [ProfileController::class, 'update']);

        // userlist

    });


    // Route::get('/user', [AuthController::class, 'user']);
    // Route::post('/logout', [AuthController::class, 'logout']);
    // Route::get('/getUser/{id}', [UserController::class, 'update']);

    // Route::apiResource('/users', UserController::class);
});
