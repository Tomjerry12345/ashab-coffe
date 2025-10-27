<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\MinumanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/order/meja/{meja:nomorMeja}/{kategori?}', [OrderController::class, 'create'])
    ->name('order.meja');

Route::post('/order/checkout/{meja:nomorMeja}', [OrderController::class, 'checkout'])->name('order.checkout');

Route::get('/order/me/{meja_id}', [OrderController::class, 'orderMe']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::resource('makanan', MakananController::class)->except(['show', 'create', 'edit']);

    Route::resource('minuman', MinumanController::class)->except(['show', 'create', 'edit']);

    Route::resource('meja', MejaController::class)->except(['show', 'create', 'edit']);

    Route::resource('bank', BankController::class);


    Route::get('/order/baru', [OrderController::class, 'orderBaru'])
        ->name('order.baru');

    Route::get('/order/rekap', [OrderController::class, 'rekap'])
        ->name('order.rekap');

    Route::post('/order/{order_key}/update-status', [OrderController::class, 'konfirmasiPesanan'])->name('order.updateStatus');
    Route::post('/order/{order_key}/batal', [OrderController::class, 'batalPesanan'])->name('order.batal');
});
