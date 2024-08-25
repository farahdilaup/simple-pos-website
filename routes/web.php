<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cart', [CartController::class, 'index'])->name('index');
Route::post('/cart/submit', [CartController::class, 'submit'])->name('cart.submit');
Route::get('/cart/result', [CartController::class, 'result'])->name('cart.result');