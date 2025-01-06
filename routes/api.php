<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/company-info', [CompanyInfoController::class, 'index']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('check-phone', [AuthController::class, 'checkPhone']);

Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/find', [ProductController::class, 'find']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::get('/authors', [AuthorController::class, 'index']);
Route::get('/authors/{id}', [AuthorController::class, 'show']);

Route::get('/stocks', [StockController::class, 'index']);
Route::get('/stocks/{id}', [StockController::class, 'show']);
Route::get('/available-stocks', [StockController::class, 'getAvailableStocks']);
Route::get('/available-stock/{productId}', [StockController::class, 'getAvailableStockByProductId']);

Route::get('/user-search', [UserController::class, 'searchUser']);

Route::get('/galleries', [GalleryController::class, 'index']);

Route::get('/random-products', [ProductController::class, 'randomProducts']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::post('/payments', [PaymentController::class, 'store']);

    Route::middleware(['role:staff,admin,developer'])->group(function () {
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::post('/collect-due', [PaymentController::class, 'collectDue']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::put('/orders/{id}/status', [OrderController::class, 'update']);
        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
        Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
        Route::get('/orders/report', [OrderController::class, 'report']);

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

        Route::post('/galleries', [GalleryController::class, 'uploadPhoto']);
        Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);
        Route::patch('/galleries/{id}/link', [GalleryController::class, 'updateLink']);

        Route::put('/company-info', [CompanyInfoController::class, 'update']);
        Route::post('/upload-logo', [CompanyInfoController::class, 'uploadLogo']);

    });

    Route::middleware(['role:developer'])->group(function () {});
});
