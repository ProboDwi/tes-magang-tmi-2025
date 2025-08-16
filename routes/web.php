<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriImportController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\KonversiSatuanController;
use App\Http\Controllers\ProdukSatuanController;
use App\Http\Controllers\SatuanController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// Semua route yang butuh login dimasukkan ke dalam group middleware
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    Route::resource('produk', ProdukController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('barang_masuk', BarangMasukController::class);

    Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak');

    // Route::post('/kategori/import', [KategoriImportController::class, 'import'])->name('kategori.import');

    // 🔹 Route baru untuk manajemen multi satuan
    Route::resource('satuan', SatuanController::class);
    // Route::resource('produk_satuan', ProdukSatuanController::class);

    Route::resource('konversi_satuan', KonversiSatuanController::class);

    // 🔹 Route untuk konversi stok antar satuan (butuh persetujuan)
    // Route::get('/konversi_satuan/persetujuan', [KonversiSatuanController::class, 'persetujuan'])->name('konversi_satuan.persetujuan');
    Route::put('/konversi_satuan/approve/{id}', [KonversiSatuanController::class, 'approve'])->name('konversi_satuan.approve');
    Route::put('/konversi_satuan/reject/{id}', [KonversiSatuanController::class, 'reject'])->name('konversi_satuan.reject');
    Route::resource('konversi_satuan', KonversiSatuanController::class)->except(['show']);

    Route::resource('produk_satuan', ProdukSatuanController::class);

    // Rute baru untuk mendapatkan harga secara dinamis
    Route::get('/produk-satuan/get-harga', [ProdukSatuanController::class, 'getHargaByProduk'])->name('produk_satuan.getHarga');
});
