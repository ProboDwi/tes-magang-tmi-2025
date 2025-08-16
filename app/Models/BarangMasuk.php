<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'produk_id',
        'jumlah_barang',
        'satuan',
        'harga_beli',
        'total_harga',
        'tanggal_masuk',
        'tanggal_kadaluwarsa',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
