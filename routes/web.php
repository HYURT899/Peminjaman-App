<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AssetAdminController;
use App\Http\Controllers\Admin\PeminjamAdminController;
use App\Http\Controllers\KeranjangPeminjamanController;

Route::redirect('/', 'login');

// Route buat ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin', [AdminController::class, 'dataDashboard'])->name('admin.dashboard');

    // Assets
    Route::resource('/admin/assets', AssetAdminController::class)->names('admin.assets');

    // Kategori
    Route::resource('/admin/category', CategoryController::class)->names('categories');

    // Peminjam
    Route::resource('/admin/peminjam', PeminjamAdminController::class)->names('admin.peminjam');
    Route::prefix('/admin/peminjam')->name('admin.peminjam.')->group(function () {
        Route::get('group/{request_id}', [PeminjamAdminController::class, 'showGroup'])->name('showGroup');
        Route::patch('group/{request_id}/approve', [PeminjamAdminController::class, 'approveGroup'])->name('approveGroup');
        Route::patch('group/{request_id}/reject', [PeminjamAdminController::class, 'rejectGroup'])->name('rejectGroup');
        Route::patch('group/{request_id}/return', [PeminjamAdminController::class, 'returnGroup'])->name('returnGroup');
        Route::get('group/{request_id}/cetak', [PeminjamAdminController::class, 'printGroup'])->name('cetakGroup');
        Route::delete('group/{request_id}', [PeminjamAdminController::class, 'destroyGroup'])->name('destroyGroup');
    });

    // Users
    Route::resource('/admin/user/users', UserController::class)->names('admin.users');
});

// Route untuk yang udah login
Route::middleware('auth',)->group(function () {
    // Dashboard untuk user yang sudah login
    Route::get('/dashboard',[HomeController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Halaman daftar asset
    Route::resource('/daftar_asset', AssetController::class)->names('public.assets');

    // Keranjang
    // daftar isi keranjang
    Route::get('/keranjang', [KeranjangPeminjamanController::class, 'index'])->name('keranjang.index');
    // tambah ke keranjang
    Route::post('/keranjang/add', [KeranjangPeminjamanController::class, 'add'])->name('keranjang.add');

    // submit keranjang → jadi peminjaman
    Route::post('/keranjang/submit', [KeranjangPeminjamanController::class, 'submit'])->name('keranjang.submit');
    Route::delete('/keranjang/{id}', [KeranjangPeminjamanController::class, 'delete'])->name('keranjang.delete');
    Route::patch('/keranjang/{id}/update', [KeranjangPeminjamanController::class, 'update'])->name('keranjang.update');
});

require __DIR__ . '/auth.php';
