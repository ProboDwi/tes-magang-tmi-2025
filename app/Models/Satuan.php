<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuans';

    protected $fillable = [
        'nama',
        'isi_per_satuan',
    ];

    public function produkSatuan()
    {
        return $this->hasMany(ProdukSatuan::class, 'satuan_id');
    }
}
