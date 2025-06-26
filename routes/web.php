<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\public\MenuController;

// Route::get('/menu', function () {
//     return view('public.menu');
// });
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/',[QrCodeController::class,'show']);
