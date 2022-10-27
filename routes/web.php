<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ProfileController;

use App\Http\Middleware\Admin;
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

    Route::post('/admin', [AdminLoginController::class, 'loginPage']);
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

