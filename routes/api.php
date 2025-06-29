<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');

    //Admin
    Route::post('/create/products', [AdminController::class, 'store'])->name('admin-create-product');
    Route::get('/getall/products', [AdminController::class, 'getall'])->name('getall-products');
    Route::post('/search/product', [AdminController::class, 'search'])->name('search/product');

    //address
    Route::post('/create/address', [AddressController::class, 'store'])->name('create-address');
});
