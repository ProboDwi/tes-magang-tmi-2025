<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriImportController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// routes/web.php
// Route::get('/', function () {
//     return view('adminlte::page');
// });

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('produk', ProdukController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('transaksi', TransaksiController::class);

Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak');

Route::post('/kategori/import', [KategoriImportController::class, 'import'])->name('kategori.import');



