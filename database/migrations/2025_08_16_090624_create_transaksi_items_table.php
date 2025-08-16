<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Drop tabel transaksi yang lama jika sudah ada
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('transaksi_items');

        // Buat ulang tabel transaksi tanpa foreign key ke produk
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->default(now());
            $table->integer('total_harga')->default(0); // Total harga akan dihitung dari item-item di bawah
            $table->timestamps();
        });

        // Buat tabel baru untuk menyimpan detail setiap item dalam transaksi
        Schema::create('transaksi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            
            // Kolom ini akan menyimpan ID produk dari salah satu tabel
            $table->foreignId('produk_id')->nullable()->constrained('produks')->onDelete('cascade');
            $table->foreignId('produksatuan_id')->nullable()->constrained('produk_satuans')->onDelete('cascade');

            $table->integer('jumlah');
            $table->integer('harga_saat_transaksi'); // Simpan harga saat transaksi terjadi
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_items');
        Schema::dropIfExists('transaksi');
    }
};
