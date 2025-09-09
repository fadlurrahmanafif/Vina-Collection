<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// user route
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/cart', [UserController::class, 'cart'])->name('keranjang');
Route::get('/statuspesanan', [UserController::class, 'statusPesanan'])->name('status.pesanan');
Route::get('/checkout', [UserController::class, 'checkout'])->name('checkout');

// auth admin route
Route::middleware('guest')->group(function () {
    // login route
    Route::get('/adminlogin', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/adminlogin', [AdminController::class, 'loginAdmin'])->name('login.admin');
});

// Admin route
Route::middleware(['admin'])->group(function () {
    Route::get('/dasboard', [AdminController::class, 'dasboard'])->name('dasboard');
    Route::get('/datapesanan', [AdminController::class, 'dataPesanan'])->name('data.pesanan');
    Route::get('/userdata', [AdminController::class, 'userData'])->name('user.data');

    // product crud route
    Route::get('/product', [AdminController::class, 'product'])->name('product');
    Route::get('/product/addModal', [AdminController::class, 'addModal'])->name('add.modal');
    Route::post('/product/addData', [AdminController::class, 'store'])->name('add.data');
    Route::get('/product/editModal/{id}', [AdminController::class, 'editModal'])->name('edit.modal');
    Route::put('/product/updateData/{id}', [AdminController::class, 'update'])->name('update.data');
    Route::delete('/product/deleteData/{id}', [AdminController::class, 'destroy'])->name('delete.data');

    Route::post('/logoutadmin', [AdminController::class, 'logoutadmin'])->name('logout.admin');
});


// auth user route
Route::middleware('guest')->group(function () {
    // login route
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    // register route
    Route::get('/regis', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    // reset password
    Route::get('/forgot', [AuthController::class, 'showForgot'])->name('reset.password');
});

// user logout route
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
