<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::resource('produk', \App\Http\Controllers\ProdukController::class);
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class);
    Route::get('/produk/harga-history', [ProdukController::class, 'historyAll'])->name('produk.history');
    Route::get('/produk/{id}/harga-history', [ProdukController::class, 'historyDetail'])->name('produk.history.detail');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
