<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'nama_produk',
        'harga',
        'stok',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function produkSatuan()
    {
        return $this->hasMany(ProdukSatuan::class, 'produk_id');
    }
}
