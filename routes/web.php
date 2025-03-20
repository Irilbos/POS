<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
}); 

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');



// Product Categories (Route Prefix)
Route::prefix('category')->group(function () {
    Route::get('/food-beverage', [ProductController::class, 'foodBeverage']);
    Route::get('/beauty-health', [ProductController::class, 'beautyHealth']);
    Route::get('/home-care', [ProductController::class, 'homeCare']);
    Route::get('/baby-kid', [ProductController::class, 'babyKid']);

});

// User Page (Route Parameter)
Route::get('/user/{id}/name/{name}', [UserController::class, 'showUser']);

// Sales Page
Route::get('/sales', [SalesController::class, 'index']);
Route::get('/level',[LevelController::class,'index']);
Route::get('/Kategori',[KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index'])->name('index');
// User Page
Route::get('/user', [UserController::class, 'index'])->name('user.index');

// User Tambah
Route::get('/user/tambah', [UserController::class, 'tambah'])->name('user.tambah');
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan'])->name('user.tambah_simpan');

// User Ubah
Route::get('/user/ubah/{id}', [UserController::class, 'ubah'])->name('user.ubah');
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan'])->name('user.ubah_simpan');

// User Hapus
Route::get('/user/hapus/{id}', [UserController::class, 'hapus'])->name('user.hapus');
Route::put('/user/ubah_simpan/{id}',[UserController::class, 'ubah_simpan'])->name('user.ubah_simpan');
