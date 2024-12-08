<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->group(function () {


    Route::middleware(['role:customer,staff,admin,developer'])->group(function () {

    });

    Route::middleware(['role:staff,admin,developer'])->group(function () {

        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::put('/users/{user}', [UserController::class, 'update']);

        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);

    });

    Route::middleware(['role:admin,developer'])->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    });

    Route::middleware(['role:developer'])->group(function () {

    });

});
