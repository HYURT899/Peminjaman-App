<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AssetAdminController;
use App\Http\Controllers\Admin\PeminjamAdminController;

// Halaman Awal (dashboard) tanpa login
Route::get('/', function () {
    return view('public.dashboard');
})->name('public.dashboard');

Route::get('/peminjaman/{id}/print', [PrintController::class, 'show'])->name('peminjaman.print');

// Route buat ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin', [AdminController::class, 'dataDashboard'])->name('admin.dashboard');

    // Assets
    Route::resource('/admin/assets', AssetAdminController::class)->names('admin.assets');
    Route::get('/admin/asset/{kode_asset}', [AssetAdminController::class, 'showByQr'])->name('asset.qr.show'); // Buat nampilin qr code

    // Kategori
    Route::resource('/admin/category', CategoryController::class)->names('categories');

    // Peminjam
    Route::resource('/admin/peminjam', PeminjamAdminController::class)->names('admin.peminjam');
    Route::get('/admin/peminjam/{id}/print', [PeminjamAdminController::class, 'print'])->name('peminjam.print');

    // Custom routes untuk approve/reject/return di peminjam
    Route::patch('/admin/peminjam/{id}/approve', [PeminjamAdminController::class, 'approve'])->name('admin.peminjam.approve');
    Route::patch('/admin/peminjam/{id}/reject', [PeminjamAdminController::class, 'reject'])->name('admin.peminjam.reject');
    Route::patch('/admin/peminjam/{id}/return', [PeminjamAdminController::class, 'return'])->name('admin.peminjam.return');
    
    // Users
    Route::resource('/admin/user/users', UserController::class)->names('admin.users');
});

// Route untuk yang udah login
Route::middleware('auth')->group(function () {
    // Dashboard untuk user yang sudah login
    Route::get('/dashboard', function () {
        return view('public.dashboard');
    })->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Halaman daftar asset
    Route::resource('/daftar_asset', AssetController::class)->names('assets');

    // Peminjam melakukan peminjaman
    Route::post('/peminjam', [PeminjamController::class, 'store'])->name('peminjam.store');
});

require __DIR__ . '/auth.php';
