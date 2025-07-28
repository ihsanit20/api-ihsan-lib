<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\DeliveryChargeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeExpenseHeadController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitorTrackingController;
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
Route::get('/products/filter', [ProductController::class, 'filter']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/publishers', [PublisherController::class, 'index']);
Route::get('/publishers/{id}', [PublisherController::class, 'show']);

Route::get('/income-expense-heads', [IncomeExpenseHeadController::class, 'index']);
Route::get('/income-expense-heads/{id}', [IncomeExpenseHeadController::class, 'show']);

Route::get('/incomes', [IncomeController::class, 'index']);
Route::get('/incomes/{id}', [IncomeController::class, 'show']);

Route::get('/expenses', [ExpenseController::class, 'index']);
Route::get('/expenses/{id}', [ExpenseController::class, 'show']);

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

Route::get('/divisions', [AddressController::class, 'divisions']);
Route::get('/districts', [AddressController::class, 'districts']);
Route::get('/areas', [AddressController::class, 'areas']);

Route::get('/delivery-charge', [DeliveryChargeController::class, 'show']);

Route::post('/visitor-tracking/count', [VisitorTrackingController::class, 'track'])->withoutMiddleware('throttle');
Route::get('/visitor-tracking/stats', [VisitorTrackingController::class, 'stats'])->withoutMiddleware('throttle');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/online-order', [OrderController::class, 'onlineOrder']);

    Route::get('/my-orders', [OrderController::class, 'myOrders']);
    Route::get('/my-orders/{id}', [OrderController::class, 'myOrder']);

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

        Route::post('/publishers', [PublisherController::class, 'store']);
        Route::put('/publishers/{id}', [PublisherController::class, 'update']);
        Route::post('/publishers/{id}/photo', [PublisherController::class, 'uploadPhoto']);

        Route::post('/income-expense-heads', [IncomeExpenseHeadController::class, 'store']);
        Route::put('/income-expense-heads/{id}', [IncomeExpenseHeadController::class, 'update']);

        Route::post('/incomes', [IncomeController::class, 'store']);
        Route::put('/incomes/{id}', [IncomeController::class, 'update']);

        Route::post('/expenses', [ExpenseController::class, 'store']);
        Route::put('/expenses/{id}', [ExpenseController::class, 'update']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);

        Route::post('/authors', [AuthorController::class, 'store']);
        Route::put('/authors/{id}', [AuthorController::class, 'update']);
        Route::post('/authors/{id}/photo', [AuthorController::class, 'uploadPhoto']);

        Route::post('/stocks', [StockController::class, 'store']);
        Route::put('/stocks/{id}', [StockController::class, 'update']);

        Route::get('/summary', [SummaryController::class, 'getIncomesAndExpenses']);
    });

    Route::middleware(['role:admin,developer'])->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        Route::delete('/publishers/{id}', [PublisherController::class, 'destroy']);

        Route::delete('/income-expense-heads/{id}', [IncomeExpenseHeadController::class, 'destroy']);

        Route::delete('/incomes/{id}', [IncomeController::class, 'destroy']);

        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::delete('/authors/{id}', [AuthorController::class, 'destroy']);

        Route::delete('/stocks/{id}', [StockController::class, 'destroy']);

        Route::post('/galleries', [GalleryController::class, 'uploadPhoto']);
        Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);
        Route::patch('/galleries/{id}/link', [GalleryController::class, 'updateLink']);

        Route::put('/company-info', [CompanyInfoController::class, 'update']);
        Route::post('/upload-logo', [CompanyInfoController::class, 'uploadLogo']);

        Route::put('/delivery-charge', [DeliveryChargeController::class, 'update']);
    });

    Route::middleware(['role:developer'])->group(function () {});
});
