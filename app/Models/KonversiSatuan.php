<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonversiSatuan extends Model
{
    protected $table = 'konversi_satuans';
    protected $fillable = [
        'produk_id', 'dari_satuan_id', 'ke_satuan_id',
        'jumlah_konversi', 'stok_dipotong', 'stok_ditambah', 'status'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function dariSatuan()
    {
        return $this->belongsTo(Satuan::class, 'dari_satuan_id');
    }

    public function keSatuan()
    {
        return $this->belongsTo(Satuan::class, 'ke_satuan_id');
    }
}
