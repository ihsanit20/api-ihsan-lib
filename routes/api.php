<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('check-phone', [AuthController::class, 'checkPhone']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::get('/authors', [AuthorController::class, 'index']);
Route::get('/authors/{id}', [AuthorController::class, 'show']);

Route::get('/stocks', [StockController::class, 'index']);
Route::get('/stocks/{id}', [StockController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    // Normal auth routs here

    Route::middleware(['role:staff,admin,developer'])->group(function () {

        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::put('/users/{user}', [UserController::class, 'update']);

        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::post('/products/{id}/photo', [ProductController::class, 'uploadPhoto']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);

        Route::post('/authors', [AuthorController::class, 'store']);
        Route::put('/authors/{id}', [AuthorController::class, 'update']);
        Route::post('/authors/{id}/photo', [AuthorController::class, 'uploadPhoto']);

        Route::post('/stocks', [StockController::class, 'store']);
        Route::put('/stocks/{id}', [StockController::class, 'update']);
    });

    Route::middleware(['role:admin,developer'])->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::delete('/authors/{id}', [AuthorController::class, 'destroy']);
        
        Route::delete('/stocks/{id}', [StockController::class, 'destroy']);
    });

    Route::middleware(['role:developer'])->group(function () {

    });

});