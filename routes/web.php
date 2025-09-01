<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AssetAdminController;
use App\Http\Controllers\Admin\PeminjamAdminController;
use App\Http\Controllers\Admin\CategoryController;

// Halaman Awal tanpa login
Route::get('/', function () {
    return view('welcome');
});

// Route buat ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Assets
    Route::resource('/admin/assets', AssetAdminController::class)->names('admin.assets');

    // Kategori
    Route::resource('/admin/category', CategoryController::class)->names('categories');

    // Peminjam
    Route::get('/admin/peminjam', [PeminjamController::class, 'index'])->name('admin.peminjam.index');
});

// Route untuk USER / Peminjam biasa
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Halaman daftar asset
    Route::get('/daftar', [AssetController::class, 'index'])->name('assets.index');

    // Peminjam melakukan peminjaman
    Route::post('/peminjam', [PeminjamController::class, 'store'])->name('peminjam.store');
});

require __DIR__.'/auth.php';
