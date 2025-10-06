<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\RobloxProxyController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\TopupPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/services', [FrontController::class, 'services'])->name('front.services');
Route::get('/product/{id}', [FrontController::class, 'productDetail'])->name('front.product.detail');
Route::get('/robux/topup', [TopupPageController::class, 'show'])->name('robux.topup');
Route::post('/robux/topup', [TopupController::class, 'store'])->name('topup.store');
Route::get('/cek-transaksi', [TopupController::class, 'track'])->name('order.track');

/*
 * ROUTE BARU UNTUK VALIDASI LENGKAP
 * Route ini akan menggantikan dua route di bawah.
 */
Route::post('/api/roblox/check', [RobloxProxyController::class, 'findPayableGamepass'])
    ->name('roblox.check');

// routes/web.php
Route::post('/api/roblox/resolve', [RobloxProxyController::class, 'resolve'])
    ->name('roblox.resolve');


/*
 * Route lama (bisa dihapus atau dikomentari)
 *
 * Route::post('/api/roblox/resolve-username', [RobloxProxyController::class, 'resolve'])
 * ->name('roblox.resolve');
 * Route::get('/api/roblox/check-experience/{userId}', [RobloxProxyController::class, 'experience'])
 * ->name('roblox.experience');
*/
