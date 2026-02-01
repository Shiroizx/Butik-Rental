<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClothController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ClothesSizeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReturnController;

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

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('customers', CustomerController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('clothes', ClothController::class);
    Route::resource('rentals', RentalController::class);
    Route::post('/payments/{id}/update-status', [App\Http\Controllers\PaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::resource('returns', ReturnController::class)->only(['create', 'store']);
    Route::resource('clothes-sizes', ClothesSizeController::class);

    Route::middleware(['admin'])->group(function () {
        Route::resource('employees', EmployeeController::class);
    });
});
