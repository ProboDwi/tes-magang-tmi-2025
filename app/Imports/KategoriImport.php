<?php

namespace App\Imports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KategoriImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Kategori([
            'nama' => $row['nama'], // Pastikan nama kolom Excel adalah "nama"
        ]);
    }
}
