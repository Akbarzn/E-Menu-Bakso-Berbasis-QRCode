<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\public\MenuController;
use App\Http\Controllers\Public\OrderController;

// Route::get('/menu', function () {
//     return view('public.menu');
// });
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/',[QrCodeController::class,'show']);

Route::post('/midtrans/callback', [OrderController::class, 'midtransCallback'])->name('midtrans.callback');
Route::get('/order/{kode_transaksi}', [OrderController::class, 'detail']);

// showFormData pelanggan
Route::post('/order/data', [OrderController::class, 'showForm'] )->name('order.form');

// submit Pesanan
Route::post('/order', [OrderController::class, 'submit'])->name('order.submit');

// callback midtrans
Route::post('/midtrans/callback', [OrderController::class, 'midtransCallback'])->name('midtrans.callback');

// redirect setelah pembayaran
Route::view('/order/success', 'public.success')->name('order.success');
Route::view('/order/failed', 'public.failed')->name('order.failed');
