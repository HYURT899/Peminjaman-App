<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AssetAdminController;
use App\Http\Controllers\Admin\PeminjamAdminController;
use App\Http\Controllers\KeranjangPeminjamanController;

// Halaman Awal (dashboard) tanpa login
Route::get('/', function () {
    return view('public.dashboard');
})->name('public.dashboard');

// Route buat ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin', [AdminController::class, 'dataDashboard'])->name('admin.dashboard');

    // Assets
    Route::resource('/admin/assets', AssetAdminController::class)->names('admin.assets');
    Route::get('/admin/asset/{kode_asset}', [AssetAdminController::class, 'showByQr'])->name('asset.qr.show'); // Buat nampilin qr code

    // Kategori
    Route::resource('/admin/category', CategoryController::class)->names('categories');

    // Peminjam
    Route::resource('/admin/peminjam', PeminjamAdminController::class)->names('admin.peminjam');

    Route::patch('admin/peminjam/{nama}/approve', [PeminjamAdminController::class, 'approve'])->name('admin.peminjam.approve');
    Route::patch('admin/peminjam/{nama}/reject', [PeminjamAdminController::class, 'reject'])->name('admin.peminjam.reject');
    Route::patch('admin/peminjam/{nama}/return', [PeminjamAdminController::class, 'return'])->name('admin.peminjam.return');
    Route::get('admin/peminjam/{nama}/print', [PeminjamAdminController::class, 'print'])->name('admin.peminjam.cetak');

    // Users
    Route::resource('/admin/user/users', UserController::class)->names('admin.users');
});

// Route untuk yang udah login
Route::middleware('auth',)->group(function () {
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
