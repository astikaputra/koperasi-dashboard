<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\GlobalHargaController;
use App\Http\Controllers\OverheadController;
use App\Http\Controllers\OverheadConfigController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// ===========================
// PROTEKSI LOGIN
// ===========================
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // PRODUK
    Route::resource('produk', ProdukController::class);

    // KATEGORI
    Route::resource('kategori', KategoriController::class);

    // HISTORY HARGA PRODUK
    Route::get('/produk/harga-history', [ProdukController::class, 'historyAll'])
        ->name('produk.history');

    Route::get('/produk/{id}/harga-history', [ProdukController::class, 'historyDetail'])
        ->name('produk.history.detail');

    // GLOBAL HARGA
    Route::get('/harga-global', [GlobalHargaController::class, 'index'])
        ->name('markup.index');

    Route::post('/harga-global/preview', [GlobalHargaController::class, 'preview'])
        ->name('harga.global.preview');

    Route::post('/harga-global/apply', [GlobalHargaController::class, 'apply'])
        ->name('harga.global.apply');

    

    // ===========================
    // OVERHEAD (Protected)
    // ===========================
    Route::prefix('overhead')->group(function () {
        Route::get('/', [OverheadController::class, 'index'])->name('overhead.index');
        Route::get('/create', [OverheadController::class, 'create'])->name('overhead.create');
        Route::post('/store', [OverheadController::class, 'store'])->name('overhead.store');
    });

    // ===========================
    // OVERHEAD CONFIG (Protected)
    // ===========================
    Route::prefix('overhead-config')->group(function () {
        Route::get('/', [OverheadConfigController::class, 'index'])->name('overhead.config');
        Route::post('/update', [OverheadConfigController::class, 'update'])->name('overhead.config.update');
    });

    // PROFILE USER
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
