<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ProfileController;

use App\Http\Middleware\Admin;
use App\Http\Middleware\TollOperator;
use App\Http\Middleware\Guest;
use App\Http\Middleware\User;

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
Route::middleware([Guest::class])->group(function () {
    Route::get('/', [LoginController::class, 'loginPage']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'registerPage']);
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/admin', [AdminLoginController::class, 'loginPage']);
    Route::post('/admin', [AdminLoginController::class, 'login']);
});

Route::middleware([User::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    Route::post('/addcard', [DashboardController::class, 'addCard']);

    Route::get('/pay', [BillController::class, 'payPage']);
    Route::post('/pay', [BillController::class, 'pay']);
    Route::get('/success', [BillController::class, 'paySuccess']);
    Route::get('/cancel', [BillController::class, 'payCancel']);

    Route::get('/bills', [BillController::class, 'billPage']);
    Route::get('/receipts', [BillController::class, 'receiptPage']);
    Route::get('/pdf/bill/{id}', [BillController::class, 'pdfBill']);
    Route::get('/pdf/receipt/{id}', [BillController::class, 'pdfReceipt']);

    Route::get('/profile', [ProfileController::class, 'profilePage']);
    Route::post('/profile', [ProfileController::class, 'updateProfile']);

    Route::get('/logout', [LoginController::class, 'logout']);
});

Route::get('/admin/logout', [AdminLoginController::class, 'logout']);

Route::middleware([Admin::class])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'userList']);
    Route::get('/admin/create/user', [AdminController::class, 'createUserPage']);
    Route::post('/admin/create/user', [AdminController::class, 'createUser']);
    Route::get('/admin/delete/user/{id}', [AdminController::class, 'deleteUser']);
    Route::get('/admin/user/{id}', [AdminController::class, 'userDetails']);
    Route::post('/admin/user/{id}', [AdminController::class, 'userUpdate']);
    Route::get('/admin/ucard/{id}', [AdminController::class, 'userCards']);
    Route::post('/admin/ucard/{id}', [AdminController::class, 'assignCard']);
});

Route::middleware([TollOperator::class])->group(function () {
    Route::get('/operator/stations', [OperatorController::class, 'dashboard']);
});
