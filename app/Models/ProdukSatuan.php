<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukSatuan extends Model
{
     use HasFactory;

    protected $table = 'produk_satuans';

    protected $fillable = [
        'produk_id',
        'harga',
        'stok',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'produksatuan_id');
    }

}
