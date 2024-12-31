<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ClientController;
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
    return view('welcome');
});

Route::get('/admin/register', [AuthController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AuthController::class, 'register'])->name('admin.register.post');

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth:admin')->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients/create', [ClientController::class, 'store'])->name('clients.store');
    Route::put('/clients/update/{id}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/delete/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::post('/clients/migrate/{id}', [ClientController::class, 'migrate'])->name('clients.migrate');
});
