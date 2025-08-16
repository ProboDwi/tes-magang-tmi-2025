<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('konversi_satuans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id');
            $table->unsignedBigInteger('dari_satuan_id'); // contoh: Ball
            $table->unsignedBigInteger('ke_satuan_id');   // contoh: Bungkus
            $table->integer('jumlah_konversi');           // misal: 1 ball -> 20 bungkus
            $table->integer('stok_dipotong');
            $table->integer('stok_ditambah');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
            // $table->foreign('dari_satuan_id')->references('id')->on('satuans')->onDelete('cascade');
            // $table->foreign('ke_satuan_id')->references('id')->on('satuans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konversi_satuans');
    }
};
