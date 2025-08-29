<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;


// Home route
Route::get('/',[UserController::class,'index'])->name('home');
Route::get('/cart',[UserController::class,'cart'])->name('keranjang');
Route::get('/statuspesanan',[UserController::class,'statusPesanan'])->name('status.pesanan');


// auth route
Route::middleware('guest')->group(function () {
    // login route
Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('login',[AuthController::class,'login'])->name('login.post');
    // register route
Route::get('/regis',[AuthController::class,'showRegister'])->name('register');
Route::post('/register',[AuthController::class,'register'])->name('register.post');
    // reset password
Route::get('/forgot',[AuthController::class,'showForgot'])->name('reset.password');
});
// logout route
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

